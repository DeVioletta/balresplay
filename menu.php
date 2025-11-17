<?php
// 1. Sertakan file konfigurasi & mulai sesi
require_once __DIR__ . '/config/database.php';
startSecureSession();

// 2. Ambil semua data produk dari database
$products_list = getAllProductsWithVariants($db);

// 3. Kelompokkan produk berdasarkan kategori
$categories = [];
foreach ($products_list as $product) {
    if (!isset($categories[$product['category']])) {
        $categories[$product['category']] = [];
    }
    $categories[$product['category']][] = $product;
}

// Daftar nomor meja
$table_numbers = range(1, 20);
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
    <style>
        .menu-item.unavailable { opacity: 0.6; pointer-events: none; }
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
        
        /* Style untuk status item di modal */
        .status-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 15px 0;
            border-bottom: 1px dashed var(--tertiary-color);
        }
        .status-item:last-child {
            border-bottom: none;
        }
        .status-item-info {
            flex-grow: 1;
        }
        .status-item-info h4 {
            margin: 0 0 5px 0;
            font-size: 1rem;
            color: var(--light-text);
        }
        .status-item-info small {
            color: var(--text-muted);
            font-style: italic;
        }
        .status-item-price {
            font-weight: 600;
            color: var(--accent-color);
            white-space: nowrap;
            margin-left: 15px;
        }
        .status-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--tertiary-color);
        }
        .status-header p {
            margin: 0;
            color: var(--text-muted);
        }
        .status-header strong {
            display: block;
            margin-top: 5px;
            font-size: 1.2rem;
            color: var(--accent-color);
        }
        .status-items {
            padding: 0 20px;
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
            <?php foreach ($categories as $category_name => $products): ?>
                <a href="#<?php echo htmlspecialchars($category_name); ?>" class="category-link">
                    <?php echo ucfirst(str_replace('-', ' ', $category_name)); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php foreach ($categories as $category_name => $products): ?>
<section id="<?php echo htmlspecialchars($category_name); ?>" class="menu-section section-padding">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php echo ucfirst(str_replace('-', ' ', $category_name)); ?></h2>
        </div>
        <div class="menu-grid">
            
            <?php foreach ($products as $product): ?>
                <?php
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
                    $image_url = !empty($product['image_url']) 
                        ? htmlspecialchars($product['image_url']) 
                        : 'https://placehold.co/300x300/e8e4d8/5c6e58?text=' . urlencode($product['name']);
                    
                    $first_variant = $product['variants'][0] ?? null;
                    $is_simple_product = (count($product['variants']) == 1 && ($first_variant['name'] == null || $first_variant['name'] == ''));
                ?>

                <div class="menu-item <?php echo $all_variants_unavailable ? 'unavailable' : ''; ?>" 
                     data-id="<?php echo $product['product_id']; ?>" 
                     data-name="<?php echo htmlspecialchars($product['name']); ?>" 
                     data-img="<?php echo $image_url; ?>"
                     <?php if ($is_simple_product && $first_variant): ?>
                        data-price="<?php echo $first_variant['price']; ?>"
                        data-variant-id="<?php echo $first_variant['variant_id']; ?>"
                     <?php endif; ?>
                     >
                    
                    <div class="item-image">
                        <?php if ($all_variants_unavailable): ?>
                            <div class="badge-habis">HABIS</div>
                        <?php endif; ?>
                        <img src="<?php echo $image_url; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </div>

                    <div class="item-info">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p><?php echo htmlspecialchars($product['description']); ?></p>

                        <?php if (!$is_simple_product && !empty($product['variants'])): ?>
                        <div class="item-variants">
                            <?php foreach ($product['variants'] as $index => $variant): ?>
                                <label>
                                    <input type="radio" 
                                           name="variant_<?php echo $product['product_id']; ?>" 
                                           value="<?php echo htmlspecialchars($variant['name']); ?>"
                                           data-price="<?php echo $variant['price']; ?>"
                                           data-variant-id="<?php echo $variant['variant_id']; ?>"
                                           <?php echo $index == 0 ? 'checked' : ''; ?>
                                           <?php echo $variant['is_available'] == 0 ? 'disabled' : ''; ?>>
                                    <?php echo htmlspecialchars($variant['name']); ?>
                                    <?php echo $variant['is_available'] == 0 ? '(Habis)' : ''; ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>

                        <div class="item-meta">
                            <span class="item-price"><?php echo ($first_variant ? $first_variant['price'] / 1000 : '0'); ?>k</span>
                            <button class="btn-add <?php echo $all_variants_unavailable ? 'disabled' : ''; ?>" <?php echo $all_variants_unavailable ? 'disabled' : ''; ?>>
                                <?php echo $all_variants_unavailable ? 'Habis' : 'Tambah'; ?>
                            </button>
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

<script>
document.addEventListener('DOMContentLoaded', () => {
    // === ELEMEN ===
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
    
    const urlParams = new URLSearchParams(window.location.search);
    let currentTableNumber = urlParams.get('meja') || '1'; 
    
    tableNumberSelect.value = currentTableNumber;
    sessionStorage.setItem('tableNumber', currentTableNumber);
    
    tableNumberSelect.addEventListener('change', (e) => {
        currentTableNumber = e.target.value;
        sessionStorage.setItem('tableNumber', currentTableNumber);
        
        const newUrl = `${window.location.pathname}?meja=${currentTableNumber}`;
        window.history.pushState({path: newUrl}, '', newUrl);
        
        // Memulai polling ulang saat nomor meja berubah
        checkOrderStatus(); 
        startPolling(); 
    });
    
    // === FUNGSI KERANJANG ===
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
            if (!variant_id) { return; }
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
                priceElement.textContent = `${newPrice / 1000}k`;
            }
        });
    });
    document.querySelectorAll('.menu-item').forEach(menuItem => {
        const variantInput = menuItem.querySelector('.item-variants input[type="radio"]:checked');
        if (variantInput) {
             const priceElement = menuItem.querySelector('.item-price');
             const newPrice = parseFloat(variantInput.dataset.price);
             priceElement.textContent = `${newPrice / 1000}k`;
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
            // Mengembalikan alert keranjang kosong dengan konteks BalResplay
            alert('BalResplay: Keranjang Anda kosong! Silakan tambahkan item untuk memesan.');
        }
    });

    // === LOGIKA STATUS PESANAN ===
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
    
    window.updateOrderStatusView = function() {
        let orderStatusKey = `orderStatusData_MEJA_${currentTableNumber}`;
        let currentOrderData = JSON.parse(sessionStorage.getItem(orderStatusKey));
        
        console.log('Update Order Status View:', currentOrderData);
        
        if (!currentOrderData || !currentOrderData.items || currentOrderData.items.length === 0) {
            orderStatusDetailsContainer.innerHTML = `
                <div class="cart-empty-message">
                    <i class="fas fa-receipt"></i>
                    <p>Tidak ada pesanan aktif untuk meja ini.</p>
                </div>`;
            orderStatusIcon.style.display = 'none';
            return;
        }
        
        // TAMPILKAN ikon status karena ada pesanan aktif
        orderStatusIcon.style.display = 'flex';
        
        let itemsHTML = currentOrderData.items.map(item => {
            const variantHTML = (item.variant && item.variant.trim() !== "") ? ` (${htmlspecialchars(item.variant)})` : '';
            const itemName = item.product_name || item.name; 
            
            let itemPrice = 0;
            if (item.subtotal !== undefined) { 
                itemPrice = parseFloat(item.subtotal);
            } else if (item.price_per_item !== undefined && item.quantity !== undefined) {
                itemPrice = parseFloat(item.price_per_item) * parseInt(item.quantity);
            } else if (item.price !== undefined && item.quantity !== undefined) { 
                itemPrice = parseFloat(item.price) * parseInt(item.quantity);
            }
            
            itemPrice = isNaN(itemPrice) ? 0 : itemPrice;
            
            return `
            <div class="status-item">
                <div class="status-item-info">
                    <h4>${item.quantity}x ${htmlspecialchars(itemName)}${variantHTML}</h4>
                </div>
                <span class="status-item-price">Rp ${itemPrice.toLocaleString('id-ID')}</span>
            </div>
        `}).join('');
        
        const mainNotesHTML = (currentOrderData.notes && currentOrderData.notes.trim() !== "")
            ? `<div class="status-item"><small><strong>Catatan:</strong> ${htmlspecialchars(currentOrderData.notes)}</small></div>`
            : '';
            
        const totalPrice = currentOrderData.total_price ? 
            `Rp ${parseFloat(currentOrderData.total_price).toLocaleString('id-ID')}` : '';
            
        orderStatusDetailsContainer.innerHTML = `
            <div class="status-header">
                <p>Status Pesanan (Meja ${currentTableNumber}):</p>
                <strong>${htmlspecialchars(currentOrderData.status)}</strong>
                ${totalPrice ? `<div style="margin-top: 10px; border-top: 1px solid var(--tertiary-color); padding-top: 10px;">
                    <strong>Total: ${totalPrice}</strong>
                </div>` : ''}
            </div>
            <div class="status-items">
                ${itemsHTML}
                ${mainNotesHTML}
            </div>
        `;
    };

    let pollingInterval;
    function startPolling() {
        if (pollingInterval) clearInterval(pollingInterval);
        
        const poll = () => {
            const cacheBuster = `&_=${new Date().getTime()}`;
            
            fetch(`actions/get_order_status.php?meja=${currentTableNumber}${cacheBuster}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Polling Response:', data);
                    
                    let orderStatusKey = `orderStatusData_MEJA_${currentTableNumber}`;
                    let previousData = JSON.parse(sessionStorage.getItem(orderStatusKey));
                    
                    if (data.status === 'found') {
                        orderStatusIcon.style.display = 'flex';
                        sessionStorage.setItem(orderStatusKey, JSON.stringify(data.order));
                        
                        if (orderStatusModal.classList.contains('show')) {
                            updateOrderStatusView();
                        }
                        
                        // Mengembalikan alert saat status berubah
                        if (previousData && previousData.status !== data.order.status) {
                             alert(`BalResplay: Status pesanan Anda (Meja ${currentTableNumber}) telah berubah menjadi: ${data.order.status}`);
                        }
                        
                    } else if (data.status === 'empty') {
                        orderStatusIcon.style.display = 'none';
                        sessionStorage.removeItem(orderStatusKey);
                        if (orderStatusModal.classList.contains('show')) {
                            updateOrderStatusView();
                        }
                    }
                })
                .catch(error => {
                    console.warn('Gagal polling status:', error);
                    let orderStatusKey = `orderStatusData_MEJA_${currentTableNumber}`;
                    let currentOrderData = JSON.parse(sessionStorage.getItem(orderStatusKey));
                    if (currentOrderData && currentOrderData.items && currentOrderData.items.length > 0) {
                        orderStatusIcon.style.display = 'flex';
                    } else {
                        orderStatusIcon.style.display = 'none';
                    }
                });
        };
        
        poll(); 
        pollingInterval = setInterval(poll, 5000);
    }

    function initializeOrderStatusIcon() {
        let orderStatusKey = `orderStatusData_MEJA_${currentTableNumber}`;
        let currentOrderData = JSON.parse(sessionStorage.getItem(orderStatusKey));
        
        console.log('Initialize Order Status:', currentOrderData);
        
        if (currentOrderData && currentOrderData.items && currentOrderData.items.length > 0) {
            orderStatusIcon.style.display = 'flex';
        } else {
            orderStatusIcon.style.display = 'none';
        }
    }

    function checkForNewOrder() {
        const urlParams = new URLSearchParams(window.location.search);
        const orderSuccess = urlParams.get('order');
        const orderId = urlParams.get('id');
        
        if (orderSuccess === 'success' && orderId) {
            console.log('New order detected:', orderId);
            startPolling();
            
            // Mengembalikan alert notifikasi pesanan berhasil
            alert('BalResplay: Pesanan Anda berhasil dikonfirmasi! Silakan cek status pesanan Anda.');
            
            // Hapus parameter URL agar tidak memicu inisialisasi status berulang
            const newUrl = window.location.pathname + '?meja=' + currentTableNumber;
            window.history.replaceState({}, '', newUrl);
        }
    }

    // Panggil fungsi inisialisasi
    updateCart();
    initializeOrderStatusIcon();
    checkForNewOrder();
    startPolling();
});
</script>
</body>
</html>