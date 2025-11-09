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
    <title>Admin | Dashboard</title>
    <!-- CSS Utama -->
    <link rel="stylesheet" href="css/variable.css">
    <link rel="stylesheet" href="css/admin_menu.css"> <!-- Base Admin -->
    <link rel="stylesheet" href="css/admin_settings.css"> <!-- Re-use tabel styles -->
    <link rel="stylesheet" href="css/admin_dashboard.css"> <!-- (FILE CSS BARU) -->
    
    <!-- (BARU) CSS untuk Kalender Litepicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css"/>
    
    <!-- Font & Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600;700&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
</head>
<body>
    
    <div class="admin-layout">
        <!-- ===== SIDEBAR ===== -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="admin_dashboard.php" class="nav-logo">
                    <img src="images/logo_fix.png" alt="BalResplay Logo" class="logo-img">
                    <span class="logo-text">BalResplay</span>
                </a>
            </div>
            <nav class="nav-list">
                <a href="admin_dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="admin_menu.php"><i class="fas fa-utensils"></i> Menu Cafe</a>
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
                <h1>Dashboard</h1>
            </header>

            <!-- Value Boxes -->
            <div class="stats-grid">
                <div class="stat-box">
                    <i class="fas fa-wallet stat-icon"></i>
                    <div class="stat-info">
                        <h3>Rp 1.572.000</h3>
                        <p>Total Pendapatan</p>
                    </div>
                </div>
                <div class="stat-box">
                    <i class="fas fa-receipt stat-icon"></i>
                    <div class="stat-info">
                        <h3>75</h3>
                        <p>Jumlah Order</p>
                    </div>
                </div>
                <div class="stat-box top-menu">
                    <i class="fas fa-star stat-icon"></i>
                    <div class="stat-info">
                        <ol>
                            <li>Es Kopi Aren</li>
                            <li>Fried Rice Chicken Grill</li>
                            <li>Crinkle Fries</li>
                        </ol>
                        <p>Top 3 Menu Terlaris</p>
                    </div>
                </div>
            </div>

            <!-- (PERUBAHAN) Filter Rentang Tanggal Dikembalikan ke 2 Input -->
            <form action="" method="GET" class="filter-toolbar">
                <div class="form-group">
                    <label for="start_date_picker">Start Date</label>
                    <input type="text" id="start_date_picker" name="start_date" class="form-control" readonly 
                           style="cursor: pointer; background-color: var(--darker-bg);" 
                           placeholder="Pilih tanggal mulai...">
                </div>
                <div class="form-group">
                    <label for="end_date_picker">End Date</label>
                    <input type="text" id="end_date_picker" name="end_date" class="form-control" readonly 
                           style="cursor: pointer; background-color: var(--darker-bg);" 
                           placeholder="Pilih tanggal akhir...">
                </div>
                
                <button type="submit" class="btn btn-secondary">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </form>
            <!-- (AKHIR PERUBAHAN) -->


            <!-- Tombol Unduh Excel -->
            <div class="admin-toolbar">
                <button class="btn btn-primary" id="download-excel-btn">
                    <i class="fas fa-file-excel"></i> Unduh Excel
                </button>
            </div>

            <!-- Tabel Data Pesanan -->
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
                        <!-- Contoh Data -->
                        <tr>
                            <td data-label="No. Order">#1021</td>
                            <td data-label="Tanggal">07 Nov 2025</td>
                            <td data-label="No. Meja">12</td>
                            <td data-label="Menu">Fried Rice Chicken Grill</td>
                            <td data-label="Harga">Rp 80.000</td>
                            <td data-label="Kuantitas">2</td>
                        </tr>
                        <tr>
                            <td data-label="No. Order">#1021</td>
                            <td data-label="Tanggal">07 Nov 2025</td>
                            <td data-label="No. Meja">12</td>
                            <td data-label="Menu">Es Kopi Aren</td>
                            <td data-label="Harga">Rp 30.000</td>
                            <td data-label="Kuantitas">1</td>
                        </tr>
                        <tr>
                            <td data-label="No. Order">#1020</td>
                            <td data-label="Tanggal">07 Nov 2025</td>
                            <td data-label="No. Meja">5</td>
                            <td data-label="Menu">Spaghetti Bolognese</td>
                            <td data-label="Harga">Rp 35.000</td>
                            <td data-label="Kuantitas">1</td>
                        </tr>
                        <!-- Data lain di-load di sini -->
                    </tbody>
                </table>
            </div>

        </main>
        
        <div class="sidebar-overlay" id="sidebar-overlay"></div>
    </div>

    <!-- (BARU) Tambahkan JS untuk Litepicker -->
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>

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

            // --- (LOGIKA DIPERBARUI) untuk Litepicker (DUA INPUT) ---
            const startDatePicker = new Litepicker({
                element: document.getElementById('start_date_picker'),
                singleMode: true, // Mode satu tanggal
                format: 'YYYY-MM-DD', // Format yang dikirim ke PHP
                placeholder: 'Pilih tanggal mulai...',
                dropdowns: {
                    minYear: 2020,
                    maxYear: null,
                    months: true,
                    years: true
                }
            });

            const endDatePicker = new Litepicker({
                element: document.getElementById('end_date_picker'),
                singleMode: true, // Mode satu tanggal
                format: 'YYYY-MM-DD', // Format yang dikirim ke PHP
                placeholder: 'Pilih tanggal akhir...',
                dropdowns: {
                    minYear: 2020,
                    maxYear: null,
                    months: true,
                    years: true
                }
            });

            // (LOGIKA DIPERBARUI) Ambil nilai dari URL jika ada untuk mengisi form
            // Ini agar filter tetap tampil setelah halaman di-reload
            const urlParams = new URLSearchParams(window.location.search);
            const startDateParam = urlParams.get('start_date');
            const endDateParam = urlParams.get('end_date');

            if (startDateParam) {
                // Set nilai di picker (format visual dan nilai input)
                startDatePicker.setDate(new Date(startDateParam));
            }
            if (endDateParam) {
                // Set nilai di picker (format visual dan nilai input)
                endDatePicker.setDate(new Date(endDateParam));
            }


            // --- Logika Tombol Unduh (Contoh) ---
            // Ini memerlukan library seperti SheetJS (xlsx) untuk implementasi nyata
            const downloadBtn = document.getElementById('download-excel-btn');
            if (downloadBtn) {
                downloadBtn.addEventListener('click', () => {
                    console.log('Fungsi unduh excel dipanggil...');
                    // Di sini Anda akan memanggil fungsi untuk mengkonversi tabel ke Excel
                    alert('Fungsi unduh Excel belum diimplementasikan.');
                });
            }
        });
    </script>
</body>
</html>