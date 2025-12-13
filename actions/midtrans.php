<?php
// FILE: balresplay/actions/midtrans.php

require_once __DIR__ . '/../vendor/autoload.php';

use Midtrans\Config;
use Midtrans\Snap;

// ============================================
// KONFIGURASI MIDTRANS
// ============================================
// GANTI DENGAN SERVER KEY ANDA (Pastikan Sandbox/Production sesuai)
Config::$serverKey = 'SERVER KEY'; 
Config::$isProduction = false;
Config::$isSanitized = true;
Config::$is3ds = true;


// // [FIX 2] Inisialisasi Header Kosong untuk mencegah Warning "Undefined array key 10023"
// Config::$curlOptions[CURLOPT_HTTPHEADER] = [];

/**
 * Fungsi helper untuk mendapatkan Snap Token
 */
function getSnapToken($order_id, $gross_amount) {
    $params = [
        'transaction_details' => [
            'order_id' => $order_id,
            'gross_amount' => (int)$gross_amount, 
        ],
        // Opsional: Tambah durasi expiry
        'custom_expiry' => [
            'start_time' => date("Y-m-d H:i:s O"),
            'unit' => 'minute',
            'duration' => 60
        ]
    ];

    try {
        return Snap::getSnapToken($params);
    } catch (Exception $e) {
        // [PENTING] Jangan return null. Lempar error agar terbaca di handle_order
        throw new Exception($e->getMessage());
    }
}
?>