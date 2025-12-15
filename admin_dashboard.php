<?php
require_once __DIR__ . '/config/database.php';
startSecureSession();
redirectIfNotLoggedIn('admin_login.php');

// Role check
if ($_SESSION['role'] == 'Dapur') {
    header("Location: admin_menu.php");
    exit();
}

// Tampilkan pesan error jika ada
$error_message = '';
if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']); 
}

// Logika Statistik
$start_date = $_GET['start_date'] ?? null;
$end_date = $_GET['end_date'] ?? null;
$stats = getDashboardStats($db, $start_date, $end_date);
$top_menus = getTopMenus($db, $start_date, $end_date);
$order_details = getDashboardOrderDetails($db, $start_date, $end_date);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Dashboard</title>
    <link rel="stylesheet" href="css/variable.css">
    <link rel="stylesheet" href="css/admin_menu.css">
    <link rel="stylesheet" href="css/admin_settings.css">
    <link rel="stylesheet" href="css/admin_dashboard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css"/>
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
                <?php 
                $role = $_SESSION['role']; 
                $currentPage = basename($_SERVER['PHP_SELF']); 
                ?>
                
                <?php if ($role == 'Super Admin' || $role == 'Kasir'): ?>
                    <a href="admin_dashboard.php" class="<?php echo $currentPage == 'admin_dashboard.php' ? 'active' : ''; ?>">
                       <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                <?php endif; ?>
                
                <a href="admin_menu.php" class="<?php echo ($currentPage == 'admin_menu.php' || $currentPage == 'admin_form_menu.php') ? 'active' : ''; ?>">
                   <i class="fas fa-utensils"></i> Menu Cafe
                </a>
                
                <?php if ($role == 'Dapur'): ?>
                    <a href="kitchen_display.php" class="<?php echo $currentPage == 'kitchen_display.php' ? 'active' : ''; ?>">
                       <i class="fas fa-receipt"></i> Antrian Dapur
                    </a>
                <?php else: ?>
                    <a href="admin_orders.php" class="<?php echo $currentPage == 'admin_orders.php' ? 'active' : ''; ?>">
                       <i class="fas fa-receipt"></i> Pesanan
                    </a>
                <?php endif; ?>
                
                <?php if ($role == 'Super Admin'): ?>
                    <a href="admin_settings.php" class="<?php echo $currentPage == 'admin_settings.php' ? 'active' : ''; ?>">
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
                <h1>Dashboard</h1>
            </header>

            <?php if ($error_message): ?>
                <div class="admin-message error" style="background-color: var(--danger-color); color: var(--light-text); padding: 15px; border-radius: 5px; margin-bottom: 20px; font-weight: 500;">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <div class="stats-grid">
                <div class="stat-box">
                    <i class="fas fa-wallet stat-icon"></i>
                    <div class="stat-info">
                        <h3>Rp <?php echo number_format($stats['total_revenue'], 0, ',', '.'); ?></h3>
                        <p>Total Pendapatan (Selesai)</p>
                    </div>
                </div>
                <div class="stat-box">
                    <i class="fas fa-receipt stat-icon"></i>
                    <div class="stat-info">
                        <h3><?php echo $stats['total_orders']; ?></h3>
                        <p>Jumlah Order (Aktif & Selesai)</p>
                    </div>
                </div>
                <div class="stat-box top-menu">
                    <i class="fas fa-star stat-icon"></i>
                    <div class="stat-info">
                        <ol>
                            <?php if (empty($top_menus)): ?>
                                <li>Belum ada data</li>
                            <?php else: ?>
                                <?php foreach ($top_menus as $menu): ?>
                                    <li><?php echo htmlspecialchars($menu['name']); ?> (<?php echo $menu['total_sold']; ?>x)</li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ol>
                        <p>Top 3 Menu Terlaris</p>
                    </div>
                </div>
            </div>

            <form action="" method="GET" class="filter-toolbar">
                <div class="form-group">
                    <label for="start_date_picker">Start Date</label>
                    <input type="text" id="start_date_picker" name="start_date" class="form-control" readonly 
                           style="cursor: pointer; background-color: var(--darker-bg);" 
                           placeholder="Pilih tanggal mulai..."
                           value="<?php echo htmlspecialchars($start_date ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="end_date_picker">End Date</label>
                    <input type="text" id="end_date_picker" name="end_date" class="form-control" readonly 
                           style="cursor: pointer; background-color: var(--darker-bg);" 
                           placeholder="Pilih tanggal akhir..."
                           value="<?php echo htmlspecialchars($end_date ?? ''); ?>">
                </div>
                
                <button type="submit" class="btn btn-secondary">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </form>
            
            <div class="admin-toolbar">
                <button class="btn btn-primary" id="download-excel-btn">
                    <i class="fas fa-file-excel"></i> Unduh Excel
                </button>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No. Order</th>
                            <th>Tanggal</th>
                            <th>No. Meja</th>
                            <th>Menu</th>
                            <th>Harga</th>
                            <th>Kuantitas</th>
                        </tr>
                    </thead>
                    <tbody id="order-data-list">
                        <?php if (empty($order_details)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center; color: var(--text-muted);">Tidak ada data pesanan untuk ditampilkan.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($order_details as $detail): ?>
                                <tr>
                                    <td data-label="No. Order">#<?php echo $detail['order_id']; ?></td>
                                    <td data-label="Tanggal"><?php echo date('d M Y, H:i', strtotime($detail['order_time'])); ?></td>
                                    <td data-label="No. Meja"><?php echo $detail['table_number']; ?></td>
                                    <td data-label="Menu">
                                        <?php echo htmlspecialchars($detail['product_name']); ?>
                                        <?php if ($detail['variant_name']): ?>
                                            <span style="color: var(--text-muted); font-style: italic;">(<?php echo htmlspecialchars($detail['variant_name']); ?>)</span>
                                        <?php endif; ?>
                                    </td>
                                    <td data-label="Harga">Rp <?php echo number_format($detail['sub_total'], 0, ',', '.'); ?></td>
                                    <td data-label="Kuantitas"><?php echo $detail['quantity']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </main>
        
        <div class="sidebar-overlay" id="sidebar-overlay"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>
    <script src="js/admin_dashboard.js"></script>
</body>
</html>