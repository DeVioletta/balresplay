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
 * (DIPERBARUI) Menambahkan 'is_available'.
 *
 * @param mysqli $db Objek koneksi database
 * @return array Daftar produk, masing-masing dengan array 'variants'
 */
function getAllProductsWithVariants($db) {
    $sql = "SELECT p.*, pv.variant_id, pv.variant_name, pv.price, pv.is_available 
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
                'price' => $row['price'],
                'is_available' => $row['is_available'] // Ditambahkan
            ];
        }
    }
    return $products;
}

/**
 * (FUNGSI BARU) Mengambil SATU produk dan variannya berdasarkan ID.
 *
 * @param mysqli $db
 * @param int $product_id
 * @return array|null Data produk tunggal atau null
 */
function getProductById($db, $product_id) {
    $stmt = $db->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) return null;
    
    $product = $result->fetch_assoc();
    $product['variants'] = [];
    
    $stmt_var = $db->prepare("SELECT * FROM product_variants WHERE product_id = ? ORDER BY variant_id");
    $stmt_var->bind_param("i", $product_id);
    $stmt_var->execute();
    $variants_result = $stmt_var->get_result();
    
    while ($row = $variants_result->fetch_assoc()) {
        $product['variants'][] = $row;
    }
    
    return $product;
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
 * (FUNGSI BARU) Memperbarui produk di tabel 'products'.
 *
 * @param mysqli $db
 * @param int $product_id
 * @param string $name
 * @param string $description
 * @param string $category
 * @param string|null $image_url
 * @return bool True jika berhasil
 */
function updateProduct($db, $product_id, $name, $description, $category, $image_url) {
    $stmt = $db->prepare("UPDATE products SET name = ?, description = ?, category = ?, image_url = ? WHERE product_id = ?");
    $stmt->bind_param("ssssi", $name, $description, $category, $image_url, $product_id);
    return $stmt->execute();
}


/**
 * Menyimpan varian baru ke tabel 'product_variants'.
 * (DIPERBARUI) Menambahkan 'is_available'.
 *
 * @param mysqli $db
 * @param int $product_id
 * @param string $variant_name
 * @param float $price
 * @param int $is_available (0 atau 1)
 * @return bool True jika berhasil
 */
function createProductVariant($db, $product_id, $variant_name, $price, $is_available) {
    $stmt = $db->prepare("INSERT INTO product_variants (product_id, variant_name, price, is_available) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isdi", $product_id, $variant_name, $price, $is_available);
    return $stmt->execute();
}

/**
 * (FUNGSI BARU) Memperbarui varian di tabel 'product_variants'.
 *
 * @param mysqli $db
 * @param int $variant_id
 * @param string $variant_name
 * @param float $price
 * @param int $is_available
 * @return bool True jika berhasil
 */
function updateProductVariant($db, $variant_id, $variant_name, $price, $is_available) {
    $stmt = $db->prepare("UPDATE product_variants SET variant_name = ?, price = ?, is_available = ? WHERE variant_id = ?");
    $stmt->bind_param("sdii", $variant_name, $price, $is_available, $variant_id);
    return $stmt->execute();
}

/**
 * (FUNGSI BARU) Menghapus produk. Kaskade di DB akan menghapus varian.
 *
 * @param mysqli $db
 * @param int $product_id
 * @return bool True jika berhasil
 */
function deleteProduct($db, $product_id) {
    $stmt = $db->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    return $stmt->execute();
}

/**
 * (FUNGSI BARU) Mengambil semua ID varian untuk produk tertentu.
 *
 * @param mysqli $db
 * @param int $product_id
 * @return array Daftar ID varian
 */
