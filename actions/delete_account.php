<?php
/**
 * File: delete_account.php
 * Deskripsi: Menangani penghapusan akun staf/admin dari database.
 */

require_once __DIR__ . '/../config/database.php';
startSecureSession();
redirectIfNotLoggedIn('../admin_login.php');

// --------------------------------------------------------------------------
// 1. Validasi Hak Akses
// --------------------------------------------------------------------------
// Hanya role 'Super Admin' yang diizinkan mengakses fitur ini.
if ($_SESSION['role'] !== 'Super Admin') {
    header("Location: ../admin_settings.php?error=unauthorized");
    exit();
}

// --------------------------------------------------------------------------
// 2. Proses Penghapusan
// --------------------------------------------------------------------------
if (isset($_GET['id'])) {
    $user_id_to_delete = (int)$_GET['id'];
    $current_user_id = (int)$_SESSION['user_id'];

    // Mencegah Super Admin menghapus akunnya sendiri yang sedang login
    if ($user_id_to_delete === $current_user_id) {
        header("Location: ../admin_settings.php?error=selfdelete");
        exit();
    }

    // Eksekusi penghapusan data dari database
    if (deleteAdminUser($db, $user_id_to_delete)) {
        header("Location: ../admin_settings.php?success=deleted");
    } else {
        header("Location: ../admin_settings.php?error=failed");
    }
    exit();

} else {
    // Redirect jika ID tidak ditemukan dalam parameter URL
    header("Location: ../admin_settings.php");
    exit();
}
?>