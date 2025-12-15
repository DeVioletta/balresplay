<?php
/**
 * File: update_kitchen_status.php
 * Deskripsi: API untuk memperbarui status pesanan dari tampilan Dapur (Kitchen Display).
 */

require_once __DIR__ . '/../config/database.php';
startSecureSession();
redirectIfNotLoggedIn('../admin_login.php');

header('Content-Type: application/json');

// Validasi Role: Dapur atau Super Admin
if ($_SESSION['role'] !== 'Dapur' && $_SESSION['role'] !== 'Super Admin') {
    echo json_encode(['status' => 'error', 'message' => 'Anda tidak memiliki izin.']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id']) && isset($_POST['status'])) {
    
    $order_id = (int)$_POST['order_id'];
    $new_status = $_POST['status'];

    // Validasi status yang diizinkan untuk alur dapur
    $allowed_statuses = ['Sedang Dimasak', 'Siap Diantar'];
    if (!in_array($new_status, $allowed_statuses)) {
        echo json_encode(['status' => 'error', 'message' => 'Status tidak valid.']);
        exit();
    }

    // Tentukan query dan timestamp yang diupdate berdasarkan status
    $sql = "";
    if ($new_status == 'Sedang Dimasak') {
        $sql = "UPDATE orders SET status = ?, started_cooking_at = NOW() WHERE order_id = ?";
    } else if ($new_status == 'Siap Diantar') {
        $sql = "UPDATE orders SET status = ?, completed_at = NOW() WHERE order_id = ?";
    }

    if (empty($sql)) {
         echo json_encode(['status' => 'error', 'message' => 'Query gagal disiapkan.']);
         exit();
    }

    // Eksekusi Update
    $stmt = $db->prepare($sql);
    $stmt->bind_param("si", $new_status, $order_id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Status berhasil diperbarui.']);
        } else {
            // Jika tidak ada baris yang berubah (misal status sudah sama)
            echo json_encode(['status' => 'error', 'message' => 'Pesanan tidak ditemukan atau status sudah diperbarui.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui database.']);
    }

    $stmt->close();
    $db->close();

} else {
    echo json_encode(['status' => 'error', 'message' => 'Request tidak valid.']);
    exit();
}
?>