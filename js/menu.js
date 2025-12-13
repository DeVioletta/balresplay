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
    
    // Logic Nomor Meja (Hanya untuk Pre-fill)
    const urlParams = new URLSearchParams(window.location.search);
    let currentTableNumber = urlParams.get('meja') || '1'; 
    
    if (tableNumberSelect) {
        tableNumberSelect.value = currentTableNumber;
        sessionStorage.setItem('tableNumber', currentTableNumber);
        
        tableNumberSelect.addEventListener('change', (e) => {
            currentTableNumber = e.target.value;
            sessionStorage.setItem('tableNumber', currentTableNumber);
            const newUrl = `${window.location.pathname}?meja=${currentTableNumber}`;
            window.history.pushState({path: newUrl}, '', newUrl);
        });
    }

    const openModal = () => cartModal.classList.add('show');
    const closeModal = () => cartModal.classList.remove('show');
    if (cartIcon) cartIcon.addEventListener('click', openModal);
    if (cartClose) cartClose.addEventListener('click', closeModal);
    if (continueShoppingBtn) continueShoppingBtn.addEventListener('click', closeModal);
    if (cartModal) cartModal.addEventListener('click', (e) => {
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
        if (!cartItemsContainer) return;
        cartItemsContainer.innerHTML = '';
        let totalItems = 0;
        totalPrice = 0;
        if (cart.length === 0) {
            cartItemsContainer.innerHTML = `<div class="cart-empty-message"><i class="fas fa-shopping-cart"></i><p>Keranjang Anda masih kosong.</p></div>`;
            if (cartFooter) cartFooter.style.display = 'none';
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
            if (cartFooter) cartFooter.style.display = 'block';
        }
        if (cartCountElement) cartCountElement.textContent = totalItems;
        if (cartTotalPriceElement) cartTotalPriceElement.textContent = `${(totalPrice / 1000).toLocaleString('id-ID')}k`;
    }

    if (cartItemsContainer) {
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
    }

    function htmlspecialchars(str) {
        if (typeof str !== 'string') return '';
        return str.replace(/[&<>"']/g, function(match) {
            const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' };
            return map[match];
        });
    }

    if (placeOrderBtn) {
        placeOrderBtn.addEventListener('click', () => {
            if (cart.length > 0) {
                sessionStorage.setItem('cartData', JSON.stringify(cart));
                sessionStorage.setItem('cartTotalPrice', totalPrice);
                window.location.href = 'payment.php'; 
            } else {
                alert('Keranjang Anda kosong! Silakan tambahkan item untuk memesan.');
            }
        });
    }

    const openStatusModal = () => {
        updateOrderStatusView();
        orderStatusModal.classList.add('show');
    };
    const closeStatusModal = () => orderStatusModal.classList.remove('show');
    if (orderStatusIcon) orderStatusIcon.addEventListener('click', openStatusModal);
    if (statusClose) statusClose.addEventListener('click', closeStatusModal);
    if (orderStatusModal) {
        orderStatusModal.addEventListener('click', (e) => {
            if (e.target === orderStatusModal) closeStatusModal();
        });
    }
    
    const orderStatusKey = 'balresplay_my_orders'; 

    window.updateOrderStatusView = function() {
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
            
            fetch(`actions/get_order_status.php?${cacheBuster}`) 
                .then(response => response.json())
                .then(data => {
                    
                    if (data.status === 'found' && data.orders && data.orders.length > 0) { 
                        if (orderStatusIcon) orderStatusIcon.style.display = 'flex';
                        sessionStorage.setItem(orderStatusKey, JSON.stringify(data.orders)); 
                        if (orderStatusModal && orderStatusModal.classList.contains('show')) {
                            updateOrderStatusView();
                        }
                    } else if (data.status === 'empty') { 
                        if (orderStatusIcon) orderStatusIcon.style.display = 'none';
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

    if (pendingToken && pendingOrderId && resumeBar) {
        
        fetch(`actions/validate_pending_order.php?order_id=${pendingOrderId}`)
            .then(response => response.json())
            .then(data => {
                if (data.valid) {
                    if (resumeOrderIdSpan) resumeOrderIdSpan.textContent = pendingOrderId;
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
                        if (resumeBar) resumeBar.style.display = 'none';
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
                        if (resumeBar) resumeBar.style.display = 'none';
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