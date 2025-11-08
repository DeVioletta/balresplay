<?php
require_once __DIR__ . '/config/database.php';
startSecureSession();
redirectIfNotLoggedIn('admin_login.php');

// --- (LOGIKA BARU) UNTUK MODE EDIT ---
$is_edit_mode = isset($_GET['id']);
$product_data = null;
$variants_data = [];
$image_preview = 'https://placehold.co/300x200/2c2c2c/a0a0a0?text=Preview+Gambar';
$page_title = "Tambah Menu Baru";

if ($is_edit_mode) {
    $product_id = (int)$_GET['id'];
    $product_data = getProductById($db, $product_id);
    
    if ($product_data) {
        $page_title = "Edit Menu: " . htmlspecialchars($product_data['name']);
        $variants_data = $product_data['variants'];
        if (!empty($product_data['image_url'])) {
            $image_preview = htmlspecialchars($product_data['image_url']);
        }
    } else {
        // Jika ID tidak ditemukan, kembalikan ke menu utama
        header("Location: admin_menu.php?error=notfound");
        exit();
    }
}
// --- (AKHIR LOGIKA BARU) ---

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | <?php echo $page_title; ?></title>
    <link rel="stylesheet" href="css/variable.css">
    <link rel="stylesheet" href="css/admin_menu.css">
    <link rel="stylesheet" href="css/menu_form.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600;700&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    
    <style>
        .admin-message {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .admin-message.error {
            background-color: var(--danger-color);
            color: var(--light-text);
        }
        
        /* (DIPERBARUI) CSS grid untuk baris varian */
        #variants-container .variant-row {
            grid-template-columns: 1fr 1fr 100px auto; /* Nama, Harga, Checkbox, Tombol Hapus */
            gap: 15px;
        }

        /* (BARU) Style untuk checkbox "Tersedia" */
        .variant-availability {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px;
            background-color: var(--dark-bg);
            border-radius: 5px;
        }
        .variant-availability label {
            margin: 0;
            color: var(--text-muted);
            font-size: 0.9rem;
            cursor: pointer;
        }
        .variant-availability input[type="checkbox"] {
            width: auto; /* Override default */
            cursor: pointer;
        }
        
        @media (max-width: 768px) {
            #variants-container .variant-row {
                grid-template-columns: 1fr; /* Stack di HP */
            }
            .variant-availability {
                justify-content: flex-start; /* Ratakan kiri di HP */
            }
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
                <a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="admin_menu.php" class="active"><i class="fas fa-utensils"></i> Menu Cafe</a>
                <a href="admin_orders.php"><i class="fas fa-receipt"></i> Pesanan</a>
                <a href="admin_settings.php"><i class="fas fa-cog"></i> Pengaturan</a>
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
                    
                    <!-- (DIPERBARUI) Input tersembunyi untuk mode edit -->
                    <?php if ($is_edit_mode): ?>
                        <input type="hidden" name="product_id" value="<?php echo $product_data['product_id']; ?>">
                        <!-- (DIPERBAIKI) Gunakan '??' untuk menangani 'null' -->
                        <input type="hidden" name="existing_image_url" value="<?php echo htmlspecialchars($product_data['image_url'] ?? ''); ?>">
                    <?php endif; ?>

                    <div class="form-section">
                        <h4>Informasi Dasar</h4>
                        <div class="form-group">
                            <label for="product_name">Nama Menu</label>
                            <!-- (DIPERBAIKI) Gunakan '??' untuk menangani 'null' -->
                            <input type="text" id="product_name" name="product_name" placeholder="cth: Americano" required 
                                   value="<?php echo htmlspecialchars($product_data['name'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="product_description">Deskripsi Singkat</label>
                            <!-- (DIPERBAIKI) Gunakan '??' untuk menangani 'null' -->
                            <textarea id="product_description" name="product_description" rows="3" 
                                      placeholder="cth: Shot espresso yang disajikan dengan tambahan air..."><?php echo htmlspecialchars($product_data['description'] ?? ''); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="product_category">Kategori (Pilih yang sudah ada)</label>
                            <!-- (DIPERBARUI) Logika 'selected' -->
                            <select id="product_category" name="product_category">
                                <option value="" <?php echo empty($product_data['category']) ? 'selected' : ''; ?>>Pilih Kategori</option>
                                <?php 
                                    // Daftar kategori statis (bisa diganti dinamis)
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
                            <input type="text" id="new_category" name="new_category" placeholder="cth: Pastry">
                        </div>
                        <div class="form-group">
                            <label for="product_image">Upload Gambar (Kosongkan jika tidak ingin mengubah)</label>
                            <input type="file" id="product_image" name="product_image" accept="image/*">
                            <div class="image-preview-container">
                                <!-- (DIPERBARUI) Pratinjau gambar dinamis -->
                                <img id="image_preview" src="<?php echo $image_preview; ?>" alt="Preview Gambar">
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h4>Harga & Varian</h4>
                        <p class="form-hint">
                            Tambahkan setidaknya satu varian. Atur ketersediaan (stok) menggunakan checkbox "Tersedia".
                        </p>
                        <div id="variants-container">
                            
                            <!-- (DIPERBARUI) Daftar varian sekarang dinamis -->
                            <?php if (empty($variants_data)): ?>
                                <!-- Tampilkan 1 baris kosong jika BUKAN mode edit / tidak ada varian -->
                                <div class="variant-row">
                                    <input type="text" name="variants[0][name]" placeholder="Nama Varian (cth: Hot / Ice)">
                                    <input type="number" name="variants[0][price]" placeholder="Harga (cth: 20000)" required>
                                    <div class="variant-availability">
                                        <input type="checkbox" id="available-0" name="variants[0][is_available]" value="1" checked>
                                        <label for="available-0">Tersedia</label>
                                    </div>
                                    <button type="button" class="btn btn-delete-variant" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            <?php else: ?>
                                <!-- Tampilkan varian yang ada dari database -->
                                <?php foreach ($variants_data as $index => $variant): ?>
                                    <div class="variant-row">
                                        <!-- (PENTING) ID Varian tersembunyi -->
                                        <input type="hidden" name="variants[<?php echo $index; ?>][id]" value="<?php echo $variant['variant_id']; ?>">
                                        
                                        <!-- (DIPERBAIKI) Ini adalah perbaikan untuk error line 208 -->
                                        <!-- Menggunakan '?? ""' untuk menangani 'null' -->
                                        <input type="text" name="variants[<?php echo $index; ?>][name]" placeholder="Nama Varian" 
                                               value="<?php echo htmlspecialchars($variant['variant_name'] ?? ''); ?>">
                                        
                                        <input type="number" name="variants[<?php echo $index; ?>][price]" placeholder="Harga" required 
                                               value="<?php echo htmlspecialchars($variant['price'] ?? ''); ?>">
                                        
                                        <div class="variant-availability">
                                            <input type="checkbox" id="available-<?php echo $index; ?>" name="variants[<?php echo $index; ?>][is_available]" value="1" 
                                                   <?php echo $variant['is_available'] == 1 ? 'checked' : ''; ?>>
                                            <label for="available-<?php echo $index; ?>">Tersedia</label>
                                        </div>
                                        <button type="button" class="btn btn-delete-variant">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            
                        </div>
                        <button type="button" id="add-variant-btn" class="btn btn-secondary">
                            <i class="fas fa-plus"></i> Tambah Varian
                        </button>
                    </div>

                    <div class="form-actions">
                        <a href="admin_menu.php" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Menu
                        </button>
                    </div>
                </form>
            </div>
        </main>
        
        <div class="sidebar-overlay" id="sidebar-overlay"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // --- Logika Sidebar Hamburger ---
            const hamburger = document.getElementById('hamburger');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            hamburger.addEventListener('click', () => {
                sidebar.classList.add('show');
            });

            overlay.addEventListener('click', () => {
                sidebar.classList.remove('show');
            });

            // --- Logika Form Varian Dinamis ---
            const variantsContainer = document.getElementById('variants-container');
            const addVariantBtn = document.getElementById('add-variant-btn');
            // (DIPERBARUI) Index dimulai dari jumlah varian yang sudah ada
            let variantIndex = <?php echo count($variants_data); ?>;

            addVariantBtn.addEventListener('click', () => {
                const newRow = document.createElement('div');
                newRow.classList.add('variant-row');
                
                // (DIPERBARUI) Baris baru sekarang menyertakan checkbox
                // ID Varian (hidden) tidak disertakan, ini menandakan ini adalah varian BARU
                newRow.innerHTML = `
                    <input type="text" name="variants[${variantIndex}][name]" placeholder="Nama Varian">
                    <input type="number" name="variants[${variantIndex}][price]" placeholder="Harga" required>
                    <div class="variant-availability">
                        <input type="checkbox" id="available-${variantIndex}" name="variants[${variantIndex}][is_available]" value="1" checked>
                        <label for="available-${variantIndex}">Tersedia</label>
                    </div>
                    <button type="button" class="btn btn-delete-variant">
                        <i class="fas fa-trash"></i>
                    </button>
                `;
                variantsContainer.appendChild(newRow);
                variantIndex++;
            });

            // Event delegation untuk tombol hapus varian
            variantsContainer.addEventListener('click', (e) => {
                if (e.target.closest('.btn-delete-variant')) {
                    // (DIPERBARUI) Cek jika ini satu-satunya baris
                    if (variantsContainer.children.length > 1) {
                        e.target.closest('.variant-row').remove();
                    } else {
                        // Jika ini baris terakhir, jangan hapus, tapi kosongkan field
                        const lastRow = variantsContainer.querySelector('.variant-row');
                        lastRow.querySelector('input[type="text"]').value = '';
                        lastRow.querySelector('input[type="number"]').value = '';
                        lastRow.querySelector('input[type="checkbox"]').checked = true;
                    }
                }
            });

            // --- Logika Image Preview ---
            const imageInput = document.getElementById('product_image');
            const imagePreview = document.getElementById('image_preview');
            // (BARU) Simpan URL gambar asli
            const originalImageSrc = imagePreview.src;

            imageInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        imagePreview.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    // Kembalikan ke placeholder/gambar asli jika batal pilih
                    imagePreview.src = originalImageSrc;
                }
            });

            // --- (VALIDASI FORM FRONTEND) ---
            const menuForm = document.getElementById('menu-form');
            const errorMessage = document.getElementById('form-error-message');

            menuForm.addEventListener('submit', (e) => {
                e.preventDefault(); // Selalu hentikan submit untuk validasi
                errorMessage.style.display = 'none';
                errorMessage.innerHTML = '';
                let errors = [];

                // 1. Cek semua input[required]
                const requiredFields = menuForm.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    if (field.value.trim() === '') {
                        let fieldName = field.placeholder || field.name;
                        const labelElement = menuForm.querySelector(`label[for="${field.id}"]`);
                        if (labelElement) {
                            fieldName = labelElement.textContent;
                        }
                        
                        if (fieldName.includes('Nama Menu')) {
                            errors.push('- Nama Menu wajib diisi.');
                        } else if (fieldName.includes('Harga')) {
                            errors.push('- Harga varian wajib diisi.');
                        } else if (!errors.includes(`- ${fieldName} wajib diisi.`)) {
                            errors.push(`- ${fieldName} wajib diisi.`);
                        }
                    }
                });
                
                errors = [...new Set(errors)];

                // 2. Cek validasi kategori
                const category = document.getElementById('product_category').value;
                const newCategory = document.getElementById('new_category').value.trim();
                if (category === '' && newCategory === '') {
                    errors.push('- Kategori wajib dipilih atau diisi.');
                }

                // 3. Tampilkan error atau submit
                if (errors.length > 0) {
                    errorMessage.innerHTML = '<strong>Validasi Gagal:</strong><br>' + errors.join('<br>');
                    errorMessage.style.display = 'block';
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    menuForm.submit();
                }
            });
        });
    </script>
</body>
</html>