<?php
/**
 * File: validate_pending_order.php
 * Deskripsi: Memvalidasi apakah pesanan yang tertunda (Pending) masih valid.
 * Fungsi: Dipanggil saat user ingin melanjutkan pembayaran untuk memastikan order belum expired/dihapus.
 */

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
    
    // Order dianggap valid untuk dilanjutkan pembayarannya HANYA JIKA
    // statusnya masih 'Menunggu Pembayaran'.
    if ($row['status'] === 'Menunggu Pembayaran') {
        echo json_encode(['valid' => true]);
    } else {
        // Jika status sudah berubah (misal: sudah Lunas atau Dibatalkan oleh admin)
        echo json_encode(['valid' => false, 'reason' => 'Status changed']);
    }
} else {
    // Jika data tidak ditemukan (sudah terhapus otomatis karena expired)
    echo json_encode(['valid' => false, 'reason' => 'Order deleted/expired']);
}

$stmt->close();
?>