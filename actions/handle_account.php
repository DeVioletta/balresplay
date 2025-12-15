<?php
/**
 * File: handle_account.php
 * Deskripsi: Menangani logika Pembuatan (Create) dan Pengeditan (Edit) akun admin.
 */

require_once __DIR__ . '/../config/database.php';
startSecureSession();
redirectIfNotLoggedIn('../admin_login.php');

// Validasi Role: Hanya Super Admin
if ($_SESSION['role'] !== 'Super Admin') {
    header("Location: ../admin_dashboard.php?error=unauthorized");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Deteksi apakah ini form Edit atau Tambah Baru
    $is_edit_mode = isset($_POST['user_id']) && !empty($_POST['user_id']);
    
    // Ambil input form
    $username = $_POST['username'];
    $role = $_POST['role'];
    $password = $_POST['password']; 
    
    // Ambil status akun (1 = Aktif, 0 = Nonaktif)
    // Checkbox HTML: jika dicentang kirim value, jika tidak kirim null/kosong
    $status = isset($_POST['status']) ? 1 : 0;

    // Validasi input dasar
    if (empty($username) || empty($role)) {
        header("Location: ../admin_settings.php?error=empty");
        exit();
    }

    // ----------------------------------------------------------------------
    // LOGIKA MODE EDIT
    // ----------------------------------------------------------------------
    if ($is_edit_mode) {
        $user_id = (int)$_POST['user_id'];
        
        // Cek apakah password diisi (artinya ingin mengganti password)
        if (!empty($password)) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE admin_users SET username = ?, role = ?, status = ?, password_hash = ? WHERE user_id = ?");
            $stmt->bind_param("ssisi", $username, $role, $status, $password_hash, $user_id);
        } else {
            // Jika kosong, update data lain tanpa mengubah password
            $stmt = $db->prepare("UPDATE admin_users SET username = ?, role = ?, status = ? WHERE user_id = ?");
            $stmt->bind_param("ssii", $username, $role, $status, $user_id);
        }
        
        if ($stmt->execute()) {
            header("Location: ../admin_settings.php?success=updated");
        } else {
            header("Location: ../admin_settings.php?error=failed");
        }
        $stmt->close();

    } 
    // ----------------------------------------------------------------------
    // LOGIKA MODE CREATE (TAMBAH BARU)
    // ----------------------------------------------------------------------
    else {
        // Password wajib untuk akun baru
        if (empty($password)) {
            header("Location: ../admin_settings.php?error=empty");
            exit();
        }
        
        // Cek duplikasi username
        if (getAdminUserByUsername($db, $username)) {
            header("Location: ../admin_settings.php?error=exists");
            exit();
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Buat user baru (Status default mungkin berbeda tergantung fungsi createAdminUser)
        if (createAdminUser($db, $username, $password_hash, $role)) { 
            header("Location: ../admin_settings.php?success=created");
        } else {
            header("Location: ../admin_settings.php?error=failed");
        }
    }
    exit();

} else {
    // Redirect jika akses bukan POST
    header("Location: ../admin_settings.php");
    exit();
}
?>