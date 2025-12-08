<?php
require_once __DIR__ . '/config/database.php';
startSecureSession();
redirectIfNotLoggedIn('admin_login.php');

$role = $_SESSION['role'];
if ($role !== 'Kasir' && $role !== 'Super Admin' && $role !== 'Dapur') {
    $_SESSION['error_message'] = 'Anda tidak memiliki izin untuk mengelola menu.';
    header("Location: " . ($role == 'Kasir' ? 'admin_dashboard.php' : 'admin_login.php'));
    exit();
}

$is_dapur_readonly = ($_SESSION['role'] == 'Dapur');

$is_edit_mode = isset($_GET['id']);
$product_data = null;
$variants_data = [];
$image_preview = 'https://placehold.co/300x200/2c2c2c/a0a0a0?text=Preview+Gambar';
$page_title = "Tambah Menu Baru";

if ($is_edit_mode) {
    $product_id = (int)$_GET['id'];
    $product_data = getProductById($db, $product_id); //
    
    if ($product_data) {
        $page_title = "Edit Menu: " . htmlspecialchars($product_data['name']);
        $variants_data = $product_data['variants'];
        if (!empty($product_data['image_url'])) {
            $image_preview = htmlspecialchars($product_data['image_url']);
        }
    } else {
        header("Location: admin_menu.php?error=notfound");
        exit();
    }
}

