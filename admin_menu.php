<?php
require_once __DIR__ . '/config/database.php';
startSecureSession();
redirectIfNotLoggedIn('admin_login.php');

$role = $_SESSION['role'];
if ($role !== 'Kasir' && $role !== 'Super Admin' && $role !== 'Dapur') {
    $_SESSION['error_message'] = 'Anda tidak memiliki izin untuk melihat halaman Menu.';
    header("Location: admin_login.php");
    exit();
}

$products = getAllProductsWithVariants($db); //
$categories = getAllCategories($db); //

$message = '';
if (isset($_GET['success'])) {
    // (DIUBAH) Tambahkan id="auto-hide-message"
    if ($_GET['success'] == 'updated') $message = '<div id="auto-hide-message" class="admin-message success">Menu berhasil diperbarui.</div>';
    if ($_GET['success'] == 'created') $message = '<div id="auto-hide-message" class="admin-message success">Menu baru berhasil dibuat.</div>';
}
if (isset($_GET['error'])) {
    // (DIUBAH) Tambahkan id="auto-hide-message"
    if ($_GET['error'] == 'notfound') $message = '<div id="auto-hide-message" class="admin-message error">Produk tidak ditemukan.</div>';
}
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
    <style>
        .admin-message { 
            padding: 15px; border-radius: 5px; margin-bottom: 20px; font-weight: 500; 
            opacity: 1;
            transition: opacity 0.5s ease-out;
        }
        .admin-message.success { background-color: var(--success-color); color: var(--light-text); }
        .admin-message.error { background-color: var(--danger-color); color: var(--light-text); }
        

        /* (BARU) Styling untuk Search Bar */
        .search-bar-container {
            margin-bottom: 24px;
        }
        .search-input-wrapper {
            position: relative;
        }
        .search-input-wrapper .fa-search {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }
        #menu-search-input {
            /* Menggunakan style input global dari admin_menu.css */
            width: 100%;
            padding: 12px 15px 12px 45px; /* Padding kiri untuk ikon */
            background-color: var(--secondary-color);
        }
        
        /* (BARU) Style untuk no-result */
        .no-search-results {
            color: var(--text-muted);
            text-align: center;
            padding: 20px;
            grid-column: 1 / -1; /* Agar span di grid */
            display: none; /* Sembunyi by default */
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
                <h1>Manajemen Menu</h1>
            </header>

            <!-- (BARU) Search Bar -->
            <div class="search-bar-container">
                <div class="search-input-wrapper">
                    <i class="fas fa-search"></i>
                    <input type="text" id="menu-search-input" placeholder="Cari nama menu..." class="form-control">
                </div>
            </div>

            <?php echo $message; ?>

            <div class="menu-toolbar">
                <div class="filter-group">
                    <label for="category-filter">Filter Kategori:</label>
                    <select id="category-filter" name="kategori">
                        <option value="all">Semua Kategori</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat['category']); ?>">
                                <?php echo htmlspecialchars($cat['category']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php if ($role == 'Super Admin' || $role == 'Kasir'): ?>
                <a href="admin_form_menu.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Menu
                </a>
                <?php endif; ?>
            </div>

            <div class="admin-menu-grid">
                
                <?php if (empty($products)): ?>
                    <p>Belum ada menu yang ditambahkan.</p>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <?php
                            $image_url = !empty($product['image_url']) 
                                ? htmlspecialchars($product['image_url']) 
                                : 'https://placehold.co/300x300/e8e4d8/5c6e58?text=' . urlencode($product['name']);
                            
                            $all_variants_unavailable = true;
                            if (empty($product['variants'])) {
                                $all_variants_unavailable = true; 
                            } else {
                                foreach ($product['variants'] as $variant) {
                                    if ($variant['is_available'] == 1) {
                                        $all_variants_unavailable = false;
                                        break;
                                    }
                                }
                            }
                        ?>
                        <div class="menu-item" 
                             data-product-id="<?php echo $product['product_id']; ?>" 
                             data-category="<?php echo htmlspecialchars($product['category']); ?>"
                             data-name="<?php echo htmlspecialchars($product['name']); ?>">
                            
                            <div class="item-image">
                                <?php if ($all_variants_unavailable): ?>
                                    <div class="availability-badge unavailable">HABIS</div>
                                <?php endif; ?>
                                <img src="<?php echo $image_url; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            </div>
                            
                            <div class="item-info">
                                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p><?php echo htmlspecialchars($product['description']); ?></p>
                                <div class="item-meta-admin">
                                    <span class="item-category-badge"><?php echo htmlspecialchars($product['category']); ?></span>
                                </div>
                            </div>
                            
                            <div class="item-actions">
                                <a href="admin_form_menu.php?id=<?php echo $product['product_id']; ?>" class="btn btn-edit">
                                    <i class="fas fa-edit"></i> 
                                    <?php echo ($role == 'Dapur' ? 'Edit Stok' : 'Edit'); ?>
                                </a>
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
            const overlay = document.getElementById('sidebar-overlay');
            if (hamburger) hamburger.addEventListener('click', () => sidebar.classList.add('show'));
            if (overlay) overlay.addEventListener('click', () => sidebar.classList.remove('show'));

            // --- (BARU) Logika Filter dan Search ---
            const categoryFilter = document.getElementById('category-filter');
            const searchInput = document.getElementById('menu-search-input');
            const menuGrid = document.querySelector('.admin-menu-grid');
            const allItems = document.querySelectorAll('.admin-menu-grid .menu-item');

            // (BARU) Buat elemen pesan "Tidak ditemukan"
            let noResultsMessage = document.createElement('p');
            noResultsMessage.classList.add('no-search-results');
            noResultsMessage.textContent = 'Tidak ada menu yang cocok dengan pencarian Anda.';
            menuGrid.appendChild(noResultsMessage);

            // (BARU) Fungsi filter terpusat yang menggabungkan search dan kategori
            function filterMenuItems() {
                const selectedCategory = categoryFilter.value;
                const searchTerm = searchInput.value.toLowerCase().trim();
                let itemsFound = 0;

                allItems.forEach(item => {
                    const itemCategory = item.dataset.category;
                    const itemName = item.dataset.name.toLowerCase();

                    // Cek kedua kondisi
                    const categoryMatch = (selectedCategory === 'all' || itemCategory === selectedCategory);
                    const nameMatch = itemName.includes(searchTerm);

                    if (categoryMatch && nameMatch) {
                        item.style.display = 'flex';
                        itemsFound++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // (BARU) Tampilkan/sembunyikan pesan "tidak ditemukan"
                if (itemsFound === 0) {
                    noResultsMessage.style.display = 'block';
                } else {
                    noResultsMessage.style.display = 'none';
                }
            }

            // (DIUBAH) Ganti logika filter kategori lama dengan fungsi baru
            if (categoryFilter) {
                categoryFilter.addEventListener('change', filterMenuItems);
            }

            // (BARU) Tambahkan event listener untuk search input
            if (searchInput) {
                // 'input' event bereaksi langsung saat mengetik, paste, dll.
                searchInput.addEventListener('input', filterMenuItems);
            }
            // --- Akhir Logika Filter dan Search ---


            // (PERBAIKAN POIN 1) Hapus JavaScript untuk .btn-delete
            
            // (BARU) Logika untuk auto-hide message
            const messageElement = document.getElementById('auto-hide-message');
            if (messageElement) {
                setTimeout(() => {
                    messageElement.style.opacity = '0'; // Memicu transisi CSS
                    setTimeout(() => {
                        messageElement.remove(); // Hapus setelah fade-out
                    }, 500); // 0.5 detik (sesuai transisi CSS)
                }, 4000); // Tampilkan pesan selama 4 detik
            }
        });
    </script>
</body>
</html>