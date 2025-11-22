<?php
date_default_timezone_set('Asia/Jakarta'); // (PERBAIKAN POIN 4)
require_once __DIR__ . '/config/database.php';
startSecureSession();
redirectIfNotLoggedIn('admin_login.php');

$role = $_SESSION['role'];
// (PERBAIKAN POIN 2) Role check
if ($role == 'Dapur') {
    // Dapur tidak bisa akses halaman ini, tendang ke KDS
    $_SESSION['error_message'] = 'Halaman pesanan Anda ada di Antrian Dapur.';
    header("Location: kitchen_display.php");
    exit();
}
if ($role !== 'Kasir' && $role !== 'Super Admin') {
     $_SESSION['error_message'] = 'Anda tidak memiliki izin.';
     header("Location: admin_login.php");
     exit();
}

// (Fungsi getOrdersForAdmin tidak berubah)
function getOrdersForAdmin($db, $is_history = false) {
    if ($is_history) {
        $status_filter = "IN ('Selesai', 'Dibatalkan')";
    } else {
        $status_filter = "IN ('Menunggu Pembayaran', 'Kirim ke Dapur', 'Sedang Dimasak', 'Siap Diantar')";
    }
    $sql = "
        SELECT 
            o.order_id, o.table_number, o.status, o.notes, o.order_time, o.total_price,
            oi.quantity, pv.variant_name, p.name as product_name
        FROM orders o
        LEFT JOIN order_items oi ON o.order_id = oi.order_id
        LEFT JOIN product_variants pv ON oi.variant_id = pv.variant_id
        LEFT JOIN products p ON pv.product_id = p.product_id
        WHERE o.status $status_filter
        ORDER BY o.order_time " . ($is_history ? "DESC" : "ASC") . ", oi.order_item_id ASC
    ";
    $result = $db->query($sql);
    if (!$result) return [];
    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $order_id = $row['order_id'];
        if (!isset($orders[$order_id])) {
            $orders[$order_id] = [
                'order_id' => $order_id, 'table_number' => $row['table_number'], 'status' => $row['status'],
                'notes' => $row['notes'], 'total_price' => $row['total_price'], 'order_time' => $row['order_time'],
                'items' => []
            ];
        }
        if ($row['product_name']) {
            $orders[$order_id]['items'][] = [
                'quantity' => $row['quantity'], 'product_name' => $row['product_name'], 'variant_name' => $row['variant_name']
            ];
        }
    }
    return $orders;
}

$active_orders = getOrdersForAdmin($db, false);
$history_orders = getOrdersForAdmin($db, true);

// (PERBAIKAN POIN 2) Status hanya untuk Kasir/SA
$role_statuses = ['Menunggu Pembayaran', 'Kirim ke Dapur', 'Siap Diantar', 'Selesai', 'Dibatalkan'];

