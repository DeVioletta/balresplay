<?php
// 1. Sertakan file konfigurasi & mulai sesi
require_once __DIR__ . '/config/database.php';
startSecureSession();

// --- [BARU] Logika Customer ID (Privasi & Keamanan) ---
// Cek apakah user sudah punya customer_id di session
if (!isset($_SESSION['customer_id'])) {
    // Jika belum ada di session, cek apakah ada di cookie (untuk recover jika browser tertutup)
    if (isset($_COOKIE['balresplay_cust_id'])) {
        $_SESSION['customer_id'] = $_COOKIE['balresplay_cust_id'];
    } else {
        // Generate UUID v4 atau random token aman (32 karakter hex)
        try {
            $unique_id = bin2hex(random_bytes(16));
        } catch (Exception $e) {
            // Fallback jika random_bytes gagal (jarang terjadi)
            $unique_id = md5(uniqid(rand(), true));
        }
        
        $_SESSION['customer_id'] = $unique_id;
        
        // Simpan juga di cookie selama 24 jam agar aman jika tab tertutup
        // Parameter: nama, nilai, expire, path, domain, secure (true jika HTTPS), httponly
        setcookie('balresplay_cust_id', $unique_id, time() + 86400, "/", "", false, true); 
    }
}
// -----------------------------------------------------

// 2. Ambil semua data produk dari database
$products_list = getAllProductsWithVariants($db); 

// Filter produk yang tersedia SEBELUM membuat kategori
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

// Ambil jumlah meja dari database
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
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-XXXXXXXXXXXXXXXX"></script>

    <style>
        .item-image .badge-habis {
            position: absolute; top: 10px; right: 10px; background-color: var(--danger-color);
            color: var(--light-text); padding: 5px 12px; border-radius: 20px;
            font-size: 12px; font-weight: 700; z-index: 2;
        }
        .btn-add.disabled { background-color: var(--tertiary-color); border-color: var(--tertiary-color); cursor: not-allowed; }
        .cart-table-number select {
            background-color: var(--secondary-color); color: var(--light-text);
            border: 1px solid var(--tertiary-color); padding: 5px 8px;
            border-radius: 5px; font-size: 1rem; font-family: "Montserrat", sans-serif;
        }
        .cart-table-number select:focus { outline: 1px solid var(--accent-color); }
        
        .status-item {
            display: flex; justify-content: space-between; align-items: flex-start;
            padding: 15px 0; border-bottom: 1px dashed var(--tertiary-color);
        }
        .status-item:last-child { border-bottom: none; }
        .status-item-info { flex-grow: 1; }
        .status-item-info h4 { margin: 0 0 5px 0; font-size: 1rem; color: var(--light-text); }
        .status-item-info small { color: var(--text-muted); font-style: italic; }
        .status-item-price { font-weight: 600; color: var(--accent-color); white-space: nowrap; margin-left: 15px; }
        .status-header { text-align: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid var(--tertiary-color); }
        .status-header p { margin: 0; color: var(--text-muted); }
        .status-header strong { display: block; margin-top: 5px; font-size: 1.2rem; color: var(--accent-color); }
        .status-items { padding: 0 20px 20px 20px; }
        
        .status-order-instance {
            margin-bottom: 15px;
            border: 1px solid var(--tertiary-color);
            border-radius: 8px;
            overflow: hidden;
        }
        .status-order-instance:last-child {
            margin-bottom: 0;
        }

        /* Style untuk Resume Payment Bar */
        #resume-payment-bar {
            display: none; 
            position: fixed; 
            top:40px; 
            left: 0; 
            width: 100%; 
            background-color: #fff3cd; 
            border-top: 2px solid #ffecb5; 
            padding: 15px; 
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1); 
            z-index: 9999; 
            text-align: center;
            animation: slideUp 0.5s ease-out;
        }
        @keyframes slideUp {
            from { transform: translateY(100%); }
            to { transform: translateY(0); }
        }
        #btn-resume-payment {
            background-color: #0d6efd; 
            color: white; 
            border: none; 
            padding: 8px 20px; 
            border-radius: 5px; 
            cursor: pointer; 
            font-weight: bold;
            margin-left: 10px;
            font-family: 'Montserrat', sans-serif;
        }
        #btn-resume-payment:hover {
            background-color: #0b5ed7;
        }
    </style>
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
    
