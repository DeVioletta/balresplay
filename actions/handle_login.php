<?php
require_once __DIR__ . '/../config/database.php';
startSecureSession();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        header("Location: ../admin_login.php?error=1"); // Gagal: Input kosong
        exit();
    }

    $user = getAdminUserByUsername($db, $username); //

    if ($user) {
        if (password_verify($password, $user['password_hash'])) {
            
            // (PERBAIKAN POIN 1) Cek jika akun nonaktif
            if ($user['status'] == 0) {
                // Kirim error baru (error=2 berarti akun nonaktif)
                header("Location: ../admin_login.php?error=2"); 
                exit();
            }

            // (PERBAIKAN POIN 1) Jika status 1 (aktif), baru set sesi
            session_regenerate_id(true); 

            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // (PERBAIKAN POIN 2) Logika Pengalihan (Redirect)
            if ($user['role'] == 'Dapur') {
                header("Location: ../admin_menu.php"); // Dapur ke Menu Cafe
            } else {
                header("Location: ../admin_dashboard.php"); // Kasir & Super Admin ke Dashboard
            }
            exit();
            
        } else {
            header("Location: ../admin_login.php?error=1"); // Gagal: Password salah
            exit();
        }
    } else {
        header("Location: ../admin_login.php?error=1"); // Gagal: Username tidak ditemukan
        exit();
    }
} else {
    header("Location: ../admin_login.php");
    exit();
}
?>