<?php
/**
 * File: update_order_status.php
 * Deskripsi: API untuk memperbarui status pesanan dari Admin/Kasir.
 * Fungsi: Memproses perubahan status dan memvalidasi aturan khusus (misal: QRIS).
 */

require_once __DIR__ . '/../config/database.php';
startSecureSession();
redirectIfNotLoggedIn('../admin_login.php');

header('Content-Type: application/json');

// 1. Validasi Role
if ($_SESSION['role'] !== 'Kasir' && $_SESSION['role'] !== 'Super Admin') {
    echo json_encode(['status' => 'error', 'message' => 'Anda tidak memiliki izin.']);
    exit();
}

// 2. Ambil data JSON dari request body
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

if ($data && isset($data['order_id']) && isset($data['status'])) {
    
    $order_id = (int)$data['order_id'];
    $new_status = $data['status'];

    $allowed_statuses = [
        'Menunggu Pembayaran', 'Kirim ke Dapur', 'Sedang Dimasak', 
        'Siap Diantar', 'Selesai', 'Dibatalkan'
    ];
    
    if (!in_array($new_status, $allowed_statuses)) {
        echo json_encode(['status' => 'error', 'message' => 'Status tidak valid.']);
        exit();
    }

    // 3. Pengecekan Keamanan (Security Check)
    // Cek detail pesanan saat ini untuk validasi aturan bisnis
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

    // -- ATURAN BLOKIR QRIS --
    // Jika metode bayar QRIS dan status masih 'Menunggu Pembayaran',
    // staf TIDAK BOLEH mengubah ke 'Kirim ke Dapur' secara manual.
    // Hal ini karena konfirmasi harus murni dari sistem Midtrans (otomatis).
    // Kecuali jika staf ingin membatalkan pesanan.
    if ($payment_method === 'QRIS' && $current_status === 'Menunggu Pembayaran') {
        if ($new_status === 'Kirim ke Dapur') {
            echo json_encode([
                'status' => 'error', 
                'message' => 'Pesanan QRIS hanya bisa dikonfirmasi otomatis oleh sistem Midtrans. Tunggu notifikasi masuk.'
            ]);
            exit();
        }
        
        if ($new_status !== 'Dibatalkan' && $new_status !== 'Menunggu Pembayaran') {
             echo json_encode([
                'status' => 'error', 
                'message' => 'Pembayaran QRIS belum lunas. Tidak bisa mengubah status.'
            ]);
            exit();
        }
    }

    // 4. Update Status Database
    $sql = "UPDATE orders SET status = ?";
    
    // Update timestamp terkait sesuai status baru
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