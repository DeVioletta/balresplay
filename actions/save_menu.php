<?php
require_once __DIR__ . '/../config/database.php';
startSecureSession();
redirectIfNotLoggedIn('../admin_login.php');

// Tentukan folder upload
$upload_dir = __DIR__ . '/../images/uploads/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// (DIPERBARUI) Cek jika ini adalah mode edit
$is_edit_mode = isset($_POST['product_id']) && !empty($_POST['product_id']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Mulai transaksi database
    $db->begin_transaction();

    try {
        // --- 1. Handle Upload Gambar ---
        $db_image_path = $_POST['existing_image_url'] ?? null; // Ambil gambar yang ada

        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
            // Ada gambar baru diupload, ganti yang lama
            $file = $_FILES['product_image'];
            $safe_filename = preg_replace('/[^A-Za-z0-9._-]/', '', basename($file['name']));
            $file_name = uniqid() . '-' . $safe_filename;
            $target_path = $upload_dir . $file_name;

            if (move_uploaded_file($file['tmp_name'], $target_path)) {
                $db_image_path = 'images/uploads/' . $file_name; // Path baru
                
                // (Opsional) Hapus gambar lama di sini jika perlu
                // $old_image_path = __DIR__ . '/../' . $_POST['existing_image_url'];
                // if (file_exists($old_image_path) && !empty($_POST['existing_image_url'])) {
                //     unlink($old_image_path);
                // }

            } else {
                throw new Exception("Gagal memindahkan file gambar yang diupload.");
            }
        }

        // --- 2. Handle Data Produk Utama ---
        $product_name = $_POST['product_name'];
        $product_description = $_POST['product_description'];
        $category = !empty(trim($_POST['new_category'])) ? trim($_POST['new_category']) : $_POST['product_category'];

        if (empty($product_name) || empty($category)) {
            throw new Exception("Nama Menu dan Kategori wajib diisi.");
        }

        // --- 3. Simpan ke Database (Create vs Update) ---
        if ($is_edit_mode) {
            // --- LOGIKA UPDATE ---
            $product_id = (int)$_POST['product_id'];
            if (!updateProduct($db, $product_id, $product_name, $product_description, $category, $db_image_path)) {
                throw new Exception("Gagal memperbarui produk utama.");
            }
            
            // --- 4. Sinkronisasi Varian (UPDATE) ---
            $variants_from_form = $_POST['variants'] ?? [];
            $existing_variant_ids_in_db = getVariantIdsForProduct($db, $product_id); // Ambil ID dari DB
            $variant_ids_from_form = [];

            foreach ($variants_from_form as $variant) {
                $variant_name = !empty($variant['name']) ? $variant['name'] : null;
                $price = $variant['price'];
                // (BARU) Cek checkbox 'is_available'
                $is_available = isset($variant['is_available']) ? 1 : 0; 

                if (empty($price)) {
                    throw new Exception("Harga untuk varian wajib diisi.");
                }
                
                if (isset($variant['id']) && !empty($variant['id'])) {
                    // Ini adalah varian yang ADA (UPDATE)
                    $variant_id = (int)$variant['id'];
                    if (!updateProductVariant($db, $variant_id, $variant_name, $price, $is_available)) {
                        throw new Exception("Gagal memperbarui varian ID: $variant_id");
                    }
                    $variant_ids_from_form[] = $variant_id;
                } else {
                    // Ini adalah varian BARU (CREATE)
                    if (!createProductVariant($db, $product_id, $variant_name, $price, $is_available)) {
                        throw new Exception("Gagal membuat varian baru.");
                    }
                }
            }
            
            // --- 5. Hapus Varian (DELETE) ---
            // Bandingkan ID dari DB dengan ID yang baru saja di-submit dari form
            $variants_to_delete = array_diff($existing_variant_ids_in_db, $variant_ids_from_form);
            foreach ($variants_to_delete as $variant_id) {
                if (!deleteVariant($db, $variant_id)) {
                    throw new Exception("Gagal menghapus varian ID: $variant_id");
                }
            }

        } else {
            // --- LOGIKA CREATE ---
            $product_id = createProduct($db, $product_name, $product_description, $category, $db_image_path);
            if (!$product_id) {
                throw new Exception("Gagal menyimpan produk utama.");
            }
            
            // --- 4. Simpan Varian (CREATE) ---
            $variants = $_POST['variants'] ?? [];
            if (empty($variants)) {
                throw new Exception("Setidaknya satu varian harga harus ditambahkan.");
            }

            foreach ($variants as $variant) {
                $variant_name = !empty($variant['name']) ? $variant['name'] : null;
                $price = $variant['price'];
                $is_available = isset($variant['is_available']) ? 1 : 0; // Ambil status

                if (empty($price)) {
                    throw new Exception("Harga untuk varian wajib diisi.");
                }

                if (!createProductVariant($db, $product_id, $variant_name, $price, $is_available)) {
                    throw new Exception("Gagal menyimpan varian produk.");
                }
            }
        }

        // Jika semua berhasil
        $db->commit();
        $redirect_url = $is_edit_mode ? "../admin_menu.php?success=updated" : "../admin_menu.php?success=created";
        header("Location: $redirect_url");
        exit();

    } catch (Exception $e) {
        // Jika terjadi kesalahan, batalkan semua perubahan
        $db->rollback();
        $error_url = $is_edit_mode ? "../admin_form_menu.php?id=" . $_POST['product_id'] : "../admin_form_menu.php";
        header("Location: $error_url&error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Jika bukan POST
    header("Location: ../admin_menu.php");
    exit();
}
?>