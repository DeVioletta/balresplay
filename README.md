# BalResplay

**BalResplay** adalah aplikasi web untuk manajemen operasional restoran dan kafe. Sistem ini mencakup alur end-to-end, mulai dari pemesanan menu oleh pelanggan, pengelolaan pesanan oleh kasir, hingga tampilan antrian real-time untuk dapur.

Dokumentasi ini bertujuan sebagai **panduan penggunaan yang jelas dan terstruktur**, agar proyek dapat dijalankan dengan benar dari tahap instalasi hingga simulasi pembayaran.

---

## Fitur Utama

* Pemesanan menu oleh pelanggan (QR / langsung)
* Manajemen pesanan (Kasir)
* Kitchen Display System (Dapur)
* Manajemen menu & stok
* Pembayaran online (Midtrans Sandbox)
* Export laporan ke Excel (.xlsx)

---

## Requirements

Pastikan lingkungan pengembangan telah memenuhi kebutuhan berikut:

* PHP **7.4 atau 8.x**
* Web Server dan MySQL

  * XAMPP / Laragon / MAMP
* Composer (Dependency Manager PHP)
  Unduh: [https://getcomposer.org/download/](https://getcomposer.org/download/)
* Git (opsional)
* Browser modern
* Ngrok (untuk webhook Midtrans pada localhost)
  Unduh: [https://ngrok.com/download](https://ngrok.com/download)

---

## Anatomi Proyek

Bagian ini menjelaskan struktur folder dan peran masing-masing komponen dalam proyek **BalResplay**, agar memudahkan pemahaman alur kode dan pemeliharaan.

```text
/
├── actions/                 # Endpoint logika backend (CRUD, auth, order, payment)
│   ├── delete_account.php       # Hapus akun pengguna
│   ├── delete_menu.php          # Hapus data menu
│   ├── download_report.php      # Export laporan (Excel)
│   ├── get_order_status.php     # Ambil status pesanan (AJAX)
│   ├── handle_account.php       # Tambah / update akun
│   ├── handle_login.php         # Proses login admin
│   ├── handle_logout.php        # Proses logout
│   ├── handle_order.php         # Proses pembuatan pesanan
│   ├── midtrans.php             # Konfigurasi & helper Midtrans
│   ├── notification_handler.php # Webhook Midtrans
│   ├── save_menu.php            # Simpan / update menu
│   ├── save_settings.php        # Simpan pengaturan sistem
│   ├── update_kitchen_status.php# Update status dapur
│   ├── update_order_status.php  # Update status pesanan
│   └── validate_pending_order.php # Validasi pesanan tertunda
│
├── config/                  # Konfigurasi inti aplikasi
│   ├── database.php            # Koneksi database
│   └── structure_db.sql        # Struktur database (opsional)
│
├── css/                     # Styling (Admin, Menu, Kitchen)
│   ├── admin_dashboard.css
│   ├── admin_login.css
│   ├── admin_menu.css
│   ├── admin_orders.css
│   ├── admin_settings.css
│   ├── header.css
│   ├── kitchen_display.css
│   ├── menu.css
│   ├── menu_form.css
│   ├── pembayaran.css
│   └── variable.css
│
├── images/                  # Asset gambar
│   ├── uploads/                # Upload gambar menu
│   ├── americano.jpg
│   ├── americano.png
│   ├── bakery-bg.jpg
│   └── logo_fix.png
│
├── includes/                # Komponen PHP reusable
│   └── header.php
│
├── js/                      # Logic frontend (AJAX & interaksi UI)
│   ├── admin_dashboard.js
│   ├── admin_form_menu.js
│   ├── admin_login.js
│   ├── admin_menu.js
│   ├── admin_orders.js
│   ├── admin_settings.js
│   ├── kitchen_display.js
│   ├── menu.js
│   └── payment.js
│
├── vendor/                  # Dependency Composer (auto-generated)
├── .gitattributes
├── .gitignore
├── admin_dashboard.php      # Dashboard admin
├── admin_form_menu.php      # Form tambah/edit menu
├── admin_login.php          # Halaman login admin
├── admin_menu.php           # Manajemen menu
├── admin_orders.php         # Manajemen pesanan
├── admin_settings.php       # Pengaturan sistem
├── balresplay_backup.sql    # Database dump utama
├── composer.json            # Konfigurasi Composer
├── composer.lock
├── kitchen_display.php      # Kitchen Display System
├── menu.php                 # Halaman menu pelanggan
├── payment.php              # Proses pembayaran
└── README.md
```

## Instalasi & Konfigurasi

### 1. Unduh Proyek

**Menggunakan Git:**

```bash
git clone https://github.com/your-username/balresplay.git
```

**Atau menggunakan file ZIP:**

* Unduh repository
* Ekstrak ke folder web server, contoh:

  ```
  C:\xampp\htdocs\balresplay
  ```

---

### 2. Instalasi Dependensi (Composer)

Proyek ini menggunakan beberapa dependensi eksternal, di antaranya:

* `midtrans/midtrans-php`
* `phpoffice/phpspreadsheet`

#### Instalasi Otomatis (Direkomendasikan)

```bash
cd C:\xampp\htdocs\balresplay
composer install
```

Setelah proses selesai, pastikan folder `vendor/` telah terbentuk.

#### Instalasi Manual (Opsional)

```bash
composer require midtrans/midtrans-php
composer require phpoffice/phpspreadsheet
```

---

### 3. Konfigurasi Database

#### Import Database

1. Buka phpMyAdmin: `http://localhost/phpmyadmin`
2. Buat database dengan nama:

   ```
   balresplay
   ```
3. Pilih database → tab **Import**
4. Import file:

   ```
   balresplay_backup.sql
   ```

#### Konfigurasi Koneksi Database

Edit file:

```php
config/database.php
```

Sesuaikan pengaturan berikut sesuai konfigurasi MySQL Anda:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'balresplay');
```

---

## Konfigurasi Midtrans (Sandbox)

Untuk menjalankan fitur pembayaran online, aplikasi perlu dikonfigurasi menggunakan Midtrans dalam mode Sandbox.

### 1. Mendapatkan API Key

* Login ke Midtrans Dashboard (Sandbox)
* Masuk ke menu **Settings > Access Keys**
* Salin **Server Key** dan **Client Key**

---

### 2. Konfigurasi Server Key

Edit file:

```php
actions/midtrans.php
```

```php
Config::$serverKey = 'Mid-server-xxxxxxxxx';
Config::$isProduction = false; // Mode Sandbox
```

---

### 3. Konfigurasi Client Key

Tambahkan Client Key pada file:

* `menu.php`
* `payment.php`

```html
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="Mid-client-xxxxxxxxx"></script>
```

---

## Setup Ngrok (Notifikasi Pembayaran)

Karena aplikasi berjalan di localhost, Midtrans memerlukan alamat publik untuk mengirim notifikasi pembayaran (Webhook). Hal ini dapat dilakukan menggunakan **ngrok**.

```bash
ngrok http 80
```

Salin URL HTTPS yang dihasilkan, lalu masukkan ke Midtrans Dashboard:

* **Settings > Configuration**
* **Payment Notification URL**:

```
https://abcd-123.ngrok-free.app/balresplay/actions/notification_handler.php
```

Lokasi pengaturan di Midtrans Dashboard:

```
Settings > Payments > Notification URL > Payment Notification URL
```

Sesuaikan `/balresplay/` dengan nama folder proyek Anda.

---

## Menjalankan Aplikasi

1. Jalankan **Apache dan MySQL**
2. Buka browser

### Akses Halaman

* **Pelanggan (Menu):**

  ```
  http://localhost/balresplay/menu.php
  ```
* **Admin / Staf:**

  ```
  http://localhost/balresplay/admin_login.php
  ```

---

## Role Pengguna & Akun Pengujian

Akun berikut telah tersedia di database `balresplay_backup.sql` dan dapat digunakan untuk pengujian:

### 1. Super Admin

* Username: `mulyamin`
* Password: `mulyamin123`
* Akses penuh sistem

### 2. Kasir

* Username: `kasir`
* Password: `kasir`
* Akses pengelolaan pesanan dan menu

### 3. Dapur

* Username: `dapur`
* Password: `dapur`
* Akses Kitchen Display System

---

## Simulasi Pembayaran Midtrans

1. Buka halaman `menu.php`
2. Pilih menu dan lakukan pemesanan
3. Pilih metode pembayaran (QRIS, E-Wallet, atau Virtual Account)
4. Konfirmasi pesanan hingga Snap Midtrans muncul

### Menggunakan Midtrans Sandbox Simulator

* Buka:

  ```
  https://simulator.sandbox.midtrans.com/
  ```

#### Simulasi E-Wallet / QRIS

* Pada halaman Snap, pilih metode E-Wallet
* Saat QR Code muncul, klik kanan pada gambar QR
* Pilih **Copy Image Address**
* Tempelkan URL tersebut ke kolom QRIS pada Midtrans Sandbox Simulator

#### Simulasi Virtual Account

* Pilih metode Virtual Account pada Snap
* Gunakan nomor Virtual Account yang ditampilkan
* Masukkan ke bagian Virtual Account pada Sandbox Simulator

Setelah simulasi berhasil, Midtrans akan mengirim notifikasi ke aplikasi dan status pesanan akan diperbarui secara otomatis.

Untuk panduan lengkap simulasi pembayaran, silakan merujuk ke dokumentasi resmi Midtrans:

```
https://docs.midtrans.com/
```

---

## Catatan

* Jalankan `composer install` sebelum menggunakan aplikasi
* Gunakan **Sandbox Key** Midtrans untuk pengujian lokal
* Pastikan ngrok aktif agar notifikasi pembayaran dapat diterima

---

