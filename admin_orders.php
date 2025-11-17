<?php
require_once __DIR__ . '/config/database.php';
startSecureSession();
redirectIfNotLoggedIn('admin_login.php');
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Manajemen Pesanan</title>
    <!-- CSS Utama -->
    <link rel="stylesheet" href="css/variable.css">
    <link rel="stylesheet" href="css/admin_menu.css"> <!-- Menggunakan base admin -->
    <link rel="stylesheet" href="css/admin_orders.css"> <!-- (FILE CSS BARU) -->
    <!-- CSS untuk item list (dari pembayaran) -->
    <link rel="stylesheet" href="css/pembayaran.css"> 
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
                <a href="admin_menu.php"><i class="fas fa-utensils"></i> Menu Cafe</a>
                <a href="admin_orders.php" class="active"><i class="fas fa-receipt"></i> Pesanan</a>
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
                <h1>Manajemen Pesanan</h1>
            </header>

            <!-- Toolbar Pesanan -->
            <div class="order-toolbar">
                <button id="toggle-view-btn" class="btn btn-secondary">
                    <i class="fas fa-history"></i> Lihat Riwayat
                </button>
            </div>

            <!-- Grid Pesanan Aktif -->
            <div class="order-grid" id="active-orders-grid">
                
                <!-- CONTOH KARTU PESANAN 1 (Menunggu) -->
                <div class="order-card" data-status="Menunggu Pembayaran">
                    <div class="order-card-header">
                        <div>
                            <h4>Order #1021</h4>
                            <span>Meja: <strong>12</strong></span>
                        </div>
                        <span class="order-time">10 menit lalu</span>
                    </div>
                    
                    <div class="order-card-body">
                        <div class="summary-items-list">
                            <!-- Item pesanan -->
                            <div class="summary-product-item">
                                <div class="product-name">
                                    <span>2x Fried Rice Chicken Grill</span>
                                </div>
                                <span class="product-price">Rp 80.000</span>
                            </div>
                            <div class="summary-product-item">
                                <div class="product-name">
                                    <span>1x Es Kopi Aren</span>
                                    <small class="product-notes">Catatan: Sedikit gula</small>
                                </div>
                                <span class="product-price">Rp 30.000</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="order-card-footer">
                        <div class="summary-total">
                            <div class="summary-item grand-total" style="border-top: 1px solid var(--tertiary-color); margin-top: 0;">
                                <span>Total</span>
                                <strong>Rp 110.000</strong>
                            </div>
                        </div>
                        <div class="order-card-status">
                            <span class="status-badge">Menunggu Pembayaran</span>
                        </div>
                        <div class="order-card-actions">
                            <label for="status-1021">Update Status:</label>
                            <select id="status-1021" name="status" class="status-select" onchange="console.log('Update Order 1021 to: ' + this.value)">
                                <option value="Menunggu Pembayaran" selected>Menunggu Pembayaran</option>
                                <option value="Kirim ke Dapur">Kirim ke Dapur</option>
                                <option value="Sedang Dimasak">Sedang Dimasak</option>
                                <option value="Siap Diantar">Siap Diantar</option>
                                <option value="Selesai">Selesai</option>
                                <option value="Dibatalkan">Dibatalkan</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- CONTOH KARTU PESANAN 2 (Dimasak) -->
                <div class="order-card" data-status="Sedang Dimasak">
                    <div class="order-card-header">
                        <div>
                            <h4>Order #1020</h4>
                            <span>Meja: <strong>5</strong></span>
                        </div>
                        <span class="order-time">25 menit lalu</span>
                    </div>
                    
                    <div class="order-card-body">
                        <div class="summary-items-list">
                            <div class="summary-product-item">
                                <div class="product-name">
                                    <span>1x Spaghetti Bolognese</span>
                                </div>
                                <span class="product-price">Rp 35.000</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="order-card-footer">
                        <div class="summary-total">
                            <div class="summary-item grand-total" style="border-top: 1px solid var(--tertiary-color); margin-top: 0;">
                                <span>Total</span>
                                <strong>Rp 35.000</strong>
                            </div>
                        </div>
                        <div class="order-card-status">
                            <span class="status-badge">Sedang Dimasak</span>
                        </div>
                        <div class="order-card-actions">
                            <label for="status-1020">Update Status:</label>
                            <select id="status-1020" name="status" class="status-select" onchange="console.log('Update Order 1020 to: ' + this.value)">
                                <option value="Menunggu Pembayaran">Menunggu Pembayaran</option>
                                <option value="Kirim ke Dapur">Kirim ke Dapur</option>
                                <option value="Sedang Dimasak" selected>Sedang Dimasak</option>
                                <option value="Siap Diantar">Siap Diantar</option>
                                <option value="Selesai">Selesai</option>
                                <option value="Dibatalkan">Dibatalkan</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- ... Kartu pesanan aktif lainnya ... -->

            </div>

            <!-- Grid Riwayat Pesanan (Hidden by default) -->
            <div class="order-grid" id="history-orders-grid" style="display: none;">
                
                <!-- CONTOH KARTU RIWAYAT 1 (Selesai) -->
                <div class="order-card" data-status="Selesai">
                    <div class="order-card-header">
                        <div>
                            <h4>Order #980</h4>
                            <span>Meja: <strong>7</strong></span>
                        </div>
                        <span class="order-time">06 Nov 2025</span>
                    </div>
                    <div class="order-card-footer">
                        <div class="summary-total">
                            <div class="summary-item grand-total" style="border-top: none; margin-top: 0; padding-top: 0;">
                                <span>Total Dibayar</span>
                                <strong>Rp 75.000</strong>
                            </div>
                        </div>
                        <div class="order-card-status">
                            <span class="status-badge">Selesai</span>
                        </div>
                    </div>
                </div>

                <!-- CONTOH KARTU RIWAYAT 2 (Dibatalkan) -->
                <div class="order-card" data-status="Dibatalkan">
                    <div class="order-card-header">
                        <div>
                            <h4>Order #979</h4>
                            <span>Meja: <strong>3</strong></span>
                        </div>
                        <span class="order-time">06 Nov 2025</span>
                    </div>
                    <div class="order-card-footer">
                         <div class="summary-total">
                            <div class="summary-item grand-total" style="border-top: none; margin-top: 0; padding-top: 0;">
                                <span>Total</span>
                                <strong>Rp 45.000</strong>
                            </div>
                        </div>
                        <div class="order-card-status">
                            <span class="status-badge">Dibatalkan</span>
                        </div>
                    </div>
                </div>

                <!-- ... Kartu riwayat lainnya ... -->
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

            if (hamburger) {
                hamburger.addEventListener('click', () => {
                    sidebar.classList.add('show');
                });
            }
            if (overlay) {
                overlay.addEventListener('click', () => {
                    sidebar.classList.remove('show');
                });
            }

            // --- Logika Toggle Riwayat Pesanan ---
            const toggleBtn = document.getElementById('toggle-view-btn');
            const activeGrid = document.getElementById('active-orders-grid');
            const historyGrid = document.getElementById('history-orders-grid');
            let isShowingHistory = false;

            if (toggleBtn) {
                toggleBtn.addEventListener('click', () => {
                    isShowingHistory = !isShowingHistory;
                    
                    if (isShowingHistory) {
                        activeGrid.style.display = 'none';
                        historyGrid.style.display = 'grid'; // atau 'flex' jika Anda gunakan flex
                        toggleBtn.innerHTML = '<i class="fas fa-clipboard-list"></i> Lihat Pesanan Aktif';
                    } else {
                        activeGrid.style.display = 'grid'; // atau 'flex'
                        historyGrid.style.display = 'none';
                        toggleBtn.innerHTML = '<i class="fas fa-history"></i> Lihat Riwayat';
                    }
                });
            }
        });
    </script>
</body>
</html>