<script>
document.addEventListener('DOMContentLoaded', () => {
    const cartIcon = document.getElementById('cart-icon');
    const cartModal = document.getElementById('cart-modal');
    const cartClose = document.getElementById('cart-close');
    const continueShoppingBtn = document.getElementById('continue-shopping');
    const addToCartButtons = document.querySelectorAll('.btn-add');
    const cartItemsContainer = document.getElementById('cart-items');
    const cartCountElement = document.getElementById('cart-count');
    const cartTotalPriceElement = document.getElementById('cart-total-price');
    const cartFooter = document.getElementById('cart-footer');
    const placeOrderBtn = document.getElementById('place-order-btn');
    const orderStatusIcon = document.getElementById('order-status-icon');
    const orderStatusModal = document.getElementById('order-status-modal');
    const statusClose = document.getElementById('status-close');
    const orderStatusDetailsContainer = document.getElementById('order-status-details');
    const tableNumberSelect = document.getElementById('table-number-select');

    let cart = [];
    let totalPrice = 0;
    
    // --- [UPDATE] Logic Nomor Meja (Hanya untuk Pre-fill) ---
    const urlParams = new URLSearchParams(window.location.search);
    let currentTableNumber = urlParams.get('meja') || '1'; 
    
    tableNumberSelect.value = currentTableNumber;
    sessionStorage.setItem('tableNumber', currentTableNumber);
    
    tableNumberSelect.addEventListener('change', (e) => {
        currentTableNumber = e.target.value;
        sessionStorage.setItem('tableNumber', currentTableNumber);
        const newUrl = `${window.location.pathname}?meja=${currentTableNumber}`;
        window.history.pushState({path: newUrl}, '', newUrl);
        // Note: Tidak perlu checkOrderStatus() ulang karena status sekarang berbasis session, bukan meja
    });

    const openModal = () => cartModal.classList.add('show');
    const closeModal = () => cartModal.classList.remove('show');
    cartIcon.addEventListener('click', openModal);
    cartClose.addEventListener('click', closeModal);
    continueShoppingBtn.addEventListener('click', closeModal);
    cartModal.addEventListener('click', (e) => {
        if (e.target === cartModal) closeModal();
    });
    addToCartButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            const menuItem = e.target.closest('.menu-item');
            const id = menuItem.dataset.id;
            const name = menuItem.dataset.name;
            const img = menuItem.dataset.img;
            let price;
            let variant = null;
            let variant_id; 
            const variantInput = menuItem.querySelector('.item-variants input[type="radio"]:checked');
            if (variantInput) {
                price = parseFloat(variantInput.dataset.price);
                variant = variantInput.value;
                variant_id = variantInput.dataset.variantId;
            } else {
                price = parseFloat(menuItem.dataset.price);
                variant_id = menuItem.dataset.variantId;
                variant = null; 
            }
            if (!variant_id) { 
                alert('Silakan pilih varian terlebih dahulu (jika ada).');
                return; 
            }
            const cartItemId = variant ? `${id}_${variant_id}` : variant_id;
            const existingItem = cart.find(item => item.cartId === cartItemId);
            if (existingItem) {
                existingItem.quantity++;
            } else {
                cart.push({ 
                    cartId: cartItemId, id: id, variant_id: variant_id, name: name, 
                    price: price, img: img, variant: variant, quantity: 1, notes: '' 
                });
            }
            updateCart();
            openModal();
        });
    });
    
    document.querySelectorAll('.item-variants input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', (e) => {
            if (e.target.checked) {
                const menuItem = e.target.closest('.menu-item');
                const priceElement = menuItem.querySelector('.item-price');
                const newPrice = parseFloat(e.target.dataset.price);
                priceElement.textContent = `${(newPrice / 1000).toLocaleString('id-ID')}k`;
            }
        });
    });
    
    document.querySelectorAll('.menu-item').forEach(menuItem => {
        const variantInput = menuItem.querySelector('.item-variants input[type="radio"]:checked');
        if (variantInput) {
             const priceElement = menuItem.querySelector('.item-price');
             const newPrice = parseFloat(variantInput.dataset.price);
             priceElement.textContent = `${(newPrice / 1000).toLocaleString('id-ID')}k`;
        }
    });

    function updateCart() {
        cartItemsContainer.innerHTML = '';
        let totalItems = 0;
        totalPrice = 0;
        if (cart.length === 0) {
            cartItemsContainer.innerHTML = `<div class="cart-empty-message"><i class="fas fa-shopping-cart"></i><p>Keranjang Anda masih kosong.</p></div>`;
            cartFooter.style.display = 'none';
        } else {
            cart.forEach(item => {
                const itemElement = document.createElement('div');
                itemElement.classList.add('cart-item');
                const variantHTML = (item.variant && item.variant.trim() !== "") 
                    ? `<p class="item-variant">(${htmlspecialchars(item.variant)})</p>` 
                    : '';
                itemElement.innerHTML = `
                    <img src="${item.img}" alt="${item.name}" class="cart-item-img">
                    <div class="cart-item-info">
                        <h4>${htmlspecialchars(item.name)}</h4>
                        ${variantHTML}
                        <p class="cart-item-price-small">${(item.price / 1000).toLocaleString('id-ID')}k</p>
                    </div>
                    <div class="cart-item-controls">
                        <div class="cart-item-quantity">
                            <button class="quantity-btn" data-id="${item.cartId}" data-action="decrease">-</button>
                            <span class="quantity-display">${item.quantity}</span>
                            <button class="quantity-btn" data-id="${item.cartId}" data-action="increase">+</button>
                        </div>
                        <button class="cart-item-remove" data-id="${item.cartId}"><i class="fas fa-trash-alt"></i></button>
                    </div>
                    <div class="cart-item-notes">
                        <textarea class="notes-input" data-id="${item.cartId}" placeholder="Contoh: Tidak pedas, sedikit gula...">${htmlspecialchars(item.notes)}</textarea>
                    </div>
                `;
                cartItemsContainer.appendChild(itemElement);
                totalItems += item.quantity;
                totalPrice += item.price * item.quantity;
            });
            cartFooter.style.display = 'block';
        }
        cartCountElement.textContent = totalItems;
        cartTotalPriceElement.textContent = `${(totalPrice / 1000).toLocaleString('id-ID')}k`;
    }
    cartItemsContainer.addEventListener('click', e => {
        const target = e.target.closest('button');
        if (!target) return;
        const cartId = target.dataset.id;
        if (target.classList.contains('quantity-btn')) {
            const action = target.dataset.action;
            const itemToUpdate = cart.find(item => item.cartId === cartId);
            if (action === 'increase') {
                itemToUpdate.quantity++;
            } else if (action === 'decrease') {
                itemToUpdate.quantity--;
                if (itemToUpdate.quantity <= 0) cart = cart.filter(item => item.cartId !== cartId);
            }
        }
        if (target.classList.contains('cart-item-remove')) {
            cart = cart.filter(item => item.cartId !== cartId);
        }
        updateCart();
    });
    cartItemsContainer.addEventListener('input', e => {
         if (e.target.classList.contains('notes-input')) {
            const cartId = e.target.dataset.id;
            const itemToUpdate = cart.find(item => item.cartId === cartId);
            if (itemToUpdate) itemToUpdate.notes = e.target.value;
        }
    });
    function htmlspecialchars(str) {
        if (typeof str !== 'string') return '';
        return str.replace(/[&<>"']/g, function(match) {
            const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' };
            return map[match];
        });
    }
    placeOrderBtn.addEventListener('click', () => {
        if (cart.length > 0) {
            sessionStorage.setItem('cartData', JSON.stringify(cart));
            sessionStorage.setItem('cartTotalPrice', totalPrice);
            window.location.href = 'payment.php'; 
        } else {
            alert('Keranjang Anda kosong! Silakan tambahkan item untuk memesan.');
        }
    });

    const openStatusModal = () => {
        updateOrderStatusView();
        orderStatusModal.classList.add('show');
    };
    const closeStatusModal = () => orderStatusModal.classList.remove('show');
    orderStatusIcon.addEventListener('click', openStatusModal);
    statusClose.addEventListener('click', closeStatusModal);
    orderStatusModal.addEventListener('click', (e) => {
        if (e.target === orderStatusModal) closeStatusModal();
    });
    
    // --- [UPDATE] Key Storage Baru untuk Privasi ---
    const orderStatusKey = 'balresplay_my_orders'; 

    window.updateOrderStatusView = function() {
        // Ambil data dari key baru yang unik per user
        let currentOrdersArray = JSON.parse(sessionStorage.getItem(orderStatusKey));
        
        if (!currentOrdersArray || !Array.isArray(currentOrdersArray) || currentOrdersArray.length === 0) {
            orderStatusDetailsContainer.innerHTML = `<div class="cart-empty-message"><p>Tidak ada pesanan aktif.</p></div>`;
            return;
        }
        
        let combinedHTML = '';
        
        currentOrdersArray.forEach(currentOrderData => {
            if (!currentOrderData || !currentOrderData.items) return; 

            let itemsHTML = currentOrderData.items.map(item => {
                const variantHTML = (item.variant && item.variant.trim() !== "") ? ` (${htmlspecialchars(item.variant)})` : '';
                const itemName = item.product_name || item.name; 
                const itemPrice = (item.price_per_item ? (item.price_per_item * item.quantity) : (item.price * item.quantity)); 
                return `
                <div class="status-item">
                    <div class="status-item-info">
                        <h4>${item.quantity}x ${htmlspecialchars(itemName)}${variantHTML}</h4>
                        ${item.notes ? `<small>Catatan: ${htmlspecialchars(item.notes)}</small>` : ''}
                    </div>
                    <span class="status-item-price">Rp ${itemPrice.toLocaleString('id-ID')}</span>
                </div>
            `}).join('');

            const mainNotesHTML = (currentOrderData.notes && currentOrderData.notes.trim() !== "")
                ? `<div class="status-item"><small><strong>Catatan Utama:</strong> ${htmlspecialchars(currentOrderData.notes)}</small></div>`
                : '';
            
            combinedHTML += `
                <div class="status-order-instance">
                    <div class="status-header">
                        <p style="font-size: 1rem;">Pesanan #${currentOrderData.order_id} (Meja ${currentOrderData.table_number})</p>
                        <strong>${htmlspecialchars(currentOrderData.status)}</strong>
                    </div>
                    <div class="status-items">
                        ${itemsHTML}
                        ${mainNotesHTML}
                    </div>
                </div>
            `;
        });
        
        orderStatusDetailsContainer.innerHTML = combinedHTML;
    };

    let pollingInterval;
    function startPolling() {
        if (pollingInterval) clearInterval(pollingInterval);
        
        const poll = () => {
            const cacheBuster = `&_=${new Date().getTime()}`;
            
            // --- [UPDATE] Fetch tanpa parameter meja (Keamanan) ---
            fetch(`actions/get_order_status.php?${cacheBuster}`) 
                .then(response => response.json())
                .then(data => {
                    
                    if (data.status === 'found' && data.orders && data.orders.length > 0) { 
                        orderStatusIcon.style.display = 'flex';
                        // Simpan ke key storage baru
                        sessionStorage.setItem(orderStatusKey, JSON.stringify(data.orders)); 
                        if (orderStatusModal.classList.contains('show')) {
                            updateOrderStatusView();
                        }
                    } else if (data.status === 'empty') { 
                        orderStatusIcon.style.display = 'none';
                        sessionStorage.removeItem(orderStatusKey);
                    }
                })
                .catch(error => console.warn('Gagal polling status:', error));
        };
        poll(); 
        pollingInterval = setInterval(poll, 7000); 
    }

    function checkOrderStatus() {
        startPolling();
    }
    
    updateCart();
    checkOrderStatus(); 

    // === RESUME PAYMENT DENGAN VALIDASI ===
    const pendingToken = sessionStorage.getItem('pending_snap_token');
    const pendingOrderId = sessionStorage.getItem('pending_order_id');
    const resumeBar = document.getElementById('resume-payment-bar');
    const resumeOrderIdSpan = document.getElementById('resume-order-id');
    const btnResume = document.getElementById('btn-resume-payment');

    if (pendingToken && pendingOrderId) {
        
        fetch(`actions/validate_pending_order.php?order_id=${pendingOrderId}`)
            .then(response => response.json())
            .then(data => {
                if (data.valid) {
                    resumeOrderIdSpan.textContent = pendingOrderId;
                    resumeBar.style.display = 'block'; 
                } else {
                    console.log("Pending order invalid/expired. Clearing session.");
                    sessionStorage.removeItem('pending_snap_token');
                    sessionStorage.removeItem('pending_order_id');
                    resumeBar.style.display = 'none';
                }
            })
            .catch(err => {
                console.error("Gagal memvalidasi order pending:", err);
                resumeBar.style.display = 'none';
            });
    }

    if (btnResume) {
        btnResume.addEventListener('click', () => {
            const currentToken = sessionStorage.getItem('pending_snap_token');
            
            if (typeof window.snap !== 'undefined' && currentToken) {
                window.snap.pay(currentToken, {
                    onSuccess: function(result){
                        alert("Pembayaran Berhasil! Terima kasih.");
                        sessionStorage.removeItem('pending_snap_token');
                        sessionStorage.removeItem('pending_order_id');
                        resumeBar.style.display = 'none';
                        window.location.reload(); 
                    },
                    onPending: function(result){
                        alert("Menunggu pembayaran...");
                        window.location.reload();
                    },
                    onError: function(result){
                        alert("Maaf, sesi pembayaran telah berakhir atau dibatalkan.");
                        sessionStorage.removeItem('pending_snap_token');
                        sessionStorage.removeItem('pending_order_id');
                        resumeBar.style.display = 'none';
                        window.location.reload();
                    },
                    onClose: function(){
                        alert('Anda menutup popup. Pembayaran tetap tertunda.');
                    }
                });
            } else {
                alert("Data pembayaran tidak valid. Halaman akan dimuat ulang.");
                sessionStorage.removeItem('pending_snap_token');
                sessionStorage.removeItem('pending_order_id');
                window.location.reload();
            }
        });
    }
});
</script>
</body>
</html>