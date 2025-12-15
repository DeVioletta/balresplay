<?php
date_default_timezone_set('Asia/Jakarta');
require_once __DIR__ . '/config/database.php';
startSecureSession();
redirectIfNotLoggedIn('admin_login.php');

$role = $_SESSION['role'];

if ($role == 'Dapur') {
    $_SESSION['error_message'] = 'Halaman pesanan Anda ada di Antrian Dapur.';
    header("Location: kitchen_display.php");
    exit();
}
if ($role !== 'Kasir' && $role !== 'Super Admin') {
     $_SESSION['error_message'] = 'Anda tidak memiliki izin.';
     header("Location: admin_login.php");
     exit();
}

function getOrdersForAdmin($db, $is_history = false) {
    if ($is_history) {
        $status_filter = "IN ('Selesai', 'Dibatalkan')";
    } else {
        $status_filter = "IN ('Menunggu Pembayaran', 'Kirim ke Dapur', 'Sedang Dimasak', 'Siap Diantar')";
    }
    // [PERBAIKAN 1] Tambahkan o.payment_method ke dalam Query
    $sql = "
        SELECT 
            o.order_id, o.table_number, o.status, o.notes, o.order_time, o.total_price, o.payment_method,
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
                'order_id' => $order_id, 
                'table_number' => $row['table_number'], 
                'status' => $row['status'],
                'notes' => $row['notes'], 
                'total_price' => $row['total_price'], 
                'order_time' => $row['order_time'],
                'payment_method' => $row['payment_method'], // Simpan payment method
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
                <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
                
                <?php if ($role == 'Super Admin' || $role == 'Kasir'): ?>
                    <a href="admin_dashboard.php" class="<?php echo $currentPage == 'admin_dashboard.php' ? 'active' : ''; ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <?php endif; ?>
                
                <a href="admin_menu.php" class="<?php echo ($currentPage == 'admin_menu.php' || $currentPage == 'admin_form_menu.php') ? 'active' : ''; ?>"><i class="fas fa-utensils"></i> Menu Cafe</a>
                
                <?php if ($role == 'Dapur'): ?>
                     <a href="kitchen_display.php" class="<?php echo $currentPage == 'kitchen_display.php' ? 'active' : ''; ?>"><i class="fas fa-receipt"></i> Antrian Dapur</a>
                <?php else: ?>
                    <a href="admin_orders.php" class="<?php echo $currentPage == 'admin_orders.php' ? 'active' : ''; ?>"><i class="fas fa-receipt"></i> Pesanan</a>
                <?php endif; ?>
                
                <?php if ($role == 'Super Admin'): ?>
                    <a href="admin_settings.php" class="<?php echo $currentPage == 'admin_settings.php' ? 'active' : ''; ?>"><i class="fas fa-cog"></i> Pengaturan</a>
                <?php endif; ?>
            </nav>

            <div class="sidebar-footer">
                <a href="actions/handle_logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
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
                            <option value="<?php echo htmlspecialchars($status); ?>"><?php echo htmlspecialchars($status); ?></option>
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
                        <?php 
                            // Cek tipe pembayaran
                            $paymentMethod = $order['payment_method'];
                            $isQRIS = ($paymentMethod === 'QRIS');
                            $badgeClass = $isQRIS ? 'payment-qris' : 'payment-cash';
                        ?>
                        <div class="order-card" data-status="<?php echo htmlspecialchars($order['status']); ?>">
                            <div class="order-card-header">
                                <div>
                                    <h4>Order #<?php echo $order['order_id']; ?> 
                                        <span class="payment-badge <?php echo $badgeClass; ?>">
                                            <?php echo htmlspecialchars($paymentMethod); ?>
                                        </span>
                                    </h4>
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
                                        
                                        <?php if ($isQRIS && $order['status'] == 'Menunggu Pembayaran'): ?>
                                            <div style="text-align: center; margin-bottom: 10px;">
                                                <small style="color: #d32f2f; display: block; margin-bottom: 5px;">
                                                    <i class="fas fa-info-circle"></i> Menunggu Konfirmasi Midtrans
                                                </small>
                                                <label for="status-<?php echo $order['order_id']; ?>">Aksi Darurat:</label>
                                                <select id="status-<?php echo $order['order_id']; ?>" name="status" class="status-select" data-order-id="<?php echo $order['order_id']; ?>">
                                                    <option value="Menunggu Pembayaran" selected>Menunggu...</option>
                                                    <option value="Dibatalkan">Batalkan Pesanan</option>
                                                </select>
                                            </div>
                                        
                                        <?php else: ?>
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
                                                    $options = ['Menunggu Pembayaran', 'Kirim ke Dapur', 'Sedang Dimasak', 'Siap Diantar', 'Selesai', 'Dibatalkan'];
                                                } else {
                                                    if ($current_status == 'Menunggu Pembayaran') {
                                                        $options = ['Menunggu Pembayaran', 'Kirim ke Dapur', 'Dibatalkan'];
                                                    } else if ($is_dapur_status) {
                                                        $options = [$current_status]; 
                                                    } else if ($current_status == 'Siap Diantar') {
                                                        $options = ['Siap Diantar', 'Selesai', 'Dibatalkan'];
                                                    }
                                                }
                                                
                                                foreach ($options as $status) {
                                                    echo "<option value=\"$status\" " . ($current_status == $status ? 'selected' : '') . ">$status</option>";
                                                }
                                                ?>
                                            </select>
                                        <?php endif; ?>

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
                                    <h4>Order #<?php echo $order['order_id']; ?> 
                                        <span class="payment-badge <?php echo ($order['payment_method'] === 'QRIS') ? 'payment-qris' : 'payment-cash'; ?>">
                                            <?php echo htmlspecialchars($order['payment_method']); ?>
                                        </span>
                                    </h4>
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

    <script src="js/admin_orders.js"></script>
</body>
</html>