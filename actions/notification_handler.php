<?php
/**
 * File: notification_handler.php
 * Deskripsi: Menangani Webhook/HTTP Notification dari Midtrans.
 * Fungsi: Mengupdate status pesanan di database secara otomatis berdasarkan status pembayaran.
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/midtrans.php';

// Ambil input JSON mentah yang dikirim oleh Midtrans
$json_result = file_get_contents('php://input');
$notification = json_decode($json_result, true);

if (!$notification) exit('No notification');

$order_id = $notification['order_id'];
$transaction_status = $notification['transaction_status'];
$fraud_status = $notification['fraud_status'];

// --------------------------------------------------------------------------
// Logika Penanganan Status Transaksi
// --------------------------------------------------------------------------

if ($transaction_status == 'capture') {
    // Untuk pembayaran kartu kredit
    if ($fraud_status == 'challenge') {
        // Transaksi dicurigai fraud -> Tahan status
        $stmt = $db->prepare("UPDATE orders SET status = 'Menunggu Pembayaran' WHERE order_id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
    } else if ($fraud_status == 'accept') {
        // Transaksi sukses
        $stmt = $db->prepare("UPDATE orders SET status = 'Kirim ke Dapur', confirmed_at = NOW() WHERE order_id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
    }
} else if ($transaction_status == 'settlement') {
    // Pembayaran lunas (QRIS/GoPay/Virtual Account) -> Update status ke Dapur
    $stmt = $db->prepare("UPDATE orders SET status = 'Kirim ke Dapur', confirmed_at = NOW() WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();

} else if ($transaction_status == 'cancel' || $transaction_status == 'deny' || $transaction_status == 'expire') {
    // Pembayaran gagal atau kadaluarsa -> Hapus pesanan dari database
    // Ini bertujuan menjaga kebersihan database dari order yang tidak jadi dibayar.
    // Item pesanan di `order_items` akan terhapus otomatis karena ON DELETE CASCADE.
    $stmt = $db->prepare("DELETE FROM orders WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    
    echo "Order deleted (Expired/Cancelled)";
    exit(); 
    
} else if ($transaction_status == 'pending') {
    // Menunggu pembayaran customer
    $stmt = $db->prepare("UPDATE orders SET status = 'Menunggu Pembayaran' WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
}

// Berikan respon 200 OK ke Midtrans
http_response_code(200);
?>