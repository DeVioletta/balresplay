<?php
// 1. Muat konfigurasi dan mulai sesi
require_once __DIR__ . '/../config/database.php';
startSecureSession();
redirectIfNotLoggedIn('../admin_login.php');

// (BARU) Set header ke JSON karena kita menggunakan fetch()
header('Content-Type: application/json');

// 2. Cek jika ID ada
if (isset($_GET['id'])) {
    $product_id = (int)$_GET['id'];

    // 3. Panggil fungsi delete
    // Database Anda memiliki ON DELETE CASCADE, 
    // jadi menghapus produk akan otomatis menghapus variannya.
    if (deleteProduct($db, $product_id)) {
        // 4. Kirim respons sukses
        echo json_encode(['status' => 'success', 'message' => 'Produk berhasil dihapus.']);
    } else {
        // 5. Kirim respons gagal
        echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus produk dari database.']);
    }
} else {
    // 6. Kirim respons jika tidak ada ID
    echo json_encode(['status' => 'error', 'message' => 'ID produk tidak disediakan.']);
}
exit();
?>