// [PERBAIKAN LOGIKA] Tentukan tipe produk untuk keperluan UI
// Jika varian kosong ATAU hanya ada 1 varian dan namanya kosong/null -> Simple (Satuan)
// Selain itu -> Variable (Varian)
$product_type = 'simple'; // Default
if (!empty($variants_data)) {
    if (count($variants_data) > 1) {
        $product_type = 'variable';
    } elseif (count($variants_data) == 1 && !empty($variants_data[0]['variant_name'])) {
        // Cek variant_name dari database (sesuai field di getProductById -> variants[])
        $product_type = 'variable';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | <?php echo $page_title; ?></title>
    <link rel="stylesheet" href="css/variable.css">
    <link rel="stylesheet" href="css/admin_menu.css"> <link rel="stylesheet" href="css/menu_form.css"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600;700&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    
    <style>
        .admin-message { padding: 15px; border-radius: 5px; margin-bottom: 20px; font-weight: 500; }
        .admin-message.error { background-color: var(--danger-color); color: var(--light-text); }
        #variants-container .variant-row { grid-template-columns: 1fr 1fr 100px auto; gap: 15px; }
        .variant-availability {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            padding: 10px; background-color: var(--dark-bg); border-radius: 5px;
        }
        .variant-availability label { margin: 0; color: var(--text-muted); font-size: 0.9rem; cursor: pointer; }
        .variant-availability input[type="checkbox"] { width: auto; cursor: pointer; }
        
        input:disabled, textarea:disabled, select:disabled {
            background-color: var(--tertiary-color) !important;
            color: var(--text-muted) !important;
            cursor: not-allowed;
            opacity: 0.7;
        }
        
        /* [PERBAIKAN CSS] Helper class untuk mode Simple (Satuan) */
        /* Menyembunyikan input nama varian dan tombol hapus agar tampilan bersih */
        .mode-simple .variant-name-input { display: none; }
        .mode-simple .btn-delete-variant { visibility: hidden; } 
        .mode-simple .variant-row { grid-template-columns: 1fr 100px auto; } /* Ubah grid agar input harga melebar */
        
        @media (max-width: 768px) {
            #variants-container .variant-row { grid-template-columns: 1fr; }
            .mode-simple .variant-row { grid-template-columns: 1fr; } /* Tetap stack di mobile */
            .variant-availability { justify-content: flex-start; }
        }
    </style>
</head>
<body>
    
    <div class="admin-layout">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="index.php" class="nav-logo">
                    <img src="images/logo_fix.png" alt="BalResplay Logo" class="logo-img">
                    <span class="logo-text">BalResplay</span>
                </a>
            </div>
            
            <nav class="nav-list">
                <?php 
                $currentPage = basename($_SERVER['PHP_SELF']); 
                ?>
                
                <?php if ($role == 'Super Admin' || $role == 'Kasir'): ?>
                    <a href="admin_dashboard.php" 
                       class="<?php echo $currentPage == 'admin_dashboard.php' ? 'active' : ''; ?>">
                       <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                <?php endif; ?>
                
                <a href="admin_menu.php" 
                   class="<?php echo ($currentPage == 'admin_menu.php' || $currentPage == 'admin_form_menu.php') ? 'active' : ''; ?>">
                   <i class="fas fa-utensils"></i> Menu Cafe
                </a>
                
                <?php if ($role == 'Dapur'): ?>
                     <a href="kitchen_display.php" 
                       class="<?php echo $currentPage == 'kitchen_display.php' ? 'active' : ''; ?>">
                       <i class="fas fa-receipt"></i> Antrian Dapur
                    </a>
                <?php else: // Super Admin & Kasir ?>
                    <a href="admin_orders.php" 
                       class="<?php echo $currentPage == 'admin_orders.php' ? 'active' : ''; ?>">
                       <i class="fas fa-receipt"></i> Pesanan
                    </a>
                <?php endif; ?>
                
                <?php if ($role == 'Super Admin'): ?>
                    <a href="admin_settings.php" 
                       class="<?php echo $currentPage == 'admin_settings.php' ? 'active' : ''; ?>">
                       <i class="fas fa-cog"></i> Pengaturan
                    </a>
                <?php endif; ?>
            </nav>

            <div class="sidebar-footer">
                <a href="actions/handle_logout.php" class="logout-link">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </aside>

        <main class="main-content">
            <header class="admin-header">
                <button class="hamburger" id="hamburger">
                    <i class="fas fa-bars"></i>
                </button>
                <h1><?php echo $page_title; ?></h1>
            </header>

            <div class="form-container">
                
                <div id="form-error-message" class="admin-message error" style="display: none;"></div>

                <form action="actions/save_menu.php" method="POST" class="form-card" enctype="multipart/form-data" id="menu-form">
                    
                    <?php if ($is_edit_mode): ?>
                        <input type="hidden" name="product_id" value="<?php echo $product_data['product_id']; ?>">
                        <input type="hidden" name="existing_image_url" value="<?php echo htmlspecialchars($product_data['image_url'] ?? ''); ?>">
                    <?php endif; ?>

                    <div class="form-section">
                        <h4>Informasi Dasar <?php if ($is_dapur_readonly) echo '(Read-Only)'; ?></h4>
                        <div class="form-group">
                            <label for="product_name">Nama Menu</label>
                            <input type="text" id="product_name" name="product_name" placeholder="cth: Americano" required 
                                   value="<?php echo htmlspecialchars($product_data['name'] ?? ''); ?>" <?php if ($is_dapur_readonly) echo 'disabled'; ?>>
                        </div>
                        <div class="form-group">
                            <label for="product_description">Deskripsi Singkat</label>
                            <textarea id="product_description" name="product_description" rows="3" 
                                      placeholder="cth: Shot espresso yang disajikan dengan tambahan air..." <?php if ($is_dapur_readonly) echo 'disabled'; ?>><?php echo htmlspecialchars($product_data['description'] ?? ''); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="product_category">Kategori (Pilih yang sudah ada)</label>
                            <select id="product_category" name="product_category" <?php if ($is_dapur_readonly) echo 'disabled'; ?>>
                                <option value="" <?php echo empty($product_data['category']) ? 'selected' : ''; ?>>Pilih Kategori</option>
                                <?php 
                                    $all_cats = ['rice', 'noodles', 'lite-easy', 'coffee', 'tea', 'non-coffee', 'signature'];
                                    foreach ($all_cats as $cat) {
                                        $selected = (isset($product_data['category']) && $product_data['category'] == $cat) ? 'selected' : '';
                                        echo "<option value=\"$cat\" $selected>" . ucfirst($cat) . "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="new_category">Atau Kategori Baru (Kosongkan jika memilih di atas)</label>
                            <input type="text" id="new_category" name="new_category" placeholder="cth: Pastry" <?php if ($is_dapur_readonly) echo 'disabled'; ?>>
                        </div>
                        <div class="form-group">
                            <label for="product_image">Upload Gambar (Kosongkan jika tidak ingin mengubah)</label>
                            <input type="file" id="product_image" name="product_image" accept="image/*" <?php if ($is_dapur_readonly) echo 'disabled'; ?>>
                            <div class="image-preview-container">
                                <img id="image_preview" src="<?php echo $image_preview; ?>" alt="Preview Gambar">
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h4>Harga & Varian <?php if ($is_dapur_readonly) echo '(Hanya bisa ubah ketersediaan)'; ?></h4>
                        
                        <!-- [PERBAIKAN UI] Radio Button Tipe Menu -->
                        <div class="form-group" style="margin-bottom: 25px;">
                            <label style="margin-bottom: 10px; display: block;">Tipe Menu:</label>
                            <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                                <label style="font-weight: normal; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                                    <input type="radio" name="ui_product_type" value="simple" 
                                           <?php echo ($product_type == 'simple') ? 'checked' : ''; ?> 
                                           <?php if ($is_dapur_readonly) echo 'disabled'; ?>> 
                                    Satuan (Harga Tunggal)
                                </label>
                                <label style="font-weight: normal; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                                    <input type="radio" name="ui_product_type" value="variable" 
                                           <?php echo ($product_type == 'variable') ? 'checked' : ''; ?> 
                                           <?php if ($is_dapur_readonly) echo 'disabled'; ?>> 
                                    Memiliki Varian (cth: Hot/Ice, Ukuran)
                                </label>
                            </div>
                        </div>

                        <p class="form-hint">
                            Atur ketersediaan (stok) menggunakan checkbox "Tersedia".
                        </p>
                        
                        <!-- Tambahkan ID dan Class dinamis -->
                        <div id="variants-container" class="<?php echo ($product_type == 'simple') ? 'mode-simple' : ''; ?>">
                            
                            <?php if (empty($variants_data)): ?>
                                <!-- Baris Default (Input nama diberi class 'variant-name-input') -->
                                <div class="variant-row">
                                    <input type="text" name="variants[0][name]" class="variant-name-input" placeholder="Nama Varian (cth: Hot / Ice)" <?php if ($is_dapur_readonly) echo 'disabled'; ?>>
                                    <input type="number" name="variants[0][price]" placeholder="Harga (cth: 20000)" required <?php if ($is_dapur_readonly) echo 'disabled'; ?>>
                                    <div class="variant-availability">
                                        <input type="checkbox" id="available-0" name="variants[0][is_available]" value="1" checked>
                                        <label for="available-0">Tersedia</label>
                                    </div>
                                    <button type="button" class="btn btn-delete-variant" <?php if ($is_dapur_readonly) echo 'disabled'; ?>>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            <?php else: ?>
                                <?php foreach ($variants_data as $index => $variant): ?>
                                    <div class="variant-row">
                                        <input type="hidden" name="variants[<?php echo $index; ?>][id]" value="<?php echo $variant['variant_id']; ?>">
                                        <!-- Input nama diberi class 'variant-name-input' dan menggunakan key 'variant_name' dari DB -->
                                        <input type="text" name="variants[<?php echo $index; ?>][name]" class="variant-name-input" placeholder="Nama Varian" 
                                               value="<?php echo htmlspecialchars($variant['variant_name'] ?? ''); ?>" <?php if ($is_dapur_readonly) echo 'disabled'; ?>>
                                        <input type="number" name="variants[<?php echo $index; ?>][price]" placeholder="Harga" required 
                                               value="<?php echo htmlspecialchars($variant['price'] ?? ''); ?>" <?php if ($is_dapur_readonly) echo 'disabled'; ?>>
                                        
                                        <div class="variant-availability">
                                            <input type="checkbox" id="available-<?php echo $index; ?>" name="variants[<?php echo $index; ?>][is_available]" value="1" 
                                                   <?php echo $variant['is_available'] == 1 ? 'checked' : ''; ?>>
                                            <label for="available-<?php echo $index; ?>">Tersedia</label>
                                        </div>
                                        <button type="button" class="btn btn-delete-variant" <?php if ($is_dapur_readonly) echo 'disabled'; ?>>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            
                        </div>
                        
                        <!-- Tombol Tambah Varian (Akan disembunyikan via CSS jika mode-simple aktif) -->
                        <button type="button" id="add-variant-btn" class="btn btn-secondary" 
                                <?php if ($is_dapur_readonly) echo 'disabled'; ?>
                                style="<?php echo ($product_type == 'simple') ? 'display: none;' : ''; ?>">
                            <i class="fas fa-plus"></i> Tambah Varian
                        </button>
                    </div>

                    <div class="form-actions">
                        <?php if ($is_edit_mode && !$is_dapur_readonly): ?>
                            <button type="button" class="btn btn-delete" id="btn-delete-product-form" style="margin-right: auto; background-color: var(--danger-color); color: white;">
                                <i class="fas fa-trash"></i> Hapus Menu
                            </button>
                        <?php endif; ?>
                        
                        <a href="admin_menu.php" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </main>
        
        <div class="sidebar-overlay" id="sidebar-overlay"></div>
    </div>

    <script>
        const isDapur = <?php echo json_encode($is_dapur_readonly); ?>;

        document.addEventListener('DOMContentLoaded', () => {
            const hamburger = document.getElementById('hamburger');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (hamburger) hamburger.addEventListener('click', () => sidebar.classList.add('show'));
            if (overlay) overlay.addEventListener('click', () => sidebar.classList.remove('show'));

            const variantsContainer = document.getElementById('variants-container');
            const addVariantBtn = document.getElementById('add-variant-btn');
            
            // --- [PERBAIKAN LOGIKA JS] Switch Tipe Produk ---
            const typeRadios = document.querySelectorAll('input[name="ui_product_type"]');
            
            typeRadios.forEach(radio => {
                radio.addEventListener('change', (e) => {
                    if (isDapur) return;

                    if (e.target.value === 'simple') {
                        // Jika pindah ke Satuan
                        const rows = variantsContainer.querySelectorAll('.variant-row');
                        const confirmMsg = "Mengubah ke tipe 'Satuan' akan menghapus semua varian tambahan. Lanjutkan?";
                        
                        if (rows.length > 1) {
                            if (!confirm(confirmMsg)) {
                                // Batalkan jika user menolak
                                document.querySelector('input[value="variable"]').checked = true;
                                return;
                            }
                        }

                        // Hapus semua baris kecuali yang pertama
                        while (variantsContainer.children.length > 1) {
                            variantsContainer.lastChild.remove();
                        }

                        // Kosongkan nama varian baris pertama (agar dikirim NULL/kosong ke DB)
                        const firstRowName = variantsContainer.querySelector('input[name*="[name]"]');
                        if (firstRowName) firstRowName.value = '';

                        // Tambah class untuk sembunyikan UI varian
                        variantsContainer.classList.add('mode-simple');
                        addVariantBtn.style.display = 'none';

                    } else {
                        // Jika pindah ke Varian
                        variantsContainer.classList.remove('mode-simple');
                        addVariantBtn.style.display = 'inline-flex';
                    }
                });
            });

            // --- Logic Tambah Varian ---
            let variantIndex = <?php echo count($variants_data) > 0 ? count($variants_data) : 1; ?>;

            addVariantBtn.addEventListener('click', () => {
                // Cari index tertinggi agar aman dari duplikat index array
                let maxIndex = -1;
                variantsContainer.querySelectorAll('.variant-row').forEach(row => {
                   const inputName = row.querySelector('input[name^="variants"]');
                   if(inputName) {
                       const matches = inputName.name.match(/\[(\d+)\]/);
                       if(matches && matches[1]) {
                           const idx = parseInt(matches[1]);
                           if(idx > maxIndex) maxIndex = idx;
                       }
                   }
                });
                variantIndex = maxIndex + 1;

                const newRow = document.createElement('div');
                newRow.classList.add('variant-row');
                
                // Pastikan input name punya class "variant-name-input"
                newRow.innerHTML = `
                    <input type="text" name="variants[${variantIndex}][name]" class="variant-name-input" placeholder="Nama Varian (cth: Hot / Ice)" ${isDapur ? 'disabled' : ''}>
                    <input type="number" name="variants[${variantIndex}][price]" placeholder="Harga" required ${isDapur ? 'disabled' : ''}>
                    <div class="variant-availability">
                        <input type="checkbox" id="available-${variantIndex}" name="variants[${variantIndex}][is_available]" value="1" checked>
                        <label for="available-${variantIndex}">Tersedia</label>
                    </div>
                    <button type="button" class="btn btn-delete-variant" ${isDapur ? 'disabled' : ''}>
                        <i class="fas fa-trash"></i>
                    </button>
                `;
                variantsContainer.appendChild(newRow);
            });

            variantsContainer.addEventListener('click', (e) => {
                if (e.target.closest('.btn-delete-variant')) {
                    if (variantsContainer.children.length > 1) {
                        e.target.closest('.variant-row').remove();
                    } else {
                        // Jika baris terakhir, jangan hapus tapi reset
                        const lastRow = variantsContainer.querySelector('.variant-row');
                        const nameInput = lastRow.querySelector('input[type="text"]');
                        if(nameInput) nameInput.value = '';
                        
                        const priceInput = lastRow.querySelector('input[type="number"]');
                        if(priceInput) priceInput.value = '';
                        
                        lastRow.querySelector('input[type="checkbox"]').checked = true;
                        alert("Minimal harus ada satu harga.");
                    }
                }
            });

            const imageInput = document.getElementById('product_image');
            const imagePreview = document.getElementById('image_preview');
            const originalImageSrc = imagePreview.src;
            imageInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (event) => { imagePreview.src = event.target.result; };
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.src = originalImageSrc;
                }
            });

            // Validasi Form
            const menuForm = document.getElementById('menu-form');
            const errorMessage = document.getElementById('form-error-message');
            
            menuForm.addEventListener('submit', (e) => {
                if (isDapur) { return; }
                
                // Clear previous errors
                errorMessage.style.display = 'none';
                errorMessage.innerHTML = '';
                let errors = [];

                // Validasi field required
                const requiredFields = menuForm.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    // Abaikan field yang sedang disembunyikan/disabled (misal di mode simple)
                    if (field.disabled || field.offsetParent === null) return;

                    if (field.value.trim() === '') {
                        let fieldName = field.placeholder || field.name;
                        const labelElement = menuForm.querySelector(`label[for="${field.id}"]`);
                        if (labelElement) fieldName = labelElement.textContent;
                        
                        if (fieldName.includes('Nama Menu')) errors.push('- Nama Menu wajib diisi.');
                        else if (fieldName.includes('Harga')) errors.push('- Harga wajib diisi.');
                        else if (!errors.includes(`- ${fieldName} wajib diisi.`)) errors.push(`- ${fieldName} wajib diisi.`);
                    }
                });

                // Validasi Kategori
                const category = document.getElementById('product_category').value;
                const newCategory = document.getElementById('new_category').value.trim();
                if (category === '' && newCategory === '') {
                    errors.push('- Kategori wajib dipilih atau diisi.');
                }

                // Tampilkan Error jika ada
                if (errors.length > 0) {
                    e.preventDefault(); // Stop submit
                    errorMessage.innerHTML = '<strong>Validasi Gagal:</strong><br>' + errors.join('<br>');
                    errorMessage.style.display = 'block';
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    // Jika mode simple, pastikan input nama varian dikosongkan sebelum submit
                    // agar backend membacanya sebagai NULL
                    const isSimpleMode = document.querySelector('input[name="ui_product_type"][value="simple"]').checked;
                    if (isSimpleMode) {
                        const variantNameInputs = variantsContainer.querySelectorAll('.variant-name-input');
                        variantNameInputs.forEach(input => input.value = '');
                    }
                }
            });

            const btnDeleteForm = document.getElementById('btn-delete-product-form');
            if (btnDeleteForm) {
                btnDeleteForm.addEventListener('click', () => {
                    if (confirm('Apakah Anda yakin ingin menghapus menu ini?')) {
                        window.location.href = 'actions/delete_menu.php?id=<?php echo $product_id ?? ""; ?>'; 
                    }
                });
            }
        });
    </script>
</body>
</html>