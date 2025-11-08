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
    <!-- CSS Utama -->
    <link rel="stylesheet" href="css/variable.css">
    <link rel="stylesheet" href="css/admin_menu.css">
    <link rel="stylesheet" href="css/menu_form.css"> <!-- (LINK BARU DITAMBAHKAN) -->
    <!-- Font & Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600;700&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
</head>
<body>
    
    <div class="admin-layout">
        <!-- ===== SIDEBAR ===== -->
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

        <!-- ===== MAIN CONTENT ===== -->
        <main class="main-content">
            <!-- Header Konten -->
            <header class="admin-header">
                <button class="hamburger" id="hamburger">
                    <i class="fas fa-bars"></i>
                </button>
                <h1><?php echo $page_title; ?></h1>
            </header>

            <!-- Formulir Menu -->
            <div class="form-container">
                <!-- 
                    Form akan mengirim data ke script PHP (misal: save_menu.php)
                    Gunakan 'enctype="multipart/form-data"' jika ingin upload gambar
                -->
                <form action="save_menu.php" method="POST" class="form-card" enctype="multipart/form-data">
                    <!-- ID Produk (tersembunyi) untuk mode Edit -->
                    <?php if ($is_edit_mode): ?>
                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                    <?php endif; ?>

                    <!-- Informasi Dasar Produk -->
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
                            <label for="product_image">Upload Gambar</label>
                            <input type="file" id="product_image" name="product_image" accept="image/*">
                            <div class="image-preview-container">
                                <img id="image_preview" src="https://placehold.co/300x200/2c2c2c/a0a0a0?text=Preview+Gambar" alt="Preview Gambar">
                            </div>
                        </div>
                    </div>

                    <!-- Varian dan Harga -->
                    <div class="form-section">
                        <h4>Harga & Varian</h4>
                        <p class="form-hint">
                            Tambahkan setidaknya satu varian. Untuk menu tanpa varian (seperti Nasi Goreng), biarkan "Nama Varian" kosong dan isi harganya.
                        </p>
                        <div id="variants-container">
                            <!-- 
                                Baris varian akan ditambahkan di sini oleh JS.
                                Backend (PHP) akan menerima ini sebagai array, cth:
                                name="variants[0][name]", name="variants[0][price]"
                                name="variants[1][name]", name="variants[1][price]"
                            -->
                            <div class="variant-row">
                                <input type="text" name="variants[0][name]" placeholder="Nama Varian (cth: Hot / Ice)">
                                <input type="number" name="variants[0][price]" placeholder="Harga (cth: 20000)" required>
                                <input type="text" name="variants[0][code]" placeholder="Kode Produk (cth: C1-HOT)" required>
                                <button type="button" class="btn btn-delete-variant" disabled>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" id="add-variant-btn" class="btn btn-secondary">
                            <i class="fas fa-plus"></i> Tambah Varian
                        </button>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="form-actions">
                        <a href="admin_menu.php" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Menu
                        </button>
                    </div>
                </form>
            </div>
        </main>
        
        <!-- (BARU) Overlay untuk menutup sidebar di mobile -->
        <div class="sidebar-overlay" id="sidebar-overlay"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // --- Logika Sidebar Hamburger ---
            const hamburger = document.getElementById('hamburger');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay'); // (BARU)

            hamburger.addEventListener('click', () => {
                sidebar.classList.add('show'); // (DIUBAH) Hanya menambah 'show'
            });

            // (BARU) Klik overlay untuk menutup sidebar
            overlay.addEventListener('click', () => {
                sidebar.classList.remove('show');
            });

            // --- Logika Form Varian Dinamis ---
            const variantsContainer = document.getElementById('variants-container');
            const addVariantBtn = document.getElementById('add-variant-btn');
            let variantIndex = 1; // Mulai dari 1 karena 0 sudah ada di HTML

            addVariantBtn.addEventListener('click', () => {
                const newRow = document.createElement('div');
                newRow.classList.add('variant-row');
                newRow.innerHTML = `
                    <input type="text" name="variants[${variantIndex}][name]" placeholder="Nama Varian">
                    <input type="number" name="variants[${variantIndex}][price]" placeholder="Harga" required>
                    <input type="text" name="variants[${variantIndex}][code]" placeholder="Kode Produk" required>
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
                    // Jangan hapus jika hanya ada satu baris
                    if (variantsContainer.children.length > 1) {
                        e.target.closest('.variant-row').remove();
                    }
                }
            });

            // --- Logika BARU untuk Image Preview ---
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
                    // Kembalikan ke placeholder jika tidak ada file
                    imagePreview.src = 'https://placehold.co/300x200/2c2c2c/a0a0a0?text=Preview+Gambar';
                }
            });
        });
    </script>
</body>
</html>