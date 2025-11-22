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
    <style>
        /* (BARU) Style untuk tombol yang dinonaktifkan */
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
                                <strong>QRIS</strong>
                                <span>Bayar dengan QR Code</span>
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
                        <h4>Pembayaran QRIS</h4>
                        <img src="https://placehold.co/250x250/f5f5f5/0f0f0f?text=QRIS+Dinamis" alt="QR Code" class="qris-image">
                        <p>Silakan pindai kode QR di atas menggunakan aplikasi pembayaran favorit Anda.</p>
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
    // (Logika Bagian 1 & 2 - Memuat keranjang & Opsi Pembayaran)
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

    // === LOGIKA KONFIRMASI PESANAN (DIUBAH) ===
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
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const tableNumForRedirect = sessionStorage.getItem('tableNumber') || '1';
                    
                    // (DIUBAH) Logika ini sekarang MENAMBAHKAN ke array, bukan MENIMPA
                    const orderStatusKey = `orderStatusData_MEJA_${tableNumForRedirect}`;
                    
                    // 1. Buat data untuk pesanan baru ini
                    const temporaryOrderData = {
                        order_id: data.order_id,
                        status: 'Menunggu Pembayaran',
                        items: currentCartData.map(item => ({
                            product_name: item.name,
                            variant: item.variant,
                            quantity: parseInt(item.quantity),
                            price_per_item: parseFloat(item.price), 
                            subtotal: parseFloat(item.price) * parseInt(item.quantity), 
                            notes: item.notes || '' // (Tambahkan catatan per item)
                        })),
                        total_price: grandTotal, 
                        // (DIUBAH) Gabungkan catatan dari payload asli
                        notes: payload.cartData.map(item => 
                            item.notes ? `${item.name}: ${item.notes}` : ''
                        ).filter(note => note).join('; ')
                    };

                    // 2. Ambil array pesanan yang sudah ada (jika ada)
                    let existingOrders = JSON.parse(sessionStorage.getItem(orderStatusKey)) || [];
                    if (!Array.isArray(existingOrders)) {
                        existingOrders = []; // Pastikan itu array
                    }

                    // 3. Tambahkan pesanan baru ke array
                    existingOrders.push(temporaryOrderData);
                    
                    // 4. Simpan kembali array yang sudah diperbarui
                    sessionStorage.setItem(orderStatusKey, JSON.stringify(existingOrders));
                    
                    sessionStorage.removeItem('cartData');
                    sessionStorage.removeItem('cartTotalPrice');

                    // Redirect dengan parameter sukses
                    window.location.href = `menu.php?meja=${tableNumForRedirect}&order=success&id=${data.order_id}`;

                } else {
                    // Mengembalikan alert kegagalan konfirmasi
                    alert('BalResplay: Gagal membuat pesanan: ' + data.message);
                    confirmOrderBtn.disabled = false;
                    confirmOrderBtn.textContent = 'Konfirmasi Pesanan';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Mengembalikan alert koneksi bermasalah
                alert('BalResplay: Terjadi kesalahan koneksi. Silakan coba lagi.');
                confirmOrderBtn.disabled = false;
                confirmOrderBtn.textContent = 'Konfirmasi Pesanan';
            });
        } else {
            // Mengembalikan alert keranjang kosong
            alert('BalResplay: Tidak ada item untuk dikonfirmasi.');
            confirmOrderBtn.disabled = false;
            confirmOrderBtn.textContent = 'Konfirmasi Pesanan';
            window.location.href = 'menu.php';
        }
    });
});
</script>

</body>
</html>