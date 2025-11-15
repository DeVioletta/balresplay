<?php
require_once __DIR__ . '/../config/database.php';
startSecureSession();

// Set header respons ke JSON karena file ini akan diakses oleh fetch()
header('Content-Type: application/json');

// 1. Baca data JSON mentah dari body request
// Kita tidak menggunakan $_POST karena fetch() mengirim JSON sebagai raw body
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

// 2. Validasi data input
if (empty($data) || !isset($data['cartData']) || !isset($data['tableNumber']) || !isset($data['paymentMethod']) || empty($data['cartData'])) {
    // Kirim error jika data tidak lengkap
    echo json_encode(['status' => 'error', 'message' => 'Data pesanan tidak lengkap.']);
    exit;
}

// Ambil data dari JSON yang sudah di-decode
$cart_data = $data['cartData'];
$table_number = (int)$data['tableNumber'];
$payment_method = $data['paymentMethod']; // 'Cash' atau 'QRIS'
$service_fee = 2000; // Biaya layanan (sesuai hardcode di payment.php)

// Mulai transaksi database
// Ini penting agar jika salah satu item gagal, seluruh pesanan dibatalkan
$db->begin_transaction();

try {
    // 3. Hitung total harga dan gabungkan semua catatan item
    $total_price = $service_fee;
    $all_notes = [];
    foreach ($cart_data as $item) {
        $total_price += (float)$item['price'] * (int)$item['quantity'];
        // Gabungkan catatan jika ada
        if (!empty($item['notes'])) {
            $all_notes[] = htmlspecialchars($item['name']) . ": " . htmlspecialchars($item['notes']);
        }
    }
    // Gabungkan semua catatan menjadi satu string, dipisah dengan "; "
    $notes_string = implode('; ', $all_notes);

    // 4. Masukkan data pesanan utama ke tabel 'orders'
    // Kita gunakan status 'Menunggu Pembayaran' sesuai rencana 6 status
    $stmt_order = $db->prepare(
        "INSERT INTO orders (table_number, total_price, payment_method, notes, status, order_time) 
         VALUES (?, ?, ?, ?, 'Menunggu Pembayaran', NOW())"
    );
    $stmt_order->bind_param("idss", $table_number, $total_price, $payment_method, $notes_string);
    
    if (!$stmt_order->execute()) {
        // Jika gagal, lempar error untuk memicu rollback
        throw new Exception("Gagal menyimpan pesanan utama: " . $stmt_order->error);
    }

    // Ambil ID dari pesanan yang baru saja kita masukkan
    $order_id = $db->insert_id;

    // 5. Masukkan setiap item di keranjang ke tabel 'order_items'
    $stmt_item = $db->prepare(
        "INSERT INTO order_items (order_id, variant_id, quantity, price_per_item) 
         VALUES (?, ?, ?, ?)"
    );

    foreach ($cart_data as $item) {
        // Ambil data spesifik per item
        $variant_id = (int)$item['variant_id']; // Ini WAJIB dikirim dari JavaScript
        $quantity = (int)$item['quantity'];
        $price_per_item = (float)$item['price'];

        $stmt_item->bind_param("iiid", $order_id, $variant_id, $quantity, $price_per_item);
        if (!$stmt_item->execute()) {
            // Jika satu item gagal, lempar error untuk memicu rollback
            throw new Exception("Gagal menyimpan item pesanan: " . $stmt_item->error);
        }
    }

    // 6. Jika semua query (order utama dan semua item) berhasil, commit transaksi
    $db->commit();
    
    // Kirim respons sukses kembali ke JavaScript
    echo json_encode([
        'status' => 'success', 
        'message' => 'Pesanan berhasil dibuat.',
        'order_id' => $order_id
    ]);

} catch (Exception $e) {
    // 7. Jika terjadi error di salah satu langkah, batalkan semua perubahan
    $db->rollback();
    
    // Kirim respons error kembali ke JavaScript
    echo json_encode([
        'status' => 'error', 
        'message' => $e->getMessage()
    ]);
}

// Selalu tutup koneksi
$db->close();
?>