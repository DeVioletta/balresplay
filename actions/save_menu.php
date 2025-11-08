<?php
// (PATH DIPERBARUI) Naik satu level (../) untuk menemukan folder config
require_once __DIR__ . '/../config/database.php';
startSecureSession();
// (PATH DIPERBARUI) Naik satu level (../) untuk redirect
redirectIfNotLoggedIn('../admin_login.php');

// Tentukan folder upload
// (PATH DIPERBARUI) Naik satu level (../) untuk menemukan folder images
$upload_dir = __DIR__ . '/../images/uploads/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Cek jika ini adalah mode edit (belum diimplementasikan penuh, fokus ke create dulu)
$is_edit_mode = isset($_POST['product_id']) && !empty($_POST['product_id']);

if ($_SERVER["REQUEST_METHOD"] == "POST" && !$is_edit_mode) {
    
    // Mulai transaksi database
    $db->begin_transaction();

    try {
        // --- 1. Handle Upload Gambar (Opsional) ---
        $db_image_path = null;
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
            $file = $_FILES['product_image'];
            // Bersihkan nama file untuk keamanan
            $safe_filename = preg_replace('/[^A-Za-z0-9._-]/', '', basename($file['name']));
            $file_name = uniqid() . '-' . $safe_filename;
            $target_path = $upload_dir . $file_name;

            if (move_uploaded_file($file['tmp_name'], $target_path)) {
                // (PATH DIPERBAIKI) Ini adalah path yang disimpan di DB.
                $db_image_path = 'images/uploads/' . $file_name; 
            } else {
                // Gagal upload, tapi lanjutkan tanpa gambar
            }
        }

        // --- 2. Handle Kategori ---
        $product_name = $_POST['product_name'];
        $product_description = $_POST['product_description'];
        $category = !empty(trim($_POST['new_category'])) ? trim($_POST['new_category']) : $_POST['product_category'];

        // Validasi server-side sederhana
        if (empty($product_name) || empty($category)) {
            throw new Exception("Nama Menu dan Kategori wajib diisi.");
        }

        // --- 3. Simpan ke tabel 'products' ---
        $product_id = createProduct($db, $product_name, $product_description, $category, $db_image_path);

        if (!$product_id) {
            throw new Exception("Gagal menyimpan produk utama.");
        }

        // --- 4. Simpan ke tabel 'product_variants' ---
        // (DIPERBARUI) Logika 'product_code' dihapus
        $variants = $_POST['variants'];
        if (empty($variants)) {
             throw new Exception("Setidaknya satu varian harga harus ditambahkan.");
        }

        foreach ($variants as $variant) {
            $variant_name = !empty($variant['name']) ? $variant['name'] : null;
            $price = $variant['price'];
            // $code = $variant['code']; // Dihapus

            if (empty($price)) { // Validasi disederhanakan
                throw new Exception("Harga untuk varian wajib diisi.");
            }

            // Panggil fungsi yang diperbarui (tanpa $code)
            if (!createProductVariant($db, $product_id, $variant_name, $price)) {
                throw new Exception("Gagal menyimpan varian produk.");
            }
        }

        // Jika semua berhasil
        $db->commit();
        // (PATH DIPERBARUI) Naik satu level (../) untuk redirect
        header("Location: ../admin_menu.php?success=created");
        exit();

    } catch (Exception $e) {
        // Jika terjadi kesalahan, batalkan semua perubahan
        $db->rollback();
        // (Opsional) Catat error: error_log($e->getMessage());
        // (PATH DIPERBARUI) Naik satu level (../) untuk redirect
        header("Location: ../admin_form_menu.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Jika bukan POST atau ini mode Edit (belum ditangani)
    // (PATH DIPERBARUI) Naik satu level (../) untuk redirect
    header("Location: ../admin_form_menu.php");
    exit();
}
?>