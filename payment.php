<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BalResplay | Pembayaran</title>
    <link rel="stylesheet" href="css/variable.css">
    <link rel="stylesheet" href="css/pembayaran.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600;700&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="CLIENT_KEY"></script>

    <style>
        .btn-confirm-order:disabled {
            background-color: var(--tertiary-color);
            cursor: not-allowed;
            opacity: 0.7;
        }
    </style>
</head>

<body>

    <?php include 'includes/header.php'; ?>

    <main class="payment-container">
        <div class="payment-header">
            <h1>Konfirmasi & Pembayaran</h1>
            <p>Periksa kembali pesanan Anda dan pilih metode pembayaran.</p>
        </div>

        <div class="payment-content">
            <div class="order-summary-card">
                <h3>Ringkasan Pesanan</h3>
                <div class="summary-details">
                    <div class="summary-item">
                        <span>Nomor Meja</span>
                        <strong id="summary-table-number">...</strong> </div>
                </div>
                <div class="summary-items-list" id="summary-items-list">
                    <p>Memuat pesanan...</p>
                </div>
                <div class="summary-total">
                    <div class="summary-item">
                        <span>Subtotal</span>
                        <span id="summary-subtotal">Rp 0</span>
                    </div>
                    <div class="summary-item">
                        <span>Biaya Layanan</span>
                        <span>Rp 2.000</span> </div>
                    <div class="summary-item grand-total">
                        <span>Total Pembayaran</span>
                        <strong id="summary-grand-total">Rp 0</strong>
                    </div>
                </div>
            </div>

            <div class="payment-method-card">
                <h3>Pilih Metode Pembayaran</h3>
                <div class="payment-options">
                    <label for="payment-qris" class="payment-option">
                        <input type="radio" id="payment-qris" name="payment_method" value="qris" checked>
                        <div class="payment-option-content">
                            <i class="fas fa-qrcode"></i>
                            <div class="payment-option-text">
                                <strong>QRIS / E-Wallet</strong>
                                <span>Scan QR Code</span>
                            </div>
                        </div>
                    </label>
                    <label for="payment-cash" class="payment-option">
                        <input type="radio" id="payment-cash" name="payment_method" value="cash">
                        <div class="payment-option-content">
                            <i class="fas fa-money-bill-wave"></i>
                            <div class="payment-option-text">
                                <strong>Tunai (Cash)</strong>
                                <span>Bayar di kasir</span>
                            </div>
                        </div>
                    </label>
                </div>
                
                <div class="payment-instructions">
                    <div id="qris-content" class="instruction-content active">
                        <h4>Pembayaran Online</h4>
                        <p style="text-align:center; padding: 20px;">
                            <i class="fas fa-mobile-alt" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 10px;"></i><br>
                            Klik "Konfirmasi Pesanan" untuk memunculkan instruksi pembayaran (QRIS/Transfer/E-Wallet).
                        </p>
                    </div>
                    <div id="cash-content" class="instruction-content">
                         <h4>Pembayaran Tunai</h4>
                         <p>Silakan tunjukkan halaman ini dan lakukan pembayaran langsung di kasir.</p>
                    </div>
                </div>

                 <button class="btn btn-primary btn-confirm-order" id="confirm-order-btn">
                    Konfirmasi Pesanan
                </button>
            </div>
        </div>

        <div class="back-to-menu">
            <a href="menu.php"><i class="fas fa-arrow-left"></i> Kembali ke Menu</a>
        </div>
    </main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // --- SETUP AWAL (TIDAK BERUBAH) ---
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
                // Pastikan nilai dikirim sesuai backend ('QRIS' atau 'Cash')
                paymentMethod: selectedPaymentMethod === 'qris' ? 'QRIS' : 'Cash' 
            };

            // ... kode sebelumnya (payload definition) ...

            fetch('actions/handle_order.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            // [MODIFIKASI DEBUG] Ubah cara handle respon untuk cek error PHP
            .then(response => {
                return response.text().then(text => {
                    try {
                        return JSON.parse(text); // Coba ubah teks jadi JSON
                    } catch (err) {
                        // Jika gagal, berarti server mengirim Error HTML/Text
                        console.error('Server Error:', text);
                        throw new Error('Respon Server Bukan JSON:\n' + text.substring(0, 300)); // Ambil 300 huruf pertama error
                    }
                });
            })
            .then(data => {
                if (data.status === 'success') {
                    // [LOGIKA SUKSES]
                    if (data.snap_token) {
                        window.snap.pay(data.snap_token, {
                            onSuccess: function(result){
                                finalizeClientSideOrder(data.order_id, currentCartData, currentTableNumber, grandTotal, payload);
                            },
                            onPending: function(result){
                                alert("Menunggu pembayaran.");
                                finalizeClientSideOrder(data.order_id, currentCartData, currentTableNumber, grandTotal, payload);
                            },
                            onError: function(result){
                                alert("Pembayaran gagal!");
                                confirmOrderBtn.disabled = false;
                                confirmOrderBtn.textContent = 'Konfirmasi Pesanan';
                            },
                            onClose: function(){
                                alert('Anda menutup popup.');
                                confirmOrderBtn.disabled = false;
                                confirmOrderBtn.textContent = 'Konfirmasi Pesanan';
                            }
                        });
                    } else {
                        // Cash
                        alert('Pesanan berhasil dibuat. Silakan menuju kasir.');
                        finalizeClientSideOrder(data.order_id, currentCartData, currentTableNumber, grandTotal, payload);
                    }

                } else {
                    // Error dari logika PHP (JSON valid tapi status error)
                    alert('Gagal membuat pesanan: ' + data.message);
                    confirmOrderBtn.disabled = false;
                    confirmOrderBtn.textContent = 'Konfirmasi Pesanan';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Tampilkan error asli di Alert agar ketahuan penyebabnya
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
     * Fungsi Helper untuk menyimpan data ke SessionStorage (agar halaman Status bisa membacanya)
     * dan melakukan Redirect ke halaman menu/sukses.
     * Logika ini dipisahkan agar bisa dipanggil baik oleh Cash maupun Midtrans Success.
     */
    function finalizeClientSideOrder(orderId, cartData, tableNum, total, payload) {
        const orderStatusKey = `orderStatusData_MEJA_${tableNum}`;
        
        // 1. Format data pesanan untuk tampilan frontend
        const temporaryOrderData = {
            order_id: orderId,
            status: 'Menunggu Pembayaran', // Status awal frontend, nanti backend update otomatis
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
</script>

</body>
</html>