function getVariantIdsForProduct($db, $product_id) {
    $stmt = $db->prepare("SELECT variant_id FROM product_variants WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $ids = [];
    while ($row = $result->fetch_assoc()) {
        $ids[] = $row['variant_id'];
    }
    return $ids;
}

/**
 * (FUNGSI BARU) Menghapus varian berdasarkan ID-nya.
 *
 * @param mysqli $db
 * @param int $variant_id
 * @return bool True jika berhasil
 */
function deleteVariant($db, $variant_id) {
    $stmt = $db->prepare("DELETE FROM product_variants WHERE variant_id = ?");
    $stmt->bind_param("i", $variant_id);
    return $stmt->execute();
}










/* =========================================
   (BARU) FUNGSI UNTUK DASHBOARD
=========================================
*/

/**
 * Mengambil statistik ringkasan untuk dashboard.
 *
 * @param mysqli $db Objek koneksi database
 * @param string|null $start_date (Format 'Y-m-d')
 * @param string|null $end_date (Format 'Y-m-d')
 * @return array Berisi 'total_revenue', 'total_orders'
 */
function getDashboardStats($db, $start_date = null, $end_date = null) {
    $stats = [
        'total_revenue' => 0,
        'total_orders' => 0
    ];
    
    // Siapkan filter tanggal
    $date_filter = "";
    $types = "";
    $params = [];

    if ($start_date && $end_date) {
        $date_filter = " AND DATE(order_time) BETWEEN ? AND ?";
        $types = "ss";
        $params[] = $start_date;
        $params[] = $end_date;
    } elseif ($start_date) {
        $date_filter = " AND DATE(order_time) >= ?";
        $types = "s";
        $params[] = $start_date;
    } elseif ($end_date) {
        $date_filter = " AND DATE(order_time) <= ?";
        $types = "s";
        $params[] = $end_date;
    }

    // 1. Ambil Total Pendapatan (Hanya dari pesanan 'Selesai')
    $sql_revenue = "SELECT SUM(total_price) as total_revenue FROM orders WHERE status = 'Selesai'" . $date_filter;
    $stmt_revenue = $db->prepare($sql_revenue);
    if ($types) $stmt_revenue->bind_param($types, ...$params);
    $stmt_revenue->execute();
    $result = $stmt_revenue->get_result();
    $row = $result->fetch_assoc();
    $stats['total_revenue'] = $row['total_revenue'] ?? 0;
    $stmt_revenue->close();

    // 2. Ambil Jumlah Order (Semua order kecuali 'Dibatalkan')
    $sql_orders = "SELECT COUNT(order_id) as total_orders FROM orders WHERE status != 'Dibatalkan'" . $date_filter;
    $stmt_orders = $db->prepare($sql_orders);
    if ($types) $stmt_orders->bind_param($types, ...$params);
    $stmt_orders->execute();
    $result = $stmt_orders->get_result();
    $row = $result->fetch_assoc();
    $stats['total_orders'] = $row['total_orders'] ?? 0;
    $stmt_orders->close();
    
    return $stats;
}

/**
 * Mengambil 3 menu terlaris.
 *
 * @param mysqli $db Objek koneksi database
 * @param string|null $start_date
 * @param string|null $end_date
 * @return array Daftar 3 menu terlaris
 */
function getTopMenus($db, $start_date = null, $end_date = null) {
    // Siapkan filter tanggal
    $date_filter = "";
    $types = "";
    $params = [];

    if ($start_date && $end_date) {
        $date_filter = " JOIN orders o ON oi.order_id = o.order_id WHERE DATE(o.order_time) BETWEEN ? AND ?";
        $types = "ss";
        $params[] = $start_date;
        $params[] = $end_date;
    } elseif ($start_date) {
        $date_filter = " JOIN orders o ON oi.order_id = o.order_id WHERE DATE(o.order_time) >= ?";
        $types = "s";
        $params[] = $start_date;
    } elseif ($end_date) {
        $date_filter = " JOIN orders o ON oi.order_id = o.order_id WHERE DATE(o.order_time) <= ?";
        $types = "s";
        $params[] = $end_date;
    } else {
         $date_filter = ""; // Tidak ada join jika tidak ada filter
    }

    $sql = "
        SELECT p.name, SUM(oi.quantity) as total_sold
        FROM order_items oi
        JOIN product_variants pv ON oi.variant_id = pv.variant_id
        JOIN products p ON pv.product_id = p.product_id
        $date_filter
        GROUP BY p.product_id, p.name
        ORDER BY total_sold DESC
        LIMIT 3
    ";
    
    $stmt = $db->prepare($sql);
    if ($types) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * (BARU) Mengambil data pesanan terperinci untuk tabel dashboard.
 *
 * @param mysqli $db
 * @param string|null $start_date
 * @param string|null $end_date
 * @return array
 */
function getDashboardOrderDetails($db, $start_date = null, $end_date = null) {
    // [MODIFIKASI] Ubah filter dari != 'Dibatalkan' menjadi = 'Selesai'
    $date_filter = "WHERE o.status = 'Selesai'"; 
    $types = "";
    $params = [];

    if ($start_date && $end_date) {
        $date_filter .= " AND DATE(o.order_time) BETWEEN ? AND ?";
        $types = "ss";
        $params[] = $start_date;
        $params[] = $end_date;
    } elseif ($start_date) {
        $date_filter .= " AND DATE(o.order_time) >= ?";
        $types = "s";
        $params[] = $start_date;
    } elseif ($end_date) {
        $date_filter .= " AND DATE(o.order_time) <= ?";
        $types = "s";
        $params[] = $end_date;
    }
    
    $sql = "
        SELECT 
            o.order_id, 
            o.order_time, 
            o.table_number, 
            p.name as product_name, 
            pv.variant_name, 
            oi.quantity, 
            (oi.price_per_item * oi.quantity) as sub_total
        FROM order_items oi
        JOIN orders o ON oi.order_id = o.order_id
        JOIN product_variants pv ON oi.variant_id = pv.variant_id
        JOIN products p ON pv.product_id = p.product_id
        $date_filter
        ORDER BY o.order_time DESC, o.order_id DESC
        LIMIT 100 
    ";
    
    $stmt = $db->prepare($sql);
    if ($types) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * (BARU) Hanya update ketersediaan varian (untuk Dapur)
 *
 * @param mysqli $db
 * @param int $variant_id
 * @param int $is_available
 * @return bool
 */
function updateVariantAvailability($db, $variant_id, $is_available) {
    $stmt = $db->prepare("UPDATE product_variants SET is_available = ? WHERE variant_id = ?");
    $stmt->bind_param("ii", $is_available, $variant_id);
    return $stmt->execute();
}

?>