<?php
require_once __DIR__ . '/../config/database.php';
startSecureSession();
redirectIfNotLoggedIn('../admin_login.php');

// Hanya Kasir dan Super Admin yang bisa download
if ($_SESSION['role'] !== 'Kasir' && $_SESSION['role'] !== 'Super Admin') {
    die("Akses ditolak.");
}

// Ambil filter tanggal dari URL
$start_date = $_GET['start_date'] ?? null;
$end_date = $_GET['end_date'] ?? null;

// Ambil data terperinci dari database menggunakan fungsi yang ada
$order_details = getDashboardOrderDetails($db, $start_date, $end_date); //

// Tentukan nama file
$filename = "Laporan_Penjualan_BalResplay";
if ($start_date) $filename .= "_dari_" . $start_date;
if ($end_date) $filename .= "_sampai_" . $end_date;
$filename .= ".csv";

// Set header PHP untuk "memaksa" download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Buka 'output stream' PHP
$output = fopen('php://output', 'w');

// Tulis baris Header CSV
fputcsv($output, [
    'Order ID',
    'Tanggal',
    'Meja',
    'Menu',
    'Varian',
    'Kuantitas',
    'Subtotal (Rp)'
]);

// Tulis data baris per baris
if (!empty($order_details)) {
    foreach ($order_details as $detail) {
        $row = [
            $detail['order_id'],
            date('d-m-Y H:i', strtotime($detail['order_time'])),
            $detail['table_number'],
            $detail['product_name'],
            $detail['variant_name'] ?? '', // Handle varian null
            $detail['quantity'],
            $detail['sub_total']
        ];
        fputcsv($output, $row);
    }
}

fclose($output);
exit();
?>