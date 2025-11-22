<?php
require_once __DIR__ . '/../config/database.php';
startSecureSession();
redirectIfNotLoggedIn('../admin_login.php');

// Hanya Super Admin yang boleh mengubah pengaturan
if ($_SESSION['role'] !== 'Super Admin') {
    header("Location: ../admin_settings.php?error=unauthorized");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validasi input
    if (isset($_POST['table_count']) && is_numeric($_POST['table_count']) && $_POST['table_count'] > 0) {
        
        $table_count = (int)$_POST['table_count'];
        
        // Gunakan query "INSERT ... ON DUPLICATE KEY UPDATE"
        // Ini akan membuat 'table_count' jika belum ada, atau memperbaruinya jika sudah ada.
        $stmt = $db->prepare("
            INSERT INTO settings (setting_key, setting_value) 
            VALUES ('table_count', ?)
            ON DUPLICATE KEY UPDATE setting_value = ?
        ");
        
        // Konversi $table_count ke string untuk bind_param
        $table_count_str = (string)$table_count;
        $stmt->bind_param("ss", $table_count_str, $table_count_str);
        
        if ($stmt->execute()) {
            header("Location: ../admin_settings.php?success=settings_updated");
        } else {
            header("Location: ../admin_settings.php?error=failed");
        }
        $stmt->close();
        
    } else {
        // Jika input tidak valid
        header("Location: ../admin_settings.php?error=invalid_number");
    }
    exit();

} else {
    // Jika bukan POST
    header("Location: ../admin_settings.php");
    exit();
}
?>