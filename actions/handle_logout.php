<?php
require_once __DIR__ . '/../config/database.php';
startSecureSession();

// Hapus semua variabel sesi
$_SESSION = array();

// Hancurkan sesi
session_destroy();

// Alihkan kembali ke halaman login
// (PERBAIKAN) Tambahkan ../ untuk path relatif yang benar
header("Location: ../admin_login.php");
exit();
?>