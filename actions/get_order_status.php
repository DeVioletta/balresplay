<?php
require_once __DIR__ . '/../config/database.php';
startSecureSession();
header('Content-Type: application/json');

if (!isset($_GET['meja'])) {
    echo json_encode(['status' => 'error', 'message' => 'Nomor meja tidak disediakan.']);
    exit;
}
$table_number = (int)$_GET['meja'];

// 1. Ambil pesanan aktif terbaru untuk meja ini (status selain Selesai/Dibatalkan)
$sql_order = "
    SELECT order_id, status, notes, order_time, total_price
    FROM orders 
    WHERE table_number = ? 
      AND status NOT IN ('Selesai', 'Dibatalkan')
    ORDER BY order_time DESC 
    LIMIT 1
";
$stmt = $db->prepare($sql_order);
$stmt->bind_param("i", $table_number);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo json_encode(['status' => 'empty', 'message' => 'Tidak ada pesanan aktif untuk meja ini.']);
    exit;
}

$order = $result->fetch_assoc();
$order_id = $order['order_id'];

// 2. Ambil detail item pesanan
// PERBAIKAN: Hapus kolom oi.notes karena tidak ada di tabel order_items. Catatan sudah ada di $order['notes'].
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
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$items_result = $stmt_items->get_result();
$items = $items_result->fetch_all(MYSQLI_ASSOC);

// 3. Kirim respons
$response = [
    'status' => 'found',
    'order' => [
        'order_id' => (int)$order_id,
        'status' => $order['status'],
        'orderTime' => strtotime($order['order_time']) * 1000,
        'notes' => $order['notes'], // Menggunakan catatan utama yang sudah digabung
        'total_price' => (float)$order['total_price'],
        'items' => $items
    ]
];

echo json_encode($response);
$stmt->close();
$stmt_items->close();
$db->close();
?>