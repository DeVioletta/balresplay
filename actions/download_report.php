<?php
/**
 * File: download_report.php
 * Deskripsi: Menghasilkan laporan penjualan dalam format Excel (.xlsx).
 * Dependensi: PhpSpreadsheet
 */

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

startSecureSession();
redirectIfNotLoggedIn('../admin_login.php');

// --------------------------------------------------------------------------
// 1. Validasi Akses
// --------------------------------------------------------------------------
// Hanya Kasir dan Super Admin yang bisa mengunduh laporan
if ($_SESSION['role'] !== 'Kasir' && $_SESSION['role'] !== 'Super Admin') {
    die("Akses ditolak.");
}

// --------------------------------------------------------------------------
// 2. Pengambilan Data
// --------------------------------------------------------------------------
// Ambil filter tanggal dari parameter URL
$start_date = $_GET['start_date'] ?? null;
$end_date = $_GET['end_date'] ?? null;

// Ambil data transaksi dari database
$order_details = getDashboardOrderDetails($db, $start_date, $end_date);

// Buat nama file yang dinamis berdasarkan filter tanggal
$filename = "Laporan_Penjualan_BalResplay";
if ($start_date) $filename .= "_dari_" . $start_date;
if ($end_date) $filename .= "_sampai_" . $end_date;
$filename .= ".xlsx";

// --------------------------------------------------------------------------
// 3. Pembuatan Spreadsheet
// --------------------------------------------------------------------------
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// -- Set Header Kolom --
$sheet->setCellValue('A1', 'Order ID');
$sheet->setCellValue('B1', 'Tanggal');
$sheet->setCellValue('C1', 'Meja');
$sheet->setCellValue('D1', 'Menu');
$sheet->setCellValue('E1', 'Varian');
$sheet->setCellValue('F1', 'Kuantitas');
$sheet->setCellValue('G1', 'Subtotal (Rp)');

// -- Styling Header --
$headerStyle = [
    'font' => ['bold' => true],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['argb' => 'FFDAAD39'], // Warna Emas
    ],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
];
$sheet->getStyle('A1:G1')->applyFromArray($headerStyle);

// -- Isi Data Baris per Baris --
$row_number = 2; 
if (!empty($order_details)) {
    foreach ($order_details as $detail) {
        $sheet->setCellValue('A' . $row_number, $detail['order_id']);
        $sheet->setCellValue('B' . $row_number, date('d-m-Y H:i', strtotime($detail['order_time'])));
        $sheet->setCellValue('C' . $row_number, $detail['table_number']);
        $sheet->setCellValue('D' . $row_number, $detail['product_name']);
        $sheet->setCellValue('E' . $row_number, $detail['variant_name'] ?? ''); 
        $sheet->setCellValue('F' . $row_number, $detail['quantity']);
        $sheet->setCellValue('G' . $row_number, $detail['sub_total']);
        
        $row_number++;
    }
}

// -- Formatting Akhir --
// Auto-size lebar kolom
foreach (range('A', 'G') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Format angka (ribuan) untuk kolom Subtotal
$sheet->getStyle('G2:G' . $row_number)->getNumberFormat()
      ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

// --------------------------------------------------------------------------
// 4. Output File ke Browser
// --------------------------------------------------------------------------
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . urlencode($filename) . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

$db->close();
exit();
?>