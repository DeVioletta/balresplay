<?php
// FILE: balresplay/actions/delete_menu.php

require_once __DIR__ . '/../config/database.php';
startSecureSession();
redirectIfNotLoggedIn('../admin_login.php');

// Cek Role
if ($_SESSION['role'] == 'Dapur') {
    header("Location: ../admin_menu.php");
    exit();
}

$id = $_GET['id'] ?? null;
$type = $_GET['type'] ?? null; // 'product' atau 'variant'

if ($id && $type) {
    if ($type === 'product') {
        // Panggil fungsi Soft Delete Produk di database.php
        if (deleteProduct($db, $id)) {
            // Sukses
        } else {
            $_SESSION['error_message'] = "Gagal menghapus produk.";
        }
    } elseif ($type === 'variant') {
        // Panggil fungsi Soft Delete Varian di database.php
        if (deleteVariant($db, $id)) {
            // Sukses
        } else {
            $_SESSION['error_message'] = "Gagal menghapus varian.";
        }
    }
}

header("Location: ../admin_menu.php");
exit();
?>