<?php
// 1. Sertakan file konfigurasi & mulai sesi
require_once __DIR__ . '/../config/database.php';
startSecureSession();

// 2. Cek jika form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 3. Validasi input sederhana
    if (empty($username) || empty($password)) {
        // Gagal: Input kosong
        // (PERBAIKAN) Hapus tanda / di awal path
        header("Location: ../admin_login.php?error=1");
        exit();
    }

    // 4. Ambil data pengguna dari database
    $user = getAdminUserByUsername($db, $username);

    // 5. Verifikasi Pengguna
    if ($user) {
        // Pengguna ditemukan, verifikasi password
        if (password_verify($password, $user['password_hash'])) {
            
            // Password benar, cek status akun
            if ($user['status'] == 1) {
                // BERHASIL LOGIN
                
                // Regenerasi ID Sesi (mencegah Session Fixation)
                session_regenerate_id(true); 

                // Simpan data ke sesi
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Arahkan ke dashboard
                // (PERBAIKAN) Hapus tanda / di awal path
                header("Location: ../admin_dashboard.php");
                exit();
                
            } else {
                // Gagal: Akun tidak aktif
                // (PERBAIKAN) Hapus tanda / di awal path
                header("Location: ../admin_login.php?error=1");
                exit();
            }
            
        } else {
            // Gagal: Password salah
            // (PERBAIKAN) Hapus tanda / di awal path
            header("Location: ../admin_login.php?error=1");
            exit();
        }
        
    } else {
        // Gagal: Username tidak ditemukan
        // (PERBAIKAN) Hapus tanda / di awal path
        header("Location: ../admin_login.php?error=1");
        exit();
    }

} else {
    // Jika diakses langsung, kembalikan ke login
    // (PERBAIKAN) Hapus tanda / di awal path
    header("Location: ../admin_login.php");
    exit();
}

?>