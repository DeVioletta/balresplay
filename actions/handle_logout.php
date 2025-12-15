<?php
/**
 * File: handle_logout.php
 * Deskripsi: Menghapus sesi pengguna dan mengarahkan kembali ke halaman login.
 */

require_once __DIR__ . '/../config/database.php';
startSecureSession();

// Kosongkan array sesi
$_SESSION = array();

// Hancurkan sesi di server
session_destroy();

// Redirect ke halaman login
header("Location: ../admin_login.php");
exit();
?>