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
    
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="YOUR_CLIENT_KEY"></script>

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

<script src="js/payment.js"></script>

</body>
</html>