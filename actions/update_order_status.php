<?php
require_once __DIR__ . '/../config/database.php';
startSecureSession();
redirectIfNotLoggedIn('../admin_login.php');

// Set header respons ke JSON
header('Content-Type: application/json');

// 1. Role Check
if ($_SESSION['role'] !== 'Kasir' && $_SESSION['role'] !== 'Super Admin') {
    echo json_encode(['status' => 'error', 'message' => 'Anda tidak memiliki izin.']);
    exit();
}

// 2. Ambil data POST
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

if ($data && isset($data['order_id']) && isset($data['status'])) {
    
    $order_id = (int)$data['order_id'];
    $new_status = $data['status'];

    // 3. Validasi status yang diizinkan
    $allowed_statuses = [
        'Menunggu Pembayaran', 
        'Kirim ke Dapur', 
        'Sedang Dimasak', 
        'Siap Diantar', 
        'Selesai', 
        'Dibatalkan'
    ];
    
    if (!in_array($new_status, $allowed_statuses)) {
        echo json_encode(['status' => 'error', 'message' => 'Status tidak valid.']);
        exit();
    }

    // [PERBAIKAN UTAMA] Validasi Keamanan untuk QRIS
    // Cek status saat ini dan metode pembayaran di database
    $check_stmt = $db->prepare("SELECT status, payment_method FROM orders WHERE order_id = ?");
    $check_stmt->bind_param("i", $order_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Pesanan tidak ditemukan.']);
        exit();
    }

    $current_order = $result->fetch_assoc();
    $current_status = $current_order['status'];
    $payment_method = $current_order['payment_method'];
    $check_stmt->close();

    // LOGIKA BLOKIR: 
    // Jika Metode = QRIS, dan Status Sekarang = Menunggu Pembayaran
    // MAKA: Tidak boleh diubah ke 'Kirim ke Dapur' secara manual.
    // Hanya boleh diubah ke 'Dibatalkan' (jika customer tidak jadi bayar).
    if ($payment_method === 'QRIS' && $current_status === 'Menunggu Pembayaran') {
        if ($new_status === 'Kirim ke Dapur') {
            echo json_encode([
                'status' => 'error', 
                'message' => 'Pesanan QRIS hanya bisa dikonfirmasi otomatis oleh sistem Midtrans. Tunggu notifikasi masuk.'
            ]);
            exit();
        }
        // Jika status baru bukan 'Dibatalkan' dan bukan 'Menunggu Pembayaran', tolak juga
        if ($new_status !== 'Dibatalkan' && $new_status !== 'Menunggu Pembayaran') {
             echo json_encode([
                'status' => 'error', 
                'message' => 'Pembayaran QRIS belum lunas. Tidak bisa mengubah status.'
            ]);
            exit();
        }
    }

    // 4. Proses Update Database (Jika lolos validasi)
    $sql = "UPDATE orders SET status = ?";
    
    if ($new_status == 'Kirim ke Dapur') {
        $sql .= ", confirmed_at = NOW()";
    } else if ($new_status == 'Sedang Dimasak') {
        $sql .= ", started_cooking_at = NOW()";
    } else if ($new_status == 'Siap Diantar') {
        $sql .= ", completed_at = NOW()"; 
    } 
    
    $sql .= " WHERE order_id = ?";

    $stmt = $db->prepare($sql);
    $stmt->bind_param("si", $new_status, $order_id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Status berhasil diperbarui.']);
        } else {
            // Bisa terjadi jika status baru = status lama
            echo json_encode(['status' => 'success', 'message' => 'Status sudah sesuai.']);
        }
    } else {
        error_log("Database update failed: " . $stmt->error);
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui database.']);
    }

    $stmt->close();
    $db->close();

} else {
    echo json_encode(['status' => 'error', 'message' => 'Request tidak valid atau data tidak lengkap.']);
    exit();
}
?>