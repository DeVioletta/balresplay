<?php
/* =========================================
   FILE KONFIGURASI DATABASE & FUNGSI GLOBAL
=========================================
*/

// --- PENGATURAN DATABASE ---
// (Sesuaikan dengan kredensial database Anda)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'balresplay'); // Ganti dengan nama database Anda

// --- KONEKSI DATABASE (MySQLi) ---
$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Cek koneksi
if ($db->connect_errno) {
    die("Koneksi database gagal: " . $db->connect_error);
}

/* =========================================
   FUNGSI KEAMANAN & SESI
=========================================
*/

/**
 * Memulai sesi (session) dengan pengaturan yang aman.
 * Wajib dipanggil di baris paling atas setiap halaman yang butuh sesi.
 */
function startSecureSession() {
    $cookieParams = session_get_cookie_params();
    
    session_set_cookie_params(
        $cookieParams["lifetime"],
        $cookieParams["path"],
        $cookieParams["domain"],
        false, // Set ke TRUE jika Anda menggunakan HTTPS
        true     // true = httponly, mencegah akses via JavaScript
    );
    
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Mengecek apakah pengguna saat ini sudah login.
 * @return bool True jika sudah login, false jika belum.
 */
function isLoggedIn() {
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        return true;
    }
    return false;
}

/**
 * "Penjaga Gerbang" untuk halaman admin.
 * Jika pengguna belum login, akan dialihkan ke halaman login.
 *
 * @param string $redirectUrl Halaman tujuan jika belum login (cth: 'admin_login.php')
 */
function redirectIfNotLoggedIn($redirectUrl) {
    if (!isLoggedIn()) {
        header("Location: " . $redirectUrl);
        exit(); // Hentikan eksekusi skrip
    }
}

/* =========================================
   FUNGSI MANAJEMEN PENGGUNA (ADMIN)
=========================================
*/

/**
 * Mengambil data admin berdasarkan username dari tabel admin_users.
 *
 * @param mysqli $db Objek koneksi database
 * @param string $username Username yang dicari
 * @return array|null Data pengguna jika ditemukan, atau null jika tidak.
 */
function getAdminUserByUsername($db, $username) {
    $stmt = $db->prepare("SELECT * FROM admin_users WHERE username = ? LIMIT 1");
    if (!$stmt) {
        // Handle error, misalnya:
        // error_log("Prepare statement failed: " . $db->error);
        return null;
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

/**
 * (BARU) Mengambil SEMUA data admin dari database.
 *
 * @param mysqli $db Objek koneksi database
 * @return mysqli_result|false Hasil query
 */
function getAllAdminUsers($db) {
    // Ambil semua kecuali Super Admin, atau sesuaikan kebutuhan
    // Di sini kita ambil semua
    $result = $db->query("SELECT user_id, username, role, status FROM admin_users");
    return $result;
}

/**
 * (BARU) Membuat admin user baru.
 * Status default diatur ke 0 (inactive) sesuai permintaan.
 *
 * @param mysqli $db Objek koneksi database
 * @param string $username
 * @param string $password_hash
 * @param string $role
 * @return bool True jika berhasil, false jika gagal.
 */
function createAdminUser($db, $username, $password_hash, $role) {
    $stmt = $db->prepare("INSERT INTO admin_users (username, password_hash, role, status) VALUES (?, ?, ?, 0)");
    $stmt->bind_param("sss", $username, $password_hash, $role);
    return $stmt->execute();
}

/**
 * (BARU) Memperbarui status pengguna (misal: 0 menjadi 1 saat login).
 *
 * @param mysqli $db Objek koneksi database
 * @param int $user_id
 * @param int $status (0 or 1)
 * @return bool True jika berhasil
 */
function updateUserStatus($db, $user_id, $status) {
    $stmt = $db->prepare("UPDATE admin_users SET status = ? WHERE user_id = ?");
    $stmt->bind_param("ii", $status, $user_id);
    return $stmt->execute();
}

/**
 * (BARU) Menghapus admin user berdasarkan ID.
 *
 * @param mysqli $db Objek koneksi database
 * @param int $user_id
 * @return bool True jika berhasil
 */
function deleteAdminUser($db, $user_id) {
    $stmt = $db->prepare("DELETE FROM admin_users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    return $stmt->execute();
}

?>