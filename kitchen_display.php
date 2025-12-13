<?php
require_once __DIR__ . '/config/database.php';
startSecureSession();
redirectIfNotLoggedIn('admin_login.php');

$role = $_SESSION['role'];
if ($role !== 'Dapur' && $role !== 'Super Admin') {
    $_SESSION['error_message'] = 'Halaman Antrian Dapur hanya bisa diakses oleh Dapur.';
    header("Location: " . ($role == 'Kasir' ? 'admin_dashboard.php' : 'admin_menu.php'));
    exit();
}

function getKitchenOrders($db) {
    $sql = "
        SELECT 
            o.order_id, o.table_number, o.status, o.notes, o.order_time,
            oi.quantity, pv.variant_name, p.name as product_name
        FROM orders o
        JOIN order_items oi ON o.order_id = oi.order_id
        JOIN product_variants pv ON oi.variant_id = pv.variant_id
        JOIN products p ON pv.product_id = p.product_id
        WHERE o.status = 'Kirim ke Dapur' OR o.status = 'Sedang Dimasak'
        ORDER BY o.order_time ASC, o.order_id ASC, oi.order_item_id ASC
    ";
    $result = $db->query($sql);
    if (!$result) return [];
    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $order_id = $row['order_id'];
        if (!isset($orders[$order_id])) {
            $orders[$order_id] = [
                'order_id' => $order_id, 'table_number' => $row['table_number'], 'status' => $row['status'],
                'notes' => $row['notes'], 'order_time' => $row['order_time'], 'items' => []
            ];
        }
        $orders[$order_id]['items'][] = [
            'quantity' => $row['quantity'], 'product_name' => $row['product_name'], 'variant_name' => $row['variant_name']
        ];
    }
    return $orders;
}

$orders = getKitchenOrders($db);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="10">
    
    <title>Dapur | Antrian Pesanan</title>
    <link rel="stylesheet" href="css/variable.css">
    <link rel="stylesheet" href="css/admin_menu.css"> 
    <link rel="stylesheet" href="css/kitchen_display.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600;700&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
</head>
<body id="kds-page">

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

        <main class="main-content kds-main-content">
            <header class="admin-header">
                <button class="hamburger" id="hamburger">
                    <i class="fas fa-bars"></i>
                </button>
                <h1>Antrian Dapur</h1>
            </header>
            
            <div class="menu-toolbar" style="margin-bottom: 20px; justify-content: flex-start;">
                <div class="filter-group">
                    <label for="status-filter-kitchen">Filter Status:</label>
                    <select id="status-filter-kitchen" name="status">
                        <option value="all">Semua Status</option>
                        <option value="Kirim ke Dapur">Kirim ke Dapur</option>
                        <option value="Sedang Dimasak">Sedang Dimasak</option>
                    </select>
                </div>
            </div>

            <div class="kitchen-grid" id="kitchen-grid">
                <?php if (empty($orders)): ?>
                    <div class="no-orders-message">
                        <i class="fas fa-clipboard-check"></i>
                        Tidak ada pesanan untuk dimasak.
                    </div>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <div class="kitchen-order-card" data-order-id="<?php echo $order['order_id']; ?>" data-status="<?php echo htmlspecialchars($order['status']); ?>">
                            <div class="card-header">
                                <h3>Meja <?php echo htmlspecialchars($order['table_number']); ?></h3>
                                <div class="order-details">
                                    <span>Order #<?php echo $order['order_id']; ?></span>
                                    <strong><?php echo htmlspecialchars($order['status']); ?></strong>
                                </div>
                            </div>
                            <div class="card-body">
                                <ul class="order-item-list">
                                    <?php foreach ($order['items'] as $item): ?>
                                        <li class="order-item">
                                            <div class="item-info">
                                                <strong><?php echo $item['quantity']; ?>x</strong>
                                                <span><?php echo htmlspecialchars($item['product_name']); ?></span>
                                                <?php if ($item['variant_name']): ?>
                                                    <span class="item-variant">(<?php echo htmlspecialchars($item['variant_name']); ?>)</span>
                                                <?php endif; ?>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="order-notes <?php echo empty($order['notes']) ? 'no-notes' : ''; ?>">
                                    <h4><i class="fas fa-sticky-note"></i> Catatan:</h4>
                                    <p><?php echo nl2br(htmlspecialchars($order['notes'])); ?></p>
                                </div>
                            </div>
                            <div class="card-footer">
                                <?php if ($order['status'] == 'Kirim ke Dapur'): ?>
                                    <button class="btn-kitchen btn-start-cooking" data-order-id="<?php echo $order['order_id']; ?>" data-next-status="Sedang Dimasak">
                                        <i class="fas fa-fire"></i> Mulai Masak
                                    </button>
                                <?php elseif ($order['status'] == 'Sedang Dimasak'): ?>
                                     <button class="btn-kitchen btn-mark-ready" data-order-id="<?php echo $order['order_id']; ?>" data-next-status="Siap Diantar">
                                        <i class="fas fa-check-double"></i> Tandai Siap Diantar
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

    <div id="refresh-bar"></div> 

    <script src="js/kitchen_display.js"></script>
</body>
</html>