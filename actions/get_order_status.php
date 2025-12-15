<?php
/**
 * File: get_order_status.php
 * Deskripsi: API Endpoint untuk Client Side (Polling).
 * Fungsi: Mengambil status pesanan aktif berdasarkan Session ID pelanggan.
 */

require_once __DIR__ . '/../config/database.php';
startSecureSession();
header('Content-Type: application/json');

// --------------------------------------------------------------------------
// 1. Validasi Sesi Pelanggan
// --------------------------------------------------------------------------
// Memastikan hanya pelanggan dengan sesi valid yang bisa melihat pesanan mereka.
if (!isset($_SESSION['customer_id'])) {
    echo json_encode(['status' => 'empty', 'message' => 'No session ID']);
    exit;
}

$customer_id = $_SESSION['customer_id'];

// --------------------------------------------------------------------------
// 2. Query Data Pesanan Utama
// --------------------------------------------------------------------------
// Mengambil pesanan yang belum 'Selesai' atau 'Dibatalkan'.
$sql_order = "
    SELECT order_id, status, notes, order_time, total_price, table_number
    FROM orders 
    WHERE customer_id = ? 
      AND status NOT IN ('Selesai', 'Dibatalkan')
    ORDER BY order_time ASC
";
$stmt = $db->prepare($sql_order);
$stmt->bind_param("s", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo json_encode(['status' => 'empty', 'orders' => []]);
    exit;
}

$all_orders = [];
$orders_data = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// --------------------------------------------------------------------------
// 3. Query Detail Item per Pesanan
// --------------------------------------------------------------------------
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

foreach ($orders_data as $order) {
    $order_id = $order['order_id'];
    
    $stmt_items->bind_param("i", $order_id);
    $stmt_items->execute();
    $items_result = $stmt_items->get_result();
    $items = $items_result->fetch_all(MYSQLI_ASSOC);

    // Struktur data yang dikembalikan ke frontend
    $response_order = [
        'order_id' => (int)$order_id,
        'table_number' => (int)$order['table_number'],
        'status' => $order['status'],
        'orderTime' => strtotime($order['order_time']) * 1000, // Timestamp JS (ms)
        'notes' => $order['notes'],
        'total_price' => (float)$order['total_price'],
        'items' => $items
    ];
    
    $all_orders[] = $response_order;
}

echo json_encode(['status' => 'found', 'orders' => $all_orders]);

$stmt_items->close();
$db->close();
?>