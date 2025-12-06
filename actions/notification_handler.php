<?php
// FILE: balresplay/actions/notification_handler.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/midtrans.php';

// Ambil input mentah dari Midtrans
$json_result = file_get_contents('php://input');
$notification = json_decode($json_result, true);

if (!$notification) exit('No notification');

$order_id = $notification['order_id'];
$transaction_status = $notification['transaction_status'];
$fraud_status = $notification['fraud_status'];

// --- LOGIKA UTAMA ---

if ($transaction_status == 'capture') {
    if ($fraud_status == 'challenge') {
        // Kartu kredit dicurigai, biarkan Menunggu
        $stmt = $db->prepare("UPDATE orders SET status = 'Menunggu Pembayaran' WHERE order_id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
    } else if ($fraud_status == 'accept') {
        // Sukses
        $stmt = $db->prepare("UPDATE orders SET status = 'Kirim ke Dapur', confirmed_at = NOW() WHERE order_id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
    }
} else if ($transaction_status == 'settlement') {
    // Lunas (QRIS/GoPay/Transfer) -> SUKSES
    $stmt = $db->prepare("UPDATE orders SET status = 'Kirim ke Dapur', confirmed_at = NOW() WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();

} else if ($transaction_status == 'cancel' || $transaction_status == 'deny' || $transaction_status == 'expire') {
    // [MODIFIKASI] JIKA GAGAL/EXPIRED -> HAPUS DARI DATABASE
    // Agar tidak menuh-menuhin database dengan pesanan sampah
    
    // Karena di SQL Anda ada 'ON DELETE CASCADE', item pesanan juga akan terhapus otomatis.
    $stmt = $db->prepare("DELETE FROM orders WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    
    echo "Order deleted (Expired/Cancelled)";
    exit(); // Selesai
    
} else if ($transaction_status == 'pending') {
    // Masih menunggu bayar -> Biarkan status 'Menunggu Pembayaran'
    $stmt = $db->prepare("UPDATE orders SET status = 'Menunggu Pembayaran' WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
}

http_response_code(200);
?>