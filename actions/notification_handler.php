<?php
// balresplay/actions/notification_handler.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/midtrans.php';

// Midtrans mengirim notifikasi via HTTP POST Raw Body
$json_result = file_get_contents('php://input');
$notification = json_decode($json_result, true);

// Pastikan ada data
if (!$notification) {
    exit('No notification received');
}

// Ambil data penting dari notifikasi Midtrans
$order_id = $notification['order_id'];
$transaction_status = $notification['transaction_status'];
$fraud_status = $notification['fraud_status'];

// Koneksi database sudah tersedia via require config/database.php

// Logika Update Status Berdasarkan Response Midtrans
$new_status = null;

if ($transaction_status == 'capture') {
    // Untuk kartu kredit
    if ($fraud_status == 'challenge') {
        $new_status = 'Menunggu Pembayaran'; // Atau status lain jika perlu review
    } else {
        $new_status = 'Kirim ke Dapur';
    }
} else if ($transaction_status == 'settlement') {
    // Untuk QRIS, GoPay, Transfer Bank (Sukses)
    $new_status = 'Kirim ke Dapur';
} else if ($transaction_status == 'pending') {
    $new_status = 'Menunggu Pembayaran';
} else if ($transaction_status == 'deny') {
    $new_status = 'Dibatalkan';
} else if ($transaction_status == 'expire') {
    $new_status = 'Dibatalkan';
} else if ($transaction_status == 'cancel') {
    $new_status = 'Dibatalkan';
}

// Update Database jika status berubah
if ($new_status) {
    // Gunakan Prepared Statement untuk keamanan
    $stmt = $db->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    
    if ($stmt->execute()) {
        // Respons OK ke Midtrans
        http_response_code(200); 
        echo "Order status updated to $new_status";
    } else {
        http_response_code(500);
        echo "Failed to update order status";
    }
    $stmt->close();
}
?>