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

?>