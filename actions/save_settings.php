<?php
/**
 * File: save_settings.php
 * Deskripsi: Menyimpan pengaturan global aplikasi (misal: jumlah meja).
 */

require_once __DIR__ . '/../config/database.php';
startSecureSession();
redirectIfNotLoggedIn('../admin_login.php');

// Validasi Role: Hanya Super Admin
if ($_SESSION['role'] !== 'Super Admin') {
    header("Location: ../admin_settings.php?error=unauthorized");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validasi input: Pastikan jumlah meja adalah angka positif
    if (isset($_POST['table_count']) && is_numeric($_POST['table_count']) && $_POST['table_count'] > 0) {
        
        $table_count = (int)$_POST['table_count'];
        
        // Query "INSERT ... ON DUPLICATE KEY UPDATE"
        // Jika setting 'table_count' belum ada, buat baru.
        // Jika sudah ada (duplicate key), update nilainya.
        $stmt = $db->prepare("
            INSERT INTO settings (setting_key, setting_value) 
            VALUES ('table_count', ?)
            ON DUPLICATE KEY UPDATE setting_value = ?
        ");
        
        $table_count_str = (string)$table_count;
        $stmt->bind_param("ss", $table_count_str, $table_count_str);
        
        if ($stmt->execute()) {
            header("Location: ../admin_settings.php?success=settings_updated");
        } else {
            header("Location: ../admin_settings.php?error=failed");
        }
        $stmt->close();
        
    } else {
        header("Location: ../admin_settings.php?error=invalid_number");
    }
    exit();

} else {
    header("Location: ../admin_settings.php");
    exit();
}
?>