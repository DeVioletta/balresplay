<?php
require_once __DIR__ . '/../config/database.php';
startSecureSession();
redirectIfNotLoggedIn('../admin_login.php');

// Hanya Super Admin yang boleh membuat akun
if ($_SESSION['role'] !== 'Super Admin') {
    header("Location: ../admin_settings.php?error=unauthorized");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validasi sederhana
    if (empty($username) || empty($password) || empty($role)) {
        header("Location: ../admin_settings.php?error=empty");
        exit();
    }

    // Cek jika username sudah ada
    if (getAdminUserByUsername($db, $username)) {
        header("Location: ../admin_settings.php?error=exists");
        exit();
    }

    // Hash password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Buat pengguna baru (status default adalah 0/inactive)
    if (createAdminUser($db, $username, $password_hash, $role)) {
        header("Location: ../admin_settings.php?success=created");
    } else {
        header("Location: ../admin_settings.php?error=failed");
    }
    exit();

} else {
    header("Location: ../admin_settings.php");
    exit();
}
?>