// (BARU) Status untuk filter dropdown
$active_filter_statuses = ['Menunggu Pembayaran', 'Kirim ke Dapur', 'Sedang Dimasak', 'Siap Diantar'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Manajemen Pesanan</title>
    <meta http-equiv="refresh" content="30">
    <link rel="stylesheet" href="css/variable.css">
    <link rel="stylesheet" href="css/admin_menu.css">
    <link rel="stylesheet" href="css/admin_orders.css">
    <link rel="stylesheet" href="css/pembayaran.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600;700&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        .status-select:disabled { opacity: 0.5; cursor: not-allowed; background-color: var(--tertiary-color); }
        /* (BARU) Style untuk filter group di toolbar */
        .order-toolbar {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 24px;
        }
        .order-toolbar .filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .order-toolbar .filter-group label {
            font-size: 0.9rem;
            color: var(--text-muted);
            white-space: nowrap;
        }
         .order-toolbar .filter-group select {
            background-color: var(--secondary-color);
            color: var(--light-text);
            border: 1px solid var(--tertiary-color);
            padding: 8px 12px;
            border-radius: 5px;
            font-family: "Montserrat", sans-serif;
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
                <h1>Manajemen Pesanan</h1>
            </header>

            <div class="order-toolbar">
                <div class="filter-group">
                    <label for="status-filter-admin">Filter Status:</label>
                    <select id="status-filter-admin" name="status">
                        <option value="all">Semua Status Aktif</option>
                        <?php foreach ($active_filter_statuses as $status): ?>
                            <option value="<?php echo htmlspecialchars($status); ?>">
                                <?php echo htmlspecialchars($status); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <button id="toggle-view-btn" class="btn btn-secondary">
                    <i class="fas fa-history"></i> Lihat Riwayat
                </button>
            </div>

            <div class="order-grid" id="active-orders-grid">
                
                <?php if (empty($active_orders)): ?>
                    <h3 style="color: var(--text-muted); grid-column: 1 / -1;">Tidak ada pesanan aktif.</h3>
                <?php else: ?>
                    <?php foreach ($active_orders as $order): ?>
                        <div class="order-card" data-status="<?php echo htmlspecialchars($order['status']); ?>">
                            <div class="order-card-header">
                                <div>
                                    <h4>Order #<?php echo $order['order_id']; ?></h4>
                                    <span>Meja: <strong><?php echo $order['table_number']; ?></strong></span>
                                </div>
                                <span class="order-time">
                                    <?php 
                                        $time_diff = time() - strtotime($order['order_time']);
                                        if ($time_diff < 3600) echo floor($time_diff / 60) . " menit lalu";
                                        else if ($time_diff < 86400) echo floor($time_diff / 3600) . " jam lalu";
                                        else echo date('d M Y, H:i', strtotime($order['order_time']));
                                    ?>
                                </span>
                            </div>
                            <div class="order-card-body">
                                <div class="summary-items-list">
                                    <?php if (empty($order['items'])): ?>
                                        <p style="color: var(--text-muted);">Tidak ada item?</p>
                                    <?php else: ?>
                                        <?php foreach ($order['items'] as $item): ?>
                                            <div class="summary-product-item">
                                                <div class="product-name">
                                                    <span><strong><?php echo $item['quantity']; ?>x</strong> <?php echo htmlspecialchars($item['product_name']); ?></span>
                                                    <?php if (isset($item['variant_name']) && $item['variant_name'] !== null): ?>
                                                        <small class="product-notes" style="font-style: italic; color: var(--accent-color);">(<?php echo htmlspecialchars($item['variant_name']); ?>)</small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <?php if (!empty($order['notes'])): ?>
                                        <div class="summary-product-item" style="display: block; border-top: 1px dashed var(--tertiary-color);">
                                            <div class="product-name">
                                                <span style="font-weight: bold; color: var(--text-muted);"><i class="fas fa-sticky-note"></i> Catatan:</span>
                                                <small class="product-notes" style="white-space: pre-wrap;"><?php echo htmlspecialchars($order['notes']); ?></small>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="order-card-footer">
                                <div class="summary-total">
                                    <div class="summary-item grand-total" style="border-top: 1px solid var(--tertiary-color); margin-top: 0;">
                                        <span>Total</span>
                                        <strong>Rp <?php echo number_format($order['total_price'], 0, ',', '.'); ?></strong>
                                    </div>
                                </div>
                                <div class="order-card-status">
                                    <span class="status-badge"><?php echo htmlspecialchars($order['status']); ?></span>
                                </div>
                                
                                <?php if ($role == 'Kasir' || $role == 'Super Admin'): ?>
                                    <div class="order-card-actions">
                                        <label for="status-<?php echo $order['order_id']; ?>">Update Status:</label>
                                        <select id="status-<?php echo $order['order_id']; ?>" name="status" class="status-select" data-order-id="<?php echo $order['order_id']; ?>"
                                            <?php 
                                            $current_status = $order['status'];
                                            $is_dapur_status = in_array($current_status, ['Kirim ke Dapur', 'Sedang Dimasak']);
                                            
                                            if ($role == 'Kasir' && $is_dapur_status) {
                                                echo 'disabled';
                                            }
                                            ?>>
                                            
                                            <?php
                                            $options = [];
                                            
                                            if ($role == 'Super Admin') {
                                                // Super Admin dapat memilih SEMUA status
                                                $options = ['Menunggu Pembayaran', 'Kirim ke Dapur', 'Sedang Dimasak', 'Siap Diantar', 'Selesai', 'Dibatalkan'];
                                            } else {
                                                // Logika Kasir (tetap)
                                                if ($current_status == 'Menunggu Pembayaran') {
                                                    $options = ['Menunggu Pembayaran', 'Kirim ke Dapur', 'Dibatalkan'];
                                                } else if ($is_dapur_status) {
                                                    $options = [$current_status]; 
                                                } else if ($current_status == 'Siap Diantar') {
                                                    $options = ['Siap Diantar', 'Selesai', 'Dibatalkan'];
                                                }
                                            }
                                            
                                            // Tampilkan opsi yang diizinkan
                                            foreach ($options as $status) {
                                                echo "<option value=\"$status\" " . ($current_status == $status ? 'selected' : '') . ">$status</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="order-grid" id="history-orders-grid" style="display: none;">
                <?php if (empty($history_orders)): ?>
                    <h3 style="color: var(--text-muted); grid-column: 1 / -1;">Tidak ada riwayat pesanan.</h3>
                <?php else: ?>
                    <?php foreach ($history_orders as $order): ?>
                        <div class="order-card" data-status="<?php echo htmlspecialchars($order['status']); ?>">
                            <div class="order-card-header">
                                <div>
                                    <h4>Order #<?php echo $order['order_id']; ?></h4>
                                    <span>Meja: <strong><?php echo $order['table_number']; ?></strong></span>
                                </div>
                                <span class="order-time"><?php echo date('d M Y, H:i', strtotime($order['order_time'])); ?></span>
                            </div>
                            <div class="order-card-footer">
                                <div class="summary-total">
                                    <div class="summary-item grand-total" style="border-top: none; margin-top: 0; padding-top: 0;">
                                        <span>Total Dibayar</span>
                                        <strong>Rp <?php echo number_format($order['total_price'], 0, ',', '.'); ?></strong>
                                    </div>
                                </div>
                                <div class="order-card-status">
                                    <span class="status-badge"><?php echo htmlspecialchars($order['status']); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
        
        <div class="sidebar-overlay" id="sidebar-overlay"></div>

        <div id="refresh-bar"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Sidebar (Kode tidak berubah)
            const hamburger = document.getElementById('hamburger');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (hamburger) hamburger.addEventListener('click', () => sidebar.classList.add('show'));
            if (overlay) overlay.addEventListener('click', () => sidebar.classList.remove('show'));

            // Toggle Riwayat (Kode tidak berubah)
            const toggleBtn = document.getElementById('toggle-view-btn');
            const activeGrid = document.getElementById('active-orders-grid');
            const historyGrid = document.getElementById('history-orders-grid');
            let isShowingHistory = false;
            if (toggleBtn) {
                toggleBtn.addEventListener('click', () => {
                    isShowingHistory = !isShowingHistory;
                    if (isShowingHistory) {
                        activeGrid.style.display = 'none';
                        historyGrid.style.display = 'grid';
                        toggleBtn.innerHTML = '<i class="fas fa-clipboard-list"></i> Lihat Pesanan Aktif';
                    } else {
                        activeGrid.style.display = 'grid';
                        historyGrid.style.display = 'none';
                        toggleBtn.innerHTML = '<i class="fas fa-history"></i> Lihat Riwayat';
                    }
                });
            }

            // --- (BARU) Logika Filter Status Admin ---
            const statusFilterAdmin = document.getElementById('status-filter-admin');
            
            if (statusFilterAdmin && activeGrid) {
                const allAdminItems = activeGrid.querySelectorAll('.order-card');
                
                if (allAdminItems.length > 0) {
                    let noAdminResultsMessage = document.createElement('h3');
                    noAdminResultsMessage.textContent = 'Tidak ada pesanan yang cocok dengan filter status.';
                    noAdminResultsMessage.style.color = 'var(--text-muted)';
                    noAdminResultsMessage.style.gridColumn = '1 / -1';
                    noAdminResultsMessage.style.display = 'none';
                    activeGrid.appendChild(noAdminResultsMessage);

                    function filterAdminOrders() {
                        const selectedStatus = statusFilterAdmin.value;
                        let itemsFound = 0;

                        allAdminItems.forEach(item => {
                            const itemStatus = item.dataset.status;
                            if (selectedStatus === 'all' || itemStatus === selectedStatus) {
                                item.style.display = 'block'; // Kartu adalah block
                                itemsFound++;
                            } else {
                                item.style.display = 'none';
                            }
                        });

                        noAdminResultsMessage.style.display = (itemsFound === 0) ? 'block' : 'none';
                    }

                    statusFilterAdmin.addEventListener('change', filterAdminOrders);
                }
            }
            // --- Akhir Logika Filter Status Admin ---


            // LOGIKA UPDATE STATUS DENGAN KONFIRMASI (Kode tidak berubah)
            const activeOrdersGrid = document.getElementById('active-orders-grid');
            if (activeOrdersGrid) {
                activeOrdersGrid.addEventListener('change', (e) => {
                    if (e.target.classList.contains('status-select')) {
                        const selectElement = e.target;
                        const orderId = selectElement.dataset.orderId;
                        const newStatus = selectElement.value;
                        const card = selectElement.closest('.order-card');
                        const originalStatus = card.dataset.status;
                        
                        // Logika Konfirmasi Pop-up
                        let isConfirmed = true;
                        if (newStatus === 'Selesai') {
                            // Mengembalikan alert Selesai
                            isConfirmed = confirm(`Anda yakin ingin menyelesaikan Pesanan #${orderId}? Pesanan akan dipindahkan ke riwayat.`);
                        } else if (newStatus === 'Dibatalkan') {
                            // Mengembalikan alert Dibatalkan
                            isConfirmed = confirm(`ANDA YAKIN ingin membatalkan Pesanan #${orderId}? Aksi ini tidak dapat diurungkan.`);
                        } else if (newStatus !== originalStatus) {
                            // Mengembalikan alert perubahan status lainnya
                            isConfirmed = confirm(`Anda yakin ingin mengubah status Pesanan #${orderId} dari ${originalStatus} menjadi ${newStatus}?`);
                        }

                        if (!isConfirmed) {
                            selectElement.value = originalStatus;
                            return; 
                        }
                        
                        selectElement.disabled = true;
                        const payload = { order_id: orderId, status: newStatus };

                        fetch('actions/update_order_status.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(payload)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                if (newStatus === 'Selesai' || newStatus === 'Dibatalkan') {
                                     // Mengembalikan alert sukses Selesai/Batal
                                     alert('Status diperbarui! Pesanan dipindahkan ke Riwayat.');
                                } else {
                                     // Mengembalikan alert sukses biasa
                                     alert('Status diperbarui!');
                                }
                                window.location.reload();
                            } else {
                                // Mengembalikan alert gagal
                                alert('Gagal: ' + data.message);
                                selectElement.value = originalStatus;
                                selectElement.disabled = false;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            // Mengembalikan alert koneksi bermasalah
                            alert('Terjadi kesalahan koneksi.');
                            selectElement.value = originalStatus;
                            selectElement.disabled = false;
                        });
                    }
                });
            }
        });
    </script>
</body>
</html>