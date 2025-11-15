<?php
require_once __DIR__ . '/config/database.php';
startSecureSession();
redirectIfNotLoggedIn('admin_login.php');

// (PERBAIKAN POIN 2) Role check: Boleh diakses Kasir, Dapur, Super Admin
$role = $_SESSION['role'];
if ($role !== 'Kasir' && $role !== 'Super Admin' && $role !== 'Dapur') {
    $_SESSION['error_message'] = 'Anda tidak memiliki izin untuk melihat halaman Menu.';
    header("Location: admin_login.php"); // Tendang ke login
    exit();
}

$products = getAllProductsWithVariants($db);
$categories = getAllCategories($db);

$message = '';
// ... (logika $message tidak berubah)
if (isset($_GET['success'])) {
    if ($_GET['success'] == 'deleted') $message = '<div class="admin-message success">Menu berhasil dihapus.</div>';
    if ($_GET['success'] == 'updated') $message = '<div class="admin-message success">Menu berhasil diperbarui.</div>';
    if ($_GET['success'] == 'created') $message = '<div class="admin-message success">Menu baru berhasil dibuat.</div>';
}
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'deletefailed') $message = '<div class="admin-message error">Gagal menghapus menu.</div>';
    if ($_GET['error'] == 'notfound') $message = '<div class="admin-message error">Produk tidak ditemukan.</div>';
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
        .admin-message { padding: 15px; border-radius: 5px; margin-bottom: 20px; font-weight: 500; }
        .admin-message.success { background-color: var(--success-color); color: var(--light-text); }
        .admin-message.error { background-color: var(--danger-color); color: var(--light-text); }
        /* (BARU) Style untuk tombol nonaktif */
        .btn-edit:disabled, .btn-delete:disabled {
            background-color: var(--tertiary-color);
            cursor: not-allowed;
            opacity: 0.6;
        }
        .btn-delete:disabled:hover { background-color: var(--tertiary-color); }
        .btn-edit:disabled:hover { background-color: var(--tertiary-color); }
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
                             data-category="<?php echo htmlspecialchars($product['category']); ?>">
                            
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
                                    <?php echo ($role == 'Dapur' ? 'Edit Stok' : 'Edit'); // Ganti teks tombol untuk Dapur ?>
                                </a>
                                
                                <?php if ($role == 'Super Admin' || $role == 'Kasir'): ?>
                                <button class="btn btn-delete" 
                                        data-id="<?php echo $product['product_id']; ?>"
                                        data-name="<?php echo htmlspecialchars($product['name']); ?>">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                                <?php endif; ?>
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

            // Filter Kategori (Tidak Berubah)
            const categoryFilter = document.getElementById('category-filter');
            if (categoryFilter) {
                categoryFilter.addEventListener('change', (e) => {
                    const selectedCategory = e.currentTarget.value;
                    const allItems = document.querySelectorAll('.admin-menu-grid .menu-item');
                    allItems.forEach(item => {
                        const itemCategory = item.dataset.category;
                        if (selectedCategory === 'all' || itemCategory === selectedCategory) {
                            item.style.display = 'flex';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            }

            // Hapus Menu (Tidak Berubah)
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', (e) => {
                    const id = e.currentTarget.dataset.id;
                    const name = e.currentTarget.dataset.name;
                    const isConfirmed = confirm(`Apakah Anda yakin ingin menghapus menu "${name}"?`);
                    
                    if (isConfirmed) {
                        fetch(`actions/delete_menu.php?id=${id}`, { method: 'GET' })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                window.location.href = 'admin_menu.php?success=deleted';
                            } else {
                                window.location.href = 'admin_menu.php?error=' + (data.message || 'deletefailed');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat mencoba menghapus.');
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>