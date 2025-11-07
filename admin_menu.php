<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Manajemen Menu</title>
    <!-- CSS Utama -->
    <link rel="stylesheet" href="css/variable.css">
    <link rel="stylesheet" href="css/admin_menu.css">
    <!-- CSS untuk Kartu Menu (diadaptasi dari menu2.css) -->
    <!-- <link rel="stylesheet" href="css/menu2.css" id="menu-card-styles"> (DIHAPUS, style dipindah ke admin.css) -->
    <!-- Font & Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600;700&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
</head>
<body>
    
    <div class="admin-layout">
        <!-- ===== SIDEBAR ===== -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="index.php" class="nav-logo">
                    <img src="images/logo_fix.png" alt="BalResplay Logo" class="logo-img">
                    <span class="logo-text">BalResplay</span>
                </a>
            </div>
            <nav class="nav-list">
                <a href="#"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="admin_menu.php" class="active"><i class="fas fa-utensils"></i> Menu Cafe</a>
                <a href="#"><i class="fas fa-receipt"></i> Pesanan</a>
                <a href="#"><i class="fas fa-cog"></i> Pengaturan</a>
            </nav>
        </aside>

        <!-- ===== MAIN CONTENT ===== -->
        <main class="main-content">
            <!-- Header Konten -->
            <header class="admin-header">
                <button class="hamburger" id="hamburger">
                    <i class="fas fa-bars"></i>
                </button>
                <h1>Manajemen Menu</h1>
            </header>

            <!-- Toolbar Menu -->
            <div class="menu-toolbar">
                <div class="filter-group">
                    <label for="category-filter">Filter Kategori:</label>
                    <select id="category-filter" name="kategori">
                        <option value="all">Semua Kategori</option>
                        <option value="rice">Rice</option>
                        <option value="noodles">Noodles</option>
                        <option value="lite-easy">Lite & Easy</option>
                        <option value="coffee">Coffee</option>
                        <option value="tea">Tea Series</option>
                        <option value="non-coffee">Non Coffee</option>
                        <option value="signature">Signature Mocktail</option>
                    </select>
                </div>
                <a href="admin_form_menu.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Menu
                </a>
            </div>

            <!-- Grid Menu Admin -->
            <div class="admin-menu-grid">
                
                <!-- CONTOH ITEM 1 (Rice) -->
                <!-- 
                    Struktur HTML ini harus di-generate oleh backend (PHP) 
                    berdasarkan data dari database.
                -->
                <div class="menu-item" data-product-id="f1">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Fried+Rice+Grill" alt="Fried Rice Chicken Grill"></div>
                    <div class="item-info">
                        <h3>Fried Rice Chicken Grill</h3>
                        <p>Nasi goreng spesial disajikan dengan ayam panggang.</p>
                        <div class="item-meta-admin">
                            <span class="item-category-badge">Rice</span>
                            <span class="item-price">40k</span>
                        </div>
                    </div>
                    <div class="item-actions">
                        <a href="admin_form_menu.php?id=f1" class="btn btn-edit">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button class="btn btn-delete" data-id="f1">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>

                <!-- CONTOH ITEM 2 (Coffee) -->
                <div class="menu-item" data-product-id="c1">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Americano" alt="Americano"></div>
                    <div class="item-info">
                        <h3>Americano</h3>
                        <p>Shot espresso yang disajikan dengan tambahan air.</p>
                        <div class="item-meta-admin">
                            <span class="item-category-badge">Coffee</span>
                            <span class="item-price">20k</span>
                        </div>
                    </div>
                     <div class="item-actions">
                        <a href="admin_form_menu.php?id=c1" class="btn btn-edit">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button class="btn btn-delete" data-id="c1">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>

                <!-- CONTOH ITEM 3 (Lite & Easy) -->
                <div class="menu-item" data-product-id="f9">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Fries" alt="Crinkle Fries"></div>
                    <div class="item-info">
                        <h3>Crinkle Fries</h3>
                        <p>Kentang goreng renyah dengan potongan berkerut.</p>
                        <div class="item-meta-admin">
                            <span class="item-category-badge">Lite & Easy</span>
                            <span class="item-price">20k</span>
                        </div>
                    </div>
                     <div class="item-actions">
                        <a href="admin_form_menu.php?id=f9" class="btn btn-edit">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button class="btn btn-delete" data-id="f9">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>

                <!-- ... Item-item menu lainnya akan di-load di sini ... -->

            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const hamburger = document.getElementById('hamburger');
            const sidebar = document.getElementById('sidebar');

            hamburger.addEventListener('click', () => {
                sidebar.classList.toggle('show');
            });

            // Logika untuk tombol hapus (untuk integrasi backend)
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', (e) => {
                    const id = e.currentTarget.dataset.id;
                    // Ganti 'confirm' dengan modal kustom jika Anda tidak ingin menggunakan dialog browser
                    const isConfirmed = confirm(`Apakah Anda yakin ingin menghapus menu ini (ID: ${id})?`);
                    
                    if (isConfirmed) {
                        // Di sinilah logika backend untuk menghapus akan dipanggil
                        // Contoh: fetch('delete_menu.php', { method: 'POST', body: JSON.stringify({ id: id }) })
                        console.log('Menghapus item dengan ID:', id);
                        
                        // Untuk demo, hapus elemen dari DOM
                        e.currentTarget.closest('.menu-item').remove();
                    }
                });
            });

            // Menyesuaikan style kartu dari menu2.css agar tidak bentrok
            // (BLOK INI DIHAPUS KARENA STYLE SUDAH DIPINDAH KE ADMIN.CSS)
            /*
            const menuStyles = document.getElementById('menu-card-styles');
            if(menuStyles) {
                // Trik untuk memuat CSS eksternal dan mengambil isinya
                fetch(menuStyles.href)
                    .then(res => res.text())
                    .then(css => {
                        // Ekstrak hanya style yang relevan (kartu)
                        // Ini adalah cara 'aman' untuk mengambil sebagian style
                        const cardStyles = `
                            .menu-item { ${css.match(/\.menu-item \{([^}]+)\}/s)[1]} }
                            .menu-item:hover { ${css.match(/\.menu-item:hover \{([^}]+)\}/s)[1]} }
                            .item-image { ${css.match(/\.item-image \{([^}]+)\}/s)[1]} }
                            .item-image img { ${css.match(/\.item-image img \{([^}]+)\}/s)[1]} }
                            .menu-item:hover .item-image img { ${css.match(/\.menu-item:hover \.item-image img \{([^}]+)\}/s)[1]} }
                            .item-info { ${css.match(/\.item-info \{([^}]+)\}/s)[1]} }
                            .item-info h3 { ${css.match(/\.item-info h3 \{([^}]+)\}/s)[1]} }
                            .item-info p { ${css.match(/\.item-info p \{([^}]+)\}/s)[1]} }
                            .item-price { ${css.match(/\.item-price \{([^}]+)\}/s)[1]} }
                        `;
                        
                        // Hapus link stylesheet lama
                        menuStyles.remove();
                        
                        // Tambahkan style yang sudah diekstrak ke <head>
                        const styleElement = document.createElement('style');
                        styleElement.textContent = cardStyles;
                        document.head.appendChild(styleElement);
                    });
            }
            */
        });
    </script>
</body>
</html>