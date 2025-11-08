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
        header("Location: ../admin_login.php?error=1");
        exit();
    }

    // 4. Ambil data pengguna dari database
    $user = getAdminUserByUsername($db, $username);

    // 5. Verifikasi Pengguna
    if ($user) {
        // Pengguna ditemukan, verifikasi password
        if (password_verify($password, $user['password_hash'])) {
            
            // Password benar.
            
            // (PERUBAHAN LOGIKA)
            // Cek jika status 0 (inactive), update jadi 1 (active)
            // Ini memenuhi permintaan "akan berubah menjadi aktif ketika pengguna berhasil login"
            if ($user['status'] == 0) {
                updateUserStatus($db, $user['user_id'], 1);
            }
            
            // (PERBAIKAN) Indentasi diperbaiki dan blok 'else' yang salah dihapus
            
            // BERHASIL LOGIN
            // Regenerasi ID Sesi (mencegah Session Fixation)
            session_regenerate_id(true); 

            // Simpan data ke sesi
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Arahkan ke dashboard
            header("Location: ../admin_dashboard.php");
            exit();
            
            // (PERBAIKAN) Blok 'else' yang menyebabkan error telah dihapus dari sini
            
        } else {
            // Gagal: Password salah
            header("Location: ../admin_login.php?error=1");
            exit();
        }
        
    } else {
        // Gagal: Username tidak ditemukan
        header("Location: ../admin_login.php?error=1");
        exit();
    }

} else {
    // Jika diakses langsung, kembalikan ke login
    header("Location: ../admin_login.php");
    exit();
}

?>