<?php
// (DIUBAH) Sertakan autoloader Composer
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';

// (BARU) Gunakan kelas-kelas dari PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
$filename .= ".xlsx"; // (DIUBAH) Ekstensi file

// --- (DIUBAH) Logika Pembuatan File Excel ---

// 1. Buat objek Spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// 2. Tulis baris Header
$sheet->setCellValue('A1', 'Order ID');
$sheet->setCellValue('B1', 'Tanggal');
$sheet->setCellValue('C1', 'Meja');
$sheet->setCellValue('D1', 'Menu');
$sheet->setCellValue('E1', 'Varian');
$sheet->setCellValue('F1', 'Kuantitas');
$sheet->setCellValue('G1', 'Subtotal (Rp)');

// (BARU) Beri style pada header (opsional tapi disarankan)
$headerStyle = [
    'font' => ['bold' => true],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['argb' => 'FFDAAD39'], // Warna aksen emas
    ],
    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
];
$sheet->getStyle('A1:G1')->applyFromArray($headerStyle);


// 3. Tulis data baris per baris
if (!empty($order_details)) {
    $row_number = 2; // Mulai dari baris 2 (setelah header)
    foreach ($order_details as $detail) {
        $sheet->setCellValue('A' . $row_number, $detail['order_id']);
        $sheet->setCellValue('B' . $row_number, date('d-m-Y H:i', strtotime($detail['order_time'])));
        $sheet->setCellValue('C' . $row_number, $detail['table_number']);
        $sheet->setCellValue('D' . $row_number, $detail['product_name']);
        $sheet->setCellValue('E' . $row_number, $detail['variant_name'] ?? ''); // Handle varian null
        $sheet->setCellValue('F' . $row_number, $detail['quantity']);
        $sheet->setCellValue('G' . $row_number, $detail['sub_total']);
        
        $row_number++;
    }
}

// (BARU) Atur lebar kolom agar otomatis
foreach (range('A', 'G') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}
// (BARU) Atur format angka untuk kolom Subtotal
$sheet->getStyle('G2:G' . $row_number)->getNumberFormat()
      ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);


// 4. Set header PHP untuk "memaksa" download file .xlsx
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . urlencode($filename) . '"');
header('Cache-Control: max-age=0');

// 5. Buat 'writer' Xlsx dan simpan ke output PHP
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

// --- Akhir Logika Pembuatan File Excel ---

$db->close();
exit();
?>