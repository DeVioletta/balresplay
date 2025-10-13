-- =================================================================
-- TABEL MENU
-- =================================================================

-- Tabel 1: products (Menyimpan informasi dasar produk)
-- Tabel ini harus dibuat pertama karena 'product_variants' bergantung padanya.
CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    category VARCHAR(50) NOT NULL,
    image_url VARCHAR(255)
);

-- Tabel 2: product_variants (Menyimpan varian, harga, dan ketersediaan)
-- Terhubung dengan tabel 'products'.
CREATE TABLE product_variants (
    variant_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    variant_name VARCHAR(50) NULL, -- Bisa NULL untuk item tanpa varian (makanan)
    price DECIMAL(10, 2) NOT NULL,
    product_code VARCHAR(20) UNIQUE NOT NULL,
    is_available TINYINT(1) NOT NULL DEFAULT 1, -- 1 = Tersedia, 0 = Habis
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);


-- =================================================================
-- TABEL PESANAN (ORDERS)
-- =================================================================

-- Tabel 3: orders (Menyimpan data utama setiap pesanan)
-- Tabel ini harus dibuat sebelum 'order_items'.
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    table_number INT NOT NULL,
    order_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Menunggu', 'Diproses', 'Selesai', 'Dibatalkan') NOT NULL DEFAULT 'Menunggu',
    payment_status ENUM('Belum Bayar', 'Lunas') NOT NULL DEFAULT 'Belum Bayar',
    total_price DECIMAL(10, 2) NOT NULL,
    notes TEXT NULL
);

-- Tabel 4: order_items (Menyimpan rincian item per pesanan)
-- Terhubung dengan tabel 'orders' dan 'product_variants'.
CREATE TABLE order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    variant_id INT NOT NULL,
    quantity INT NOT NULL,
    price_per_item DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (variant_id) REFERENCES product_variants(variant_id) ON DELETE RESTRICT
);