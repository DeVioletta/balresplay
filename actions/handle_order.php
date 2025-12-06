<?php
// FILE: balresplay/actions/handle_order.php

// Matikan output error HTML agar tidak merusak JSON
ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/midtrans.php';

startSecureSession();
header('Content-Type: application/json');

try {
    // 1. Ambil data JSON
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);

    if (empty($data) || !isset($data['cartData'])) {
        throw new Exception("Data pesanan tidak lengkap.");
    }

    $cart_data = $data['cartData'];
    $table_number = (int)$data['tableNumber'];
    $payment_method = $data['paymentMethod']; 
    $service_fee = 2000;

    // 2. Mulai Transaksi Database
    $db->begin_transaction();

    // Hitung Total
    $total_price = $service_fee;
    $notes_arr = [];
    foreach ($cart_data as $item) {
        $total_price += ($item['price'] * $item['quantity']);
        if(!empty($item['notes'])) {
            $notes_arr[] = $item['name'] . ": " . $item['notes'];
        }
    }
    $notes_string = implode("; ", $notes_arr);

    // Insert Order Utama
    $stmt = $db->prepare("INSERT INTO orders (table_number, total_price, payment_method, notes, status, order_time) VALUES (?, ?, ?, ?, 'Menunggu Pembayaran', NOW())");
    $stmt->bind_param("idss", $table_number, $total_price, $payment_method, $notes_string);
    
    if (!$stmt->execute()) {
        throw new Exception("Database Error (Order): " . $stmt->error);
    }
    $order_id = $db->insert_id;
    $stmt->close();

    // Insert Item Pesanan
    $stmt_item = $db->prepare("INSERT INTO order_items (order_id, variant_id, quantity, price_per_item) VALUES (?, ?, ?, ?)");
    foreach ($cart_data as $item) {
        $stmt_item->bind_param("iiid", $order_id, $item['variant_id'], $item['quantity'], $item['price']);
        if (!$stmt_item->execute()) {
            throw new Exception("Database Error (Item): " . $stmt_item->error);
        }
    }
    $stmt_item->close();

    // 3. INTEGRASI MIDTRANS
    $snapToken = null;
    
    if ($payment_method === 'QRIS') {
        try {
            // Panggil fungsi getSnapToken yang sudah diperbaiki
            $snapToken = getSnapToken($order_id, $total_price);
        } catch (Exception $midtransError) {
            // Tangkap error asli Midtrans dan lempar ke catch utama
            throw new Exception("Midtrans Gagal: " . $midtransError->getMessage());
        }
    }

    // Commit Database jika semua lancar
    $db->commit();

    // Kirim JSON Sukses
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesanan berhasil dibuat.',
        'order_id' => $order_id,
        'snap_token' => $snapToken
    ]);

} catch (Exception $e) {
    // Rollback jika ada error apa pun
    if (isset($db)) { $db->rollback(); }
    
    // Kirim pesan error asli ke frontend
    echo json_encode([
        'status' => 'error', 
        'message' => $e->getMessage()
    ]);
}
?>