<?php
/**
 * File: handle_login.php
 * Deskripsi: Memproses otentikasi login admin/staf.
 */

require_once __DIR__ . '/../config/database.php';
startSecureSession();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validasi input kosong
    if (empty($username) || empty($password)) {
        header("Location: ../admin_login.php?error=1"); 
        exit();
    }

    // Ambil data user dari database
    $user = getAdminUserByUsername($db, $username);

    if ($user) {
        // Verifikasi password hash
        if (password_verify($password, $user['password_hash'])) {
            
            // Cek status akun (0 = Nonaktif)
            if ($user['status'] == 0) {
                header("Location: ../admin_login.php?error=2"); 
                exit();
            }

            // Regenerasi ID sesi untuk mencegah Session Fixation Attack
            session_regenerate_id(true); 

            // Set variabel sesi
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect berdasarkan Role
            if ($user['role'] == 'Dapur') {
                header("Location: ../admin_menu.php"); 
            } else {
                // Kasir & Super Admin
                header("Location: ../admin_dashboard.php"); 
            }
            exit();
            
        } else {
            // Password salah
            header("Location: ../admin_login.php?error=1"); 
            exit();
        }
    } else {
        // Username tidak ditemukan
        header("Location: ../admin_login.php?error=1"); 
        exit();
    }
} else {
    header("Location: ../admin_login.php");
    exit();
}
?>