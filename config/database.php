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

// --- KONESI DATABASE (MySQLi) ---
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


/* =========================================
   (BARU) FUNGSI MANAJEMEN MENU
=========================================
*/

/**
 * Mengambil semua produk beserta variannya dari database.
 * (DIPERBARUI) Menghapus 'product_code' dari query dan array.
 *
 * @param mysqli $db Objek koneksi database
 * @return array Daftar produk, masing-masing dengan array 'variants'
 */
function getAllProductsWithVariants($db) {
    $sql = "SELECT p.*, pv.variant_id, pv.variant_name, pv.price 
            FROM products p 
            LEFT JOIN product_variants pv ON p.product_id = pv.product_id 
            ORDER BY p.product_id, pv.variant_id";
    $result = $db->query($sql);
    if (!$result) return [];
    
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $product_id = $row['product_id'];
        if (!isset($products[$product_id])) {
            $products[$product_id] = [
                'product_id' => $row['product_id'],
                'name' => $row['name'],
                'description' => $row['description'],
                'category' => $row['category'],
                'image_url' => $row['image_url'],
                'variants' => []
            ];
        }
        if ($row['variant_id']) { // Hanya tambahkan varian jika ada (karena LEFT JOIN)
            $products[$product_id]['variants'][] = [
                'variant_id' => $row['variant_id'],
                'name' => $row['variant_name'],
                'price' => $row['price']
                // 'code' Dihapus dari sini
            ];
        }
    }
    return $products;
}

/**
 * (FUNGSI BARU) Mengambil semua kategori unik dari tabel products.
 *
 * @param mysqli $db
 * @return array Daftar kategori
 */
function getAllCategories($db) {
    $sql = "SELECT DISTINCT category FROM products ORDER BY category ASC";
    $result = $db->query($sql);
    if (!$result) return [];
    // Menggunakan fetch_all untuk mendapatkan semua baris sebagai array
    return $result->fetch_all(MYSQLI_ASSOC);
}


/**
 * Menyimpan produk baru ke tabel 'products'.
 *
 * @param mysqli $db
 * @param string $name
 * @param string $description
 * @param string $category
 * @param string|null $image_url
 * @return int|false ID produk baru, atau false jika gagal.
 */
function createProduct($db, $name, $description, $category, $image_url) {
    $stmt = $db->prepare("INSERT INTO products (name, description, category, image_url) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $description, $category, $image_url);
    if ($stmt->execute()) {
        return $db->insert_id;
    }
    return false;
}

/**
 * Menyimpan varian baru ke tabel 'product_variants'.
 * (DIPERBARUI) Menghapus 'product_code' dari parameter, query, dan bind.
 *
 * @param mysqli $db
 * @param int $product_id
 * @param string $variant_name
 * @param float $price
 * @return bool True jika berhasil
 */
function createProductVariant($db, $product_id, $variant_name, $price) {
    $stmt = $db->prepare("INSERT INTO product_variants (product_id, variant_name, price) VALUES (?, ?, ?)");
    $stmt->bind_param("isd", $product_id, $variant_name, $price);
    return $stmt->execute();
}

?>