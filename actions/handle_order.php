<?php
/**
 * File: handle_order.php
 * Deskripsi: Endpoint utama untuk memproses pesanan baru dari pelanggan.
 * Fungsi: Validasi, Insert Database (Transaction), dan Request Midtrans Snap Token.
 */

// Nonaktifkan display error HTML agar tidak merusak format JSON respons
ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/midtrans.php';

startSecureSession();
header('Content-Type: application/json');

try {
    // ----------------------------------------------------------------------
    // 1. Persiapan Data & Validasi
    // ----------------------------------------------------------------------
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);

    if (empty($data) || !isset($data['cartData'])) {
        throw new Exception("Data pesanan tidak lengkap.");
    }

    // Validasi apakah sesi pelanggan masih ada
    $customer_id = $_SESSION['customer_id'] ?? '';
    if (empty($customer_id)) {
        throw new Exception("Sesi Anda telah berakhir. Silakan refresh halaman menu.");
    }

    $cart_data = $data['cartData'];
    $table_number = (int)$data['tableNumber'];
    $payment_method = $data['paymentMethod']; 
    $service_fee = 2000;

    // ----------------------------------------------------------------------
    // 2. Database Transaction (Start)
    // ----------------------------------------------------------------------
    // Menggunakan transaksi agar jika gagal insert item, order utama juga batal.
    $db->begin_transaction();

    // Hitung Total Harga & Gabungkan Catatan
    $total_price = $service_fee;
    $notes_arr = [];
    foreach ($cart_data as $item) {
        $total_price += ($item['price'] * $item['quantity']);
        if(!empty($item['notes'])) {
            $notes_arr[] = $item['name'] . ": " . $item['notes'];
        }
    }
    $notes_string = implode("; ", $notes_arr);

    // ----------------------------------------------------------------------
    // 3. Insert ke Tabel `orders`
    // ----------------------------------------------------------------------
    $stmt = $db->prepare("INSERT INTO orders (customer_id, table_number, total_price, payment_method, notes, status, order_time) VALUES (?, ?, ?, ?, ?, 'Menunggu Pembayaran', NOW())");
    $stmt->bind_param("sidss", $customer_id, $table_number, $total_price, $payment_method, $notes_string);
    
    if (!$stmt->execute()) {
        throw new Exception("Database Error (Order): " . $stmt->error);
    }
    $order_id = $db->insert_id;
    $stmt->close();

    // ----------------------------------------------------------------------
    // 4. Insert ke Tabel `order_items`
    // ----------------------------------------------------------------------
    $stmt_item = $db->prepare("INSERT INTO order_items (order_id, variant_id, quantity, price_per_item) VALUES (?, ?, ?, ?)");
    foreach ($cart_data as $item) {
        $stmt_item->bind_param("iiid", $order_id, $item['variant_id'], $item['quantity'], $item['price']);
        if (!$stmt_item->execute()) {
            throw new Exception("Database Error (Item): " . $stmt_item->error);
        }
    }
    $stmt_item->close();

    // ----------------------------------------------------------------------
    // 5. Integrasi Midtrans (Jika QRIS)
    // ----------------------------------------------------------------------
    $snapToken = null;
    if ($payment_method === 'QRIS') {
        try {
            $snapToken = getSnapToken($order_id, $total_price);
        } catch (Exception $midtransError) {
            throw new Exception("Midtrans Gagal: " . $midtransError->getMessage());
        }
    }

    // ----------------------------------------------------------------------
    // 6. Commit & Response
    // ----------------------------------------------------------------------
    $db->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Pesanan berhasil dibuat.',
        'order_id' => $order_id,
        'snap_token' => $snapToken
    ]);

} catch (Exception $e) {
    // Rollback: Batalkan semua perubahan database jika terjadi error
    if (isset($db)) { $db->rollback(); }
    
    echo json_encode([
        'status' => 'error', 
        'message' => $e->getMessage()
    ]);
}
?>