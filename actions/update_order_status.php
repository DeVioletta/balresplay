<?php
require_once __DIR__ . '/../config/database.php';
startSecureSession();
redirectIfNotLoggedIn('../admin_login.php');

// Set header respons ke JSON
header('Content-Type: application/json');

// 1. Role Check: Hanya 'Kasir' dan 'Super Admin' yang boleh update status ini
if ($_SESSION['role'] !== 'Kasir' && $_SESSION['role'] !== 'Super Admin') {
    echo json_encode(['status' => 'error', 'message' => 'Anda tidak memiliki izin.']);
    exit();
}

// 2. Cek jika ini adalah request POST dan data ada
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

if ($data && isset($data['order_id']) && isset($data['status'])) {
    
    $order_id = (int)$data['order_id'];
    $new_status = $data['status'];

    // 3. Validasi status
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

    // 4. Siapkan query UPDATE dengan timestamp yang relevan
    $sql = "UPDATE orders SET status = ?";
    
    // Tambahkan timestamp yang relevan berdasarkan status baru
    if ($new_status == 'Kirim ke Dapur') {
        $sql .= ", confirmed_at = NOW()";
    } else if ($new_status == 'Sedang Dimasak') {
        $sql .= ", started_cooking_at = NOW()";
    } else if ($new_status == 'Siap Diantar') {
        // Kolom ini ada di DB Anda
        $sql .= ", completed_at = NOW()"; 
    } else if ($new_status == 'Selesai') {
        // PERBAIKAN: Kolom delivered_at TIDAK ADA di DB Anda.
        // Karena completed_at sudah diisi di Siap Diantar, kita biarkan status Selesai hanya update statusnya saja.
        // Jika Anda ingin mencatat waktu penyelesaian, Anda HARUS menambah kolom baru di tabel orders.
        // Untuk saat ini, kita biarkan kosong.
    }
    
    $sql .= " WHERE order_id = ?";

    $stmt = $db->prepare($sql);
    $stmt->bind_param("si", $new_status, $order_id);

    // 5. Eksekusi query dan kirim respons
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Status berhasil diperbarui.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Pesanan tidak ditemukan atau status sudah sama.']);
        }
    } else {
        error_log("Database update failed: " . $stmt->error);
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui database.']);
    }

    $stmt->close();
    $db->close();

} else {
    // Jika bukan POST atau data tidak lengkap
    echo json_encode(['status' => 'error', 'message' => 'Request tidak valid atau data tidak lengkap.']);
    exit();
}
?>