<?php
// Pastikan tidak ada output spasi/newline sebelum tag PHP ini
require_once __DIR__ . '/../config/database.php';
startSecureSession();

// Set header JSON agar browser tahu ini bukan HTML
header('Content-Type: application/json');

// Cek Login
if (!isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Anda harus login.']);
    exit();
}

$role = $_SESSION['role'] ?? '';
if ($role !== 'Kasir' && $role !== 'Super Admin') {
    echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
    exit();
}

if (isset($_GET['id'])) {
    $product_id = (int)$_GET['id'];

    // Panggil fungsi Soft Delete
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