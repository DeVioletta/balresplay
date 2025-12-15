<?php
/**
 * File: delete_menu.php
 * Deskripsi: Menangani penghapusan produk (Soft Delete) via request AJAX atau Link.
 */

require_once __DIR__ . '/../config/database.php';
startSecureSession();

// Set header respons menjadi JSON karena sering dipanggil via fetch/AJAX
header('Content-Type: application/json');

// --------------------------------------------------------------------------
// 1. Validasi Login & Role
// --------------------------------------------------------------------------
if (!isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Anda harus login.']);
    exit();
}

$role = $_SESSION['role'] ?? '';
// Hanya Kasir dan Super Admin yang boleh menghapus menu
if ($role !== 'Kasir' && $role !== 'Super Admin') {
    echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
    exit();
}

// --------------------------------------------------------------------------
// 2. Proses Soft Delete
// --------------------------------------------------------------------------
if (isset($_GET['id'])) {
    $product_id = (int)$_GET['id'];

    // Memanggil fungsi Soft Delete (mengubah flag is_deleted = 1)
    // agar data historis penjualan tetap aman.
    if (deleteProduct($db, $product_id)) {
        echo json_encode(['status' => 'success', 'message' => 'Produk berhasil dihapus (soft delete).']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus produk.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID produk tidak ditemukan.']);
}
exit();
?>