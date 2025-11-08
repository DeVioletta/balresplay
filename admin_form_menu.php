<?php
require_once __DIR__ . '/config/database.php';
startSecureSession();
redirectIfNotLoggedIn('admin_login.php');
?>

<?php
    // --- LOGIKA UNTUK BACKEND (CONTOH) ---
    // Cek apakah ada parameter 'id' di URL
    $is_edit_mode = isset($_GET['id']);
    $product_id = $is_edit_mode ? $_GET['id'] : null;

    $page_title = $is_edit_mode ? "Edit Menu (ID: $product_id)" : "Tambah Menu Baru";

    // Jika mode edit, Anda akan mengambil data dari database di sini
    // $product_data = get_product_from_db($product_id);
    // $variants_data = get_variants_from_db($product_id);
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
    
    <!-- (BARU) Style untuk pesan error, diambil dari admin_settings.css -->
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
        
        /* (BARU) Override CSS grid untuk baris varian karena 1 kolom dihapus */
        #variants-container .variant-row {
            /* Original: 1fr 1fr 1fr auto */
            grid-template-columns: 1fr 1fr auto; /* (DIPERBARUI) Menghapus 1fr untuk input kode */
        }
        /* (BARU) Perbaikan layout di mobile */
        @media (max-width: 768px) {
            #variants-container .variant-row {
                grid-template-columns: 1fr; /* Tetap 1 kolom */
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
                
                <!-- (BARU) Kontainer untuk pesan error -->
                <div id="form-error-message" class="admin-message error" style="display: none;"></div>

                <form action="actions/save_menu.php" method="POST" class="form-card" enctype="multipart/form-data" id="menu-form">
                    <?php if ($is_edit_mode): ?>
                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                    <?php endif; ?>

                    <div class="form-section">
                        <h4>Informasi Dasar</h4>
                        <div class="form-group">
                            <label for="product_name">Nama Menu</label>
                            <input type="text" id="product_name" name="product_name" placeholder="cth: Americano" required>
                        </div>
                        <div class="form-group">
                            <label for="product_description">Deskripsi Singkat</label>
                            <textarea id="product_description" name="product_description" rows="3" placeholder="cth: Shot espresso yang disajikan dengan tambahan air..."></textarea>
                        </div>
                        <div class="form-group">
                            <label for="product_category">Kategori (Pilih yang sudah ada)</label>
                            <select id="product_category" name="product_category">
                                <option value="" selected>Pilih Kategori</option>
                                <option value="rice">Rice</option>
                                <option value="noodles">Noodles</option>
                                <option value="lite-easy">Lite & Easy</option>
                                <option value="coffee">Coffee</option>
                                <option value="tea">Tea Series</option>
                                <option value="non-coffee">Non Coffee</option>
                                <option value="signature">Signature Mocktail</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="new_category">Atau Kategori Baru (Kosongkan jika memilih di atas)</label>
                            <input type="text" id="new_category" name="new_category" placeholder="cth: Pastry">
                        </div>
                        <div class="form-group">
                            <label for="product_image">Upload Gambar (Opsional)</label>
                            <input type="file" id="product_image" name="product_image" accept="image/*">
                            <div class="image-preview-container">
                                <img id="image_preview" src="https://placehold.co/300x200/2c2c2c/a0a0a0?text=Preview+Gambar" alt="Preview Gambar">

                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h4>Harga & Varian</h4>
                        <p class="form-hint">
                            Tambahkan setidaknya satu varian. Untuk menu tanpa varian (seperti Nasi Goreng), biarkan "Nama Varian" kosong dan isi harganya.
                        </p>
                        <div id="variants-container">
                            <!-- (DIPERBARUI) Input 'product_code' dihapus -->
                            <div class="variant-row">
                                <input type="text" name="variants[0][name]" placeholder="Nama Varian (cth: Hot / Ice)">
                                <input type="number" name="variants[0][price]" placeholder="Harga (cth: 20000)" required>
                                <button type="button" class="btn btn-delete-variant" disabled>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
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
            let variantIndex = 1;

            addVariantBtn.addEventListener('click', () => {
                const newRow = document.createElement('div');
                newRow.classList.add('variant-row');
                // (DIPERBARUI) Input 'product_code' dihapus
                newRow.innerHTML = `
                    <input type="text" name="variants[${variantIndex}][name]" placeholder="Nama Varian">
                    <input type="number" name="variants[${variantIndex}][price]" placeholder="Harga" required>
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
                    if (variantsContainer.children.length > 1) {
                        e.target.closest('.variant-row').remove();
                    }
                }
            });

            // --- Logika Image Preview ---
            const imageInput = document.getElementById('product_image');
            const imagePreview = document.getElementById('image_preview');

            imageInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        imagePreview.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.src = 'https://placehold.co/300x200/2c2c2c/a0a0a0?text=Preview+Gambar';
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
                        // Coba cari labelnya
                        const labelElement = menuForm.querySelector(`label[for="${field.id}"]`);
                        if (labelElement) {
                            fieldName = labelElement.textContent;
                        }
                        
                        // Buat pesan error lebih deskriptif
                        if (fieldName.includes('Nama Menu')) {
                            errors.push('- Nama Menu wajib diisi.');
                        } else if (fieldName.includes('Harga')) {
                            errors.push('- Harga varian wajib diisi.');
                        } else if (!errors.includes(`- ${fieldName} wajib diisi.`)) {
                            // Pesan umum jika tidak teridentifikasi
                            errors.push(`- ${fieldName} wajib diisi.`);
                        }
                    }
                });
                
                // Hapus duplikat pesan
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
                    // Scroll ke atas agar admin melihat pesan error
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    // Tidak ada error, submit form
                    menuForm.submit();
                }
            });
        });
    </script>
</body>
</html>