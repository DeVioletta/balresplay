<?php
require_once __DIR__ . '/../config/database.php';
startSecureSession();
header('Content-Type: application/json');

// --- [UPDATE] Validasi Customer ID ---
// Kita tidak lagi mengecek $_GET['meja'] untuk query, demi keamanan
if (!isset($_SESSION['customer_id'])) {
    // Jika tidak ada sesi ID, berarti user belum pernah buka menu.php atau sesi habis
    echo json_encode(['status' => 'empty', 'message' => 'No session ID']);
    exit;
}

$customer_id = $_SESSION['customer_id'];

// 1. Ambil SEMUA pesanan aktif milik customer_id ini
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

// 2. Siapkan query item
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

// 3. Loop untuk setiap pesanan dan ambil item-nya
foreach ($orders_data as $order) {
    $order_id = $order['order_id'];
    
    $stmt_items->bind_param("i", $order_id);
    $stmt_items->execute();
    $items_result = $stmt_items->get_result();
    $items = $items_result->fetch_all(MYSQLI_ASSOC);

    $response_order = [
        'order_id' => (int)$order_id,
        'table_number' => (int)$order['table_number'], // Kirim info meja juga untuk verifikasi visual
        'status' => $order['status'],
        'orderTime' => strtotime($order['order_time']) * 1000,
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