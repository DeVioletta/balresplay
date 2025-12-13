document.addEventListener('DOMContentLoaded', () => {
    // --- SETUP AWAL ---
    const cartData = JSON.parse(sessionStorage.getItem('cartData'));
    const totalPrice = sessionStorage.getItem('cartTotalPrice');
    const tableNumber = sessionStorage.getItem('tableNumber') || '1'; 
    const summaryItemsList = document.getElementById('summary-items-list');
    const summarySubtotal = document.getElementById('summary-subtotal');
    const summaryGrandTotal = document.getElementById('summary-grand-total');
    const summaryTableNumber = document.getElementById('summary-table-number');
    const serviceFee = 2000;
    
    let subtotal = 0;
    let grandTotal = serviceFee;
    
    if (cartData && cartData.length > 0) {
        subtotal = parseFloat(totalPrice);
        grandTotal = subtotal + serviceFee;
        
        summaryItemsList.innerHTML = ''; 
        cartData.forEach(item => {
            const itemElement = document.createElement('div');
            itemElement.classList.add('summary-product-item');
            let itemName = `${item.quantity}x ${item.name}`;
            if (item.variant) { itemName += ` (${item.variant})`; }
            itemElement.innerHTML = `
                <div class="product-name">
                    <span>${itemName}</span>
                    ${item.notes ? `<small class="product-notes">Catatan: ${item.notes}</small>` : ''}
                </div>
                <span class="product-price">Rp ${(item.price * item.quantity).toLocaleString('id-ID')}</span>
            `;
            summaryItemsList.appendChild(itemElement);
        });

        summaryTableNumber.textContent = tableNumber;
        summarySubtotal.textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
        summaryGrandTotal.textContent = `Rp ${grandTotal.toLocaleString('id-ID')}`;
    } else {
        summaryItemsList.innerHTML = '<p>Keranjang Anda kosong. Silakan kembali ke menu untuk memesan.</p>';
        document.getElementById('confirm-order-btn').disabled = true;
    }
    
    // Toggle Tampilan Instruksi
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const qrisContent = document.getElementById('qris-content');
    const cashContent = document.getElementById('cash-content');
    paymentRadios.forEach(radio => {
        radio.addEventListener('change', (e) => {
            if (e.target.value === 'qris') {
                qrisContent.classList.add('active');
                cashContent.classList.remove('active');
            } else {
                qrisContent.classList.remove('active');
                cashContent.classList.add('active');
            }
        });
    });


    // --- [INTEGRASI MIDTRANS 2] LOGIKA TOMBOL & POPUP ---
    const confirmOrderBtn = document.getElementById('confirm-order-btn');

    confirmOrderBtn.addEventListener('click', () => {
        confirmOrderBtn.disabled = true;
        confirmOrderBtn.textContent = 'Memproses...';

        const currentCartData = JSON.parse(sessionStorage.getItem('cartData'));
        const currentTableNumber = sessionStorage.getItem('tableNumber') || '1';
        const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
        
        if (currentCartData && currentCartData.length > 0) {
            
            const payload = {
                cartData: currentCartData,
                tableNumber: currentTableNumber,
                paymentMethod: selectedPaymentMethod === 'qris' ? 'QRIS' : 'Cash' 
            };

            fetch('actions/handle_order.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(response => {
                return response.text().then(text => {
                    try { return JSON.parse(text); } 
                    catch (err) { throw new Error('Respon Server Bukan JSON:\n' + text.substring(0, 300)); }
                });
            })
            .then(data => {
                if (data.status === 'success') {
                    
                    // A. JIKA ADA SNAP TOKEN (Metode QRIS/Online)
                    if (data.snap_token) {
                        // [LANGKAH 1] SIMPAN TOKEN KE SESSION STORAGE
                        sessionStorage.setItem('pending_snap_token', data.snap_token);
                        sessionStorage.setItem('pending_order_id', data.order_id);

                        window.snap.pay(data.snap_token, {
                            onSuccess: function(result){
                                // Pembayaran sukses -> Hapus token pending
                                sessionStorage.removeItem('pending_snap_token');
                                sessionStorage.removeItem('pending_order_id');
                                finalizeClientSideOrder(data.order_id, currentCartData, currentTableNumber, grandTotal, payload);
                            },
                            onPending: function(result){
                                alert("Menunggu pembayaran. Cek status di Menu.");
                                finalizeClientSideOrder(data.order_id, currentCartData, currentTableNumber, grandTotal, payload);
                            },
                            onError: function(result){
                                alert("Pembayaran gagal!");
                                confirmOrderBtn.disabled = false;
                                confirmOrderBtn.textContent = 'Konfirmasi Pesanan';
                            },
                            onClose: function(){
                                // [LANGKAH 2] REDIRECT KE MENU JIKA DI-CLOSE
                                alert('Anda menutup popup. Silakan lanjutkan pembayaran di halaman Menu.');
                                window.location.href = `menu.php?meja=${currentTableNumber}`;
                            }
                        });
                    } 
                    // B. CASH
                    else {
                        alert('Pesanan berhasil dibuat. Silakan menuju kasir.');
                        finalizeClientSideOrder(data.order_id, currentCartData, currentTableNumber, grandTotal, payload);
                    }

                } else {
                    alert('Gagal membuat pesanan: ' + data.message);
                    confirmOrderBtn.disabled = false;
                    confirmOrderBtn.textContent = 'Konfirmasi Pesanan';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('TERJADI ERROR:\n' + error.message);
                confirmOrderBtn.disabled = false;
                confirmOrderBtn.textContent = 'Konfirmasi Pesanan';
            });
        } else {
            alert('Tidak ada item untuk dikonfirmasi.');
            window.location.href = 'menu.php';
        }
    });

    /**
     * Fungsi Helper untuk menyimpan data ke SessionStorage
     */
    function finalizeClientSideOrder(orderId, cartData, tableNum, total, payload) {
        const orderStatusKey = `balresplay_my_orders`; 
        
        const temporaryOrderData = {
            order_id: orderId,
            status: 'Menunggu Pembayaran',
            table_number: tableNum, // Pastikan nomor meja tersimpan
            items: cartData.map(item => ({
                product_name: item.name,
                variant: item.variant,
                quantity: parseInt(item.quantity),
                price_per_item: parseFloat(item.price), 
                subtotal: parseFloat(item.price) * parseInt(item.quantity), 
                notes: item.notes || '' 
            })),
            total_price: total, 
            notes: payload.cartData.map(item => 
                item.notes ? `${item.name}: ${item.notes}` : ''
            ).filter(note => note).join('; ')
        };

        // 2. Simpan ke array session
        let existingOrders = JSON.parse(sessionStorage.getItem(orderStatusKey)) || [];
        if (!Array.isArray(existingOrders)) { existingOrders = []; }
        existingOrders.push(temporaryOrderData);
        sessionStorage.setItem(orderStatusKey, JSON.stringify(existingOrders));
        
        // 3. Bersihkan keranjang
        sessionStorage.removeItem('cartData');
        sessionStorage.removeItem('cartTotalPrice');

        // 4. Redirect
        window.location.href = `menu.php?meja=${tableNum}&order=success&id=${orderId}`;
    }
});