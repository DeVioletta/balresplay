<?php
// FILE: balresplay/actions/validate_pending_order.php

require_once __DIR__ . '/../config/database.php';
startSecureSession();

header('Content-Type: application/json');

$order_id = $_GET['order_id'] ?? null;

if (!$order_id) {
    echo json_encode(['valid' => false, 'reason' => 'No ID']);
    exit;
}

// Cek status order di database
$stmt = $db->prepare("SELECT status FROM orders WHERE order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Valid HANYA JIKA status masih 'Menunggu Pembayaran'
    if ($row['status'] === 'Menunggu Pembayaran') {
        echo json_encode(['valid' => true]);
    } else {
        // Jika status sudah 'Kirim ke Dapur' (Lunas) atau 'Dibatalkan'
        echo json_encode(['valid' => false, 'reason' => 'Status changed']);
    }
} else {
    // Jika data tidak ditemukan (artinya sudah dihapus karena Expired)
    echo json_encode(['valid' => false, 'reason' => 'Order deleted/expired']);
}

$stmt->close();
?>