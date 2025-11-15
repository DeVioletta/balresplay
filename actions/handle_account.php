<?php
require_once __DIR__ . '/../config/database.php';
startSecureSession();
redirectIfNotLoggedIn('../admin_login.php');

// Hanya Super Admin yang boleh
if ($_SESSION['role'] !== 'Super Admin') {
    header("Location: ../admin_dashboard.php?error=unauthorized");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // (BARU) Cek apakah ini mode EDIT atau mode TAMBAH
    $is_edit_mode = isset($_POST['user_id']) && !empty($_POST['user_id']);
    
    // Ambil data
    $username = $_POST['username'];
    $role = $_POST['role'];
    $password = $_POST['password']; // Bisa kosong saat edit
    
    // (BARU) Ambil status. 'status' akan ada jika 'is_edit_mode'
    // Jika tidak dicentang (null), nilainya 0. Jika dicentang, nilainya 1.
    $status = isset($_POST['status']) ? 1 : 0;

    // Validasi dasar
    if (empty($username) || empty($role)) {
        header("Location: ../admin_settings.php?error=empty");
        exit();
    }

    if ($is_edit_mode) {
        // --- LOGIKA UPDATE ---
        $user_id = (int)$_POST['user_id'];
        
        // Cek jika ganti password
        if (!empty($password)) {
            // Jika password baru diisi, hash dan update
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE admin_users SET username = ?, role = ?, status = ?, password_hash = ? WHERE user_id = ?");
            $stmt->bind_param("ssisi", $username, $role, $status, $password_hash, $user_id);
        } else {
            // Jika password dikosongkan, JANGAN update password
            $stmt = $db->prepare("UPDATE admin_users SET username = ?, role = ?, status = ? WHERE user_id = ?");
            $stmt->bind_param("ssii", $username, $role, $status, $user_id);
        }
        
        if ($stmt->execute()) {
            header("Location: ../admin_settings.php?success=updated");
        } else {
            header("Location: ../admin_settings.php?error=failed");
        }
        $stmt->close();

    } else {
        // --- LOGIKA CREATE (TAMBAH) ---
        // Password wajib diisi saat membuat akun baru
        if (empty($password)) {
            header("Location: ../admin_settings.php?error=empty");
            exit();
        }
        
        // Cek jika username sudah ada (hanya saat CREATE)
        if (getAdminUserByUsername($db, $username)) {
            header("Location: ../admin_settings.php?error=exists");
            exit();
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // (DIUBAH) Status default adalah 0 saat dibuat
        if (createAdminUser($db, $username, $password_hash, $role)) { 
            header("Location: ../admin_settings.php?success=created");
        } else {
            header("Location: ../admin_settings.php?error=failed");
        }
    }
    exit();

} else {
    header("Location: ../admin_settings.php");
    exit();
}
?>