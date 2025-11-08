<?php
require_once __DIR__ . '/../config/database.php';
startSecureSession();
redirectIfNotLoggedIn('../admin_login.php');

// Hanya Super Admin yang boleh menghapus akun
if ($_SESSION['role'] !== 'Super Admin') {
    header("Location: ../admin_settings.php?error=unauthorized");
    exit();
}

if (isset($_GET['id'])) {
    $user_id_to_delete = (int)$_GET['id'];
    $current_user_id = (int)$_SESSION['user_id'];

    // Mencegah Super Admin menghapus akunnya sendiri
    if ($user_id_to_delete === $current_user_id) {
        header("Location: ../admin_settings.php?error=selfdelete");
        exit();
    }

    // Hapus pengguna
    if (deleteAdminUser($db, $user_id_to_delete)) {
        header("Location: ../admin_settings.php?success=deleted");
    } else {
        header("Location: ../admin_settings.php?error=failed");
    }
    exit();

} else {
    header("Location: ../admin_settings.php");
    exit();
}
?>