<?php
require_once __DIR__ . '/../config/database.php';
startSecureSession();
redirectIfNotLoggedIn('../admin_login.php');

// (PERBAIKAN POIN 3) Cek role
$role = $_SESSION['role'];
if ($role !== 'Kasir' && $role !== 'Super Admin' && $role !== 'Dapur') {
    header("Location: ../admin_menu.php?error=unauthorized");
    exit();
}
$is_dapur = ($role == 'Dapur');

$upload_dir = __DIR__ . '/../images/uploads/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

$is_edit_mode = isset($_POST['product_id']) && !empty($_POST['product_id']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $db->begin_transaction();

    try {
        $product_id = (int)($_POST['product_id'] ?? 0);
        
        // --- LOGIKA UNTUK KASIR / SUPER ADMIN (Bisa edit semua) ---
        if (!$is_dapur) {
            // 1. Handle Upload Gambar
            $db_image_path = $_POST['existing_image_url'] ?? null; 
            if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
                $file = $_FILES['product_image'];
                $safe_filename = preg_replace('/[^A-Za-z0-9._-]/', '', basename($file['name']));
                $file_name = uniqid() . '-' . $safe_filename;
                $target_path = $upload_dir . $file_name;
                if (move_uploaded_file($file['tmp_name'], $target_path)) {
                    $db_image_path = 'images/uploads/' . $file_name;
                } else {
                    throw new Exception("Gagal memindahkan file gambar.");
                }
            }

            // 2. Handle Data Produk Utama
            $product_name = $_POST['product_name'];
            $product_description = $_POST['product_description'];
            $category = !empty(trim($_POST['new_category'])) ? trim($_POST['new_category']) : $_POST['product_category'];
            if (empty($product_name) || empty($category)) {
                throw new Exception("Nama Menu dan Kategori wajib diisi.");
            }

            // 3. Simpan Produk (Create vs Update)
            if ($is_edit_mode) {
                if (!updateProduct($db, $product_id, $product_name, $product_description, $category, $db_image_path)) { //
                    throw new Exception("Gagal memperbarui produk utama.");
                }
            } else {
                $product_id = createProduct($db, $product_name, $product_description, $category, $db_image_path); //
                if (!$product_id) {
                    throw new Exception("Gagal menyimpan produk utama.");
                }
            }
        }
        
        // --- LOGIKA UNTUK SEMUA ROLE (Termasuk Dapur) ---
        // 4. Sinkronisasi Varian
        $variants_from_form = $_POST['variants'] ?? [];
        if (empty($variants_from_form)) {
             throw new Exception("Setidaknya satu varian harga harus ada.");
        }
        
        $existing_variant_ids_in_db = getVariantIdsForProduct($db, $product_id); //
        $variant_ids_from_form = [];

        foreach ($variants_from_form as $variant) {
            $is_available = isset($variant['is_available']) ? 1 : 0; 
            $variant_id = (int)($variant['id'] ?? 0);

            if ($is_dapur) {
                // --- (PERBAIKAN POIN 3) LOGIKA DAPUR (Hanya Update Stok) ---
                if ($variant_id > 0) {
                    if (!updateVariantAvailability($db, $variant_id, $is_available)) { //
                        throw new Exception("Gagal update stok varian ID: $variant_id");
                    }
                }
            } else {
                // --- LOGIKA KASIR / SA (Update Penuh) ---
                $variant_name = !empty($variant['name']) ? $variant['name'] : null;
                $price = $variant['price'];
                if (empty($price)) throw new Exception("Harga varian wajib diisi.");

                if ($variant_id > 0 && in_array($variant_id, $existing_variant_ids_in_db)) {
                    // UPDATE Varian
                    if (!updateProductVariant($db, $variant_id, $variant_name, $price, $is_available)) { //
                        throw new Exception("Gagal memperbarui varian ID: $variant_id");
                    }
                    $variant_ids_from_form[] = $variant_id;
                } else {
                    // CREATE Varian
                    if (!createProductVariant($db, $product_id, $variant_name, $price, $is_available)) { //
                        throw new Exception("Gagal membuat varian baru.");
                    }
                }
            }
        }
        
        // 5. Hapus Varian (Hanya Kasir/SA)
        if (!$is_dapur && $is_edit_mode) {
            $variants_to_delete = array_diff($existing_variant_ids_in_db, $variant_ids_from_form);
            foreach ($variants_to_delete as $variant_id) {
                if (!deleteVariant($db, $variant_id)) { //
                    throw new Exception("Gagal menghapus varian ID: $variant_id");
                }
            }
        }

        $db->commit();
        // (DIUBAH) Pesan sukses generik
        header("Location: ../admin_menu.php?success=updated");
        exit();

    } catch (Exception $e) {
        $db->rollback();
        $error_url = $is_edit_mode ? "../admin_form_menu.php?id=" . $product_id : "../admin_form_menu.php";
        header("Location: $error_url&error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: ../admin_menu.php");
    exit();
}
?>