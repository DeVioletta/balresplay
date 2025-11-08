<?php
require_once __DIR__ . '/config/database.php';
startSecureSession();
redirectIfNotLoggedIn('admin_login.php');

// (BARU) Ambil semua data produk dari database
$products = getAllProductsWithVariants($db);
// (BARU) Ambil semua kategori unik untuk filter
$categories = getAllCategories($db);
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Manajemen Menu</title>
    <link rel="stylesheet" href="css/variable.css">
    <link rel="stylesheet" href="css/admin_menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600;700&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
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
                <h1>Manajemen Menu</h1>
            </header>

            <div class="menu-toolbar">
                <div class="filter-group">
                    <label for="category-filter">Filter Kategori:</label>
                    <!-- (DIPERBARUI) Select filter sekarang dinamis -->
                    <select id="category-filter" name="kategori">
                        <option value="all">Semua Kategori</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat['category']); ?>">
                                <?php echo htmlspecialchars($cat['category']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <a href="admin_form_menu.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Menu
                </a>
            </div>

            <div class="admin-menu-grid">
                
                <?php if (empty($products)): ?>
                    <p>Belum ada menu yang ditambahkan.</p>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <?php
                            // Logika placeholder
                            $image_url = !empty($product['image_url']) 
                                ? htmlspecialchars($product['image_url']) 
                                : 'https://placehold.co/300x300/e8e4d8/5c6e58?text=' . urlencode($product['name']);
                        ?>
                        <!-- (DIPERBARUI) Menambahkan data-category untuk JS -->
                        <div class="menu-item" 
                             data-product-id="<?php echo $product['product_id']; ?>" 
                             data-category="<?php echo htmlspecialchars($product['category']); ?>">
                            
                            <div class="item-image"><img src="<?php echo $image_url; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>"></div>
                            <div class="item-info">
                                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p><?php echo htmlspecialchars($product['description']); ?></p>
                                <div class="item-meta-admin">
                                    <span class="item-category-badge"><?php echo htmlspecialchars($product['category']); ?></span>
                                </div>
                            </div>
                            <div class="item-actions">
                                <a href="admin_form_menu.php?id=<?php echo $product['product_id']; ?>" class="btn btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button class="btn btn-delete" data-id="<?php echo $product['product_id']; ?>">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </main>
        
        <div class="sidebar-overlay" id="sidebar-overlay"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
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

            // (LOGIKA BARU) Logika untuk filter kategori
            const categoryFilter = document.getElementById('category-filter');
            if (categoryFilter) {
                categoryFilter.addEventListener('change', (e) => {
                    const selectedCategory = e.currentTarget.value;
                    const allItems = document.querySelectorAll('.admin-menu-grid .menu-item');

                    allItems.forEach(item => {
                        const itemCategory = item.dataset.category;
                        
                        // Cek jika item harus ditampilkan
                        if (selectedCategory === 'all' || itemCategory === selectedCategory) {
                            item.style.display = 'flex'; // Gunakan 'flex' karena .menu-item adalah flexbox
                        } else {
                            item.style.display = 'none'; // Sembunyikan item
                        }
                    });
                });
            }


            // Logika untuk tombol hapus (untuk integrasi backend)
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', (e) => {
                    const id = e.currentTarget.dataset.id;
                    // Ganti 'confirm' dengan modal kustom jika Anda tidak ingin menggunakan dialog browser
                    const isConfirmed = confirm(`Apakah Anda yakin ingin menghapus menu ini (ID: ${id})? (Fitur Hapus belum diimplementasikan)`);
                    
                    if (isConfirmed) {
                        // Di sinilah logika backend untuk menghapus akan dipanggil
                        // Contoh: fetch('delete_menu.php', { method: 'POST', body: JSON.stringify({ id: id }) })
                        console.log('Menghapus item dengan ID:', id);
                        
                        // Untuk demo, hapus elemen dari DOM (jika fitur delete sudah jadi)
                        // e.currentTarget.closest('.menu-item').remove();
                    }
                });
            });
        });
    </script>
</body>
</html>