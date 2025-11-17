<?php
require_once __DIR__ . '/../config/database.php';
startSecureSession();
header('Content-Type: application/json');

if (!isset($_GET['meja'])) {
    echo json_encode(['status' => 'error', 'message' => 'Nomor meja tidak disediakan.']);
    exit;
}
$table_number = (int)$_GET['meja'];

// 1. (DIUBAH) Ambil SEMUA pesanan aktif untuk meja ini (LIMIT 1 dihapus)
// (DIUBAH) Urutkan dari yang PALING LAMA (ASC) agar antrian benar
$sql_order = "
    SELECT order_id, status, notes, order_time, total_price
    FROM orders 
    WHERE table_number = ? 
      AND status NOT IN ('Selesai', 'Dibatalkan')
    ORDER BY order_time ASC
";
$stmt = $db->prepare($sql_order);
$stmt->bind_param("i", $table_number);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // (DIUBAH) Kembalikan array kosong agar konsisten
    echo json_encode(['status' => 'empty', 'orders' => []]);
    exit;
}

// (DIUBAH) Siapkan array untuk menampung semua pesanan
$all_orders = [];
$orders_data = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// 2. (DIUBAH) Siapkan query item SATU KALI
$sql_items = "
    SELECT 
        oi.quantity,
        oi.price_per_item,
        (oi.price_per_item * oi.quantity) as subtotal,
        p.name as product_name,
        pv.variant_name as variant
    FROM order_items oi
    JOIN product_variants pv ON oi.variant_id = pv.variant_id
    JOIN products p ON pv.product_id = p.product_id
    WHERE oi.order_id = ?
";
$stmt_items = $db->prepare($sql_items);

// 3. (DIUBAH) Loop untuk setiap pesanan dan ambil item-nya
foreach ($orders_data as $order) {
    $order_id = $order['order_id'];
    
    $stmt_items->bind_param("i", $order_id);
    $stmt_items->execute();
    $items_result = $stmt_items->get_result();
    $items = $items_result->fetch_all(MYSQLI_ASSOC);

    // Buat objek respons untuk pesanan ini
    $response_order = [
        'order_id' => (int)$order_id,
        'status' => $order['status'],
        'orderTime' => strtotime($order['order_time']) * 1000,
        'notes' => $order['notes'], // Menggunakan catatan utama yang sudah digabung
        'total_price' => (float)$order['total_price'],
        'items' => $items
    ];
    
    // Tambahkan ke array utama
    $all_orders[] = $response_order;
}

// 4. Kirim respons (berisi array 'orders')
echo json_encode(['status' => 'found', 'orders' => $all_orders]);

$stmt_items->close();
$db->close();
?>