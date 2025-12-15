<?php
require_once __DIR__ . '/config/database.php';
startSecureSession();

if (!isset($_SESSION['customer_id'])) {
    if (isset($_COOKIE['balresplay_cust_id'])) {
        $_SESSION['customer_id'] = $_COOKIE['balresplay_cust_id'];
    } else {
        try {
            $unique_id = bin2hex(random_bytes(16));
        } catch (Exception $e) {
            $unique_id = md5(uniqid(rand(), true));
        }
        $_SESSION['customer_id'] = $unique_id;
        setcookie('balresplay_cust_id', $unique_id, time() + 86400, "/", "", false, true); 
    }
}

$products_list = getAllProductsWithVariants($db); 

$available_categories = [];
foreach ($products_list as $product) {
    $product_is_available = false;
    if (!empty($product['variants'])) {
        foreach ($product['variants'] as $variant) {
            if ($variant['is_available'] == 1) { 
                $product_is_available = true;
                break;
            }
        }
    }
    if ($product_is_available) {
        $category_name = $product['category'];
        if (!isset($available_categories[$category_name])) {
            $available_categories[$category_name] = [];
        }
        $available_categories[$category_name][] = $product;
    }
}

$table_count_result = $db->query("SELECT setting_value FROM settings WHERE setting_key = 'table_count' LIMIT 1");
if ($table_count_result && $table_count_result->num_rows > 0) {
    $table_count = (int)$table_count_result->fetch_assoc()['setting_value'];
} else {
    $table_count = 20; 
}
$table_numbers = range(1, $table_count);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BalResplay | Menu</title>
    <link rel="stylesheet" href="css/variable.css">
    <link rel="stylesheet" href="css/menu.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600;700&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    
    <!-- Ganti CLIENT_KEY dengan Client Key Midtrans Anda -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="CLIENT KEY"></script>
</head>
<body>

<?php include 'includes/header.php'; ?> 

    <section class="page-hero">
        <div class="container">
            <h1>Our Menu</h1>
            <p>Nikmati kopi, teh, dan camilan favoritmu dalam suasana yang nyaman</p>
        </div>
    </section>

    <section class="menu-nav">
        <div class="container">
            <div class="menu-categories">
                <?php foreach ($available_categories as $category_name => $products): ?>
                    <a href="#<?php echo htmlspecialchars($category_name); ?>" class="category-link">
                        <?php echo ucfirst(str_replace('-', ' ', $category_name)); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php foreach ($available_categories as $category_name => $products): ?>
    <section id="<?php echo htmlspecialchars($category_name); ?>" class="menu-section section-padding">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php echo ucfirst(str_replace('-', ' ', $category_name)); ?></h2>
            </div>
            <div class="menu-grid">
                
                <?php foreach ($products as $product): ?>
                    <?php
                        $image_url = !empty($product['image_url']) 
                            ? htmlspecialchars($product['image_url']) 
                            : 'https://placehold.co/300x300/e8e4d8/5c6e58?text=' . urlencode($product['name']);
                        
                        $first_variant = $product['variants'][0] ?? null;
                        $is_simple_product = (count($product['variants']) == 1 && ($first_variant['name'] == null || $first_variant['name'] == ''));
                    ?>

                    <div class="menu-item" 
                         data-id="<?php echo $product['product_id']; ?>" 
                         data-name="<?php echo htmlspecialchars($product['name']); ?>" 
                         data-img="<?php echo $image_url; ?>"
                         <?php if ($is_simple_product && $first_variant): ?>
                            data-price="<?php echo $first_variant['price']; ?>"
                            data-variant-id="<?php echo $first_variant['variant_id']; ?>"
                         <?php endif; ?>
                         >
                        
                        <div class="item-image">
                            <img src="<?php echo $image_url; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        </div>

                        <div class="item-info">
                            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p><?php echo htmlspecialchars($product['description']); ?></p>

                            <?php if (!$is_simple_product && !empty($product['variants'])): ?>
                            <div class="item-variants">
                                <?php 
                                $first_available_variant_index = -1;
                                foreach ($product['variants'] as $index => $variant) {
                                    if ($variant['is_available'] == 1) {
                                        if ($first_available_variant_index == -1) {
                                            $first_available_variant_index = $index;
                                        }
                                ?>
                                    <label>
                                        <input type="radio" 
                                               name="variant_<?php echo $product['product_id']; ?>" 
                                               value="<?php echo htmlspecialchars($variant['name']); ?>"
                                               data-price="<?php echo $variant['price']; ?>"
                                               data-variant-id="<?php echo $variant['variant_id']; ?>"
                                               <?php echo ($index == $first_available_variant_index) ? 'checked' : ''; ?>>
                                        <?php echo htmlspecialchars($variant['name']); ?>
                                    </label>
                                <?php 
                                    }
                                } 
                                ?>
                            </div>
                            <?php endif; ?>

                            <div class="item-meta">
                                <span class="item-price"><?php echo ($first_variant ? $first_variant['price'] / 1000 : '0'); ?>k</span>
                                <button class="btn-add">Tambah</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </section>
    <?php endforeach; ?>


    <div class="cart-modal" id="cart-modal">     
        <div class="cart-modal-content">
            <span class="cart-close" id="cart-close">&times;</span>
            <h2 class="cart-title">Pesanan Anda</h2>
            <div class="cart-items" id="cart-items">
                 <div class="cart-empty-message">
                    <i class="fas fa-shopping-cart"></i>
                    <p>Keranjang Anda masih kosong.</p>
                </div>
            </div>
            <div class="cart-footer" id="cart-footer" style="display: none;">
                <div class="cart-table-number">
                    <span>Nomor Meja:</span>
                    <select id="table-number-select">
                        <?php foreach ($table_numbers as $table): ?>
                            <option value="<?php echo $table; ?>">Meja <?php echo $table; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="cart-total">
                    <span>Total:</span>
                    <span class="cart-total-price" id="cart-total-price">0k</span>
                </div>
                <div class="cart-buttons">
                    <button class="btn btn-secondary" id="continue-shopping">Lanjut Belanja</button>
                    <button class="btn btn-primary" id="place-order-btn">Pesan Sekarang</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="cart-modal" id="order-status-modal">
        <div class="cart-modal-content">
            <span class="cart-close" id="status-close">&times;</span>
            <h2 class="cart-title">Status Pesanan Anda</h2>
            <div id="order-status-details">
                <div class="cart-empty-message">
                    <p>Tidak ada pesanan yang sedang aktif.</p>
                </div>
            </div>
        </div>
    </div>

    <div id="resume-payment-bar">
        <div style="display: flex; justify-content: center; align-items: center; gap: 15px; flex-wrap: wrap;">
            <span style="color: #856404; font-weight: 500; font-family: 'Montserrat', sans-serif;">
                <i class="fas fa-exclamation-circle"></i> 
                Menunggu pembayaran untuk <strong>Order #<span id="resume-order-id"></span></strong>.
            </span>
            <button id="btn-resume-payment">
                Bayar Sekarang <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
    
    <script src="js/menu.js"></script>
</body>
</html>