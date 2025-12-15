<?php
/**
 * File: midtrans.php
 * Deskripsi: Konfigurasi library Midtrans dan fungsi helper.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Midtrans\Config;
use Midtrans\Snap;

// ============================================
// KONFIGURASI MIDTRANS
// ============================================
// GANTI 'SERVER KEY' DENGAN KUNCI SERVER ASLI DARI DASHBOARD MIDTRANS
Config::$serverKey = 'SERVER KEY'; 
Config::$isProduction = false; // Ubah ke true jika sudah live
Config::$isSanitized = true;
Config::$is3ds = true;

/**
 * Fungsi helper untuk meminta Snap Token dari API Midtrans.
 * * @param int $order_id ID Pesanan dari database.
 * @param int $gross_amount Total harga yang harus dibayar.
 * @return string Token Snap untuk membuka popup pembayaran.
 * @throws Exception Jika request ke Midtrans gagal.
 */
function getSnapToken($order_id, $gross_amount) {
    $params = [
        'transaction_details' => [
            'order_id' => $order_id,
            'gross_amount' => (int)$gross_amount, 
        ],
        // Pengaturan kedaluwarsa token/pembayaran (opsional)
        'custom_expiry' => [
            'start_time' => date("Y-m-d H:i:s O"),
            'unit' => 'minute',
            'duration' => 60
        ]
    ];

    try {
        return Snap::getSnapToken($params);
    } catch (Exception $e) {
        throw new Exception($e->getMessage());
    }
}
?>