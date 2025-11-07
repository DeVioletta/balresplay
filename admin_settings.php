<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale-1.0">
    <title>Admin | Pengaturan Akun</title>
    <!-- CSS Utama -->
    <link rel="stylesheet" href="css/variable.css">
    <link rel="stylesheet" href="css/admin_menu.css"> <!-- Base Admin -->
    <link rel="stylesheet" href="css/admin_settings.css"> <!-- (FILE CSS BARU) -->
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
                <a href="admin_menu.php"><i class="fas fa-utensils"></i> Menu Cafe</a>
                <a href="admin_orders.php"><i class="fas fa-receipt"></i> Pesanan</a>
                <a href="admin_settings.php" class="active"><i class="fas fa-cog"></i> Pengaturan</a>
            </nav>
        </aside>

        <!-- ===== MAIN CONTENT ===== -->
        <main class="main-content">
            <!-- Header Konten -->
            <header class="admin-header">
                <button class="hamburger" id="hamburger">
                    <i class="fas fa-bars"></i>
                </button>
                <h1>Pengaturan Akun</h1>
            </header>

            <!-- Toolbar (Tombol Tambah Akun) -->
            <div class="admin-toolbar">
                <button class="btn btn-primary" id="add-account-btn">
                    <i class="fas fa-plus"></i> Tambah Akun
                </button>
            </div>

            <!-- Daftar Akun (Tabel) -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Tindakan</th>
                        </tr>
                    </thead>
                    <tbody id="account-list">
                        <!-- CONTOH DATA 1 -->
                        <tr>
                            <td data-label="Username">admin_super</td>
                            <td data-label="Role">Super Admin</td>
                            <td data-label="Status">
                                <span class="status-badge status-active">Aktif</span>
                            </td>
                            <td data-label="Tindakan">
                                <button class="btn btn-edit-sm" data-id="1">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-delete-sm" data-id="1">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </td>
                        </tr>
                        <!-- CONTOH DATA 2 -->
                        <tr>
                            <td data-label="Username">kasir_01</td>
                            <td data-label="Role">Kasir</td>
                            <td data-label="Status">
                                <span class="status-badge status-active">Aktif</span>
                            </td>
                            <td data-label="Tindakan">
                                <button class="btn btn-edit-sm" data-id="2">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-delete-sm" data-id="2">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </td>
                        </tr>
                        <!-- CONTOH DATA 3 -->
                        <tr>
                            <td data-label="Username">dapur_sore</td>
                            <td data-label="Role">Dapur</td>
                            <td data-label="Status">
                                <span class="status-badge status-inactive">Nonaktif</span>
                            </td>
                            <td data-label="Tindakan">
                                <button class="btn btn-edit-sm" data-id="3">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-delete-sm" data-id="3">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </td>
                        </tr>
                        <!-- Data lain akan di-load di sini -->
                    </tbody>
                </table>
            </div>

        </main>
        
        <div class="sidebar-overlay" id="sidebar-overlay"></div>
    </div>

    <!-- ===== MODAL TAMBAH/EDIT AKUN (Hidden by default) ===== -->
    <div class="modal-overlay" id="account-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modal-title">Tambah Akun Baru</h3>
                <span class="modal-close" id="modal-close">&times;</span>
            </div>
            <form id="account-form">
                <div class="modal-body">
                    <input type="hidden" id="user_id" name="user_id">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <small>Kosongkan jika tidak ingin mengubah password.</small>
                        <div class="input-with-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="password" name="password" class="form-control">
                            <i class="fas fa-eye" id="toggle-password"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select id="role" name="role" class="form-control" required>
                            <option value="Kasir">Kasir</option>
                            <option value="Dapur">Dapur</option>
                            <option value="Super Admin">Super Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control" required>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="modal-cancel-btn">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // --- Logika Sidebar Hamburger ---
            const hamburger = document.getElementById('hamburger');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            if (hamburger) {
                hamburger.addEventListener('click', () => {
                    sidebar.classList.add('show');
                });
            }
            if (overlay) {
                overlay.addEventListener('click', () => {
                    sidebar.classList.remove('show');
                });
            }

            // --- Logika Modal Akun ---
            const modal = document.getElementById('account-modal');
            const addBtn = document.getElementById('add-account-btn');
            const closeBtn = document.getElementById('modal-close');
            const cancelBtn = document.getElementById('modal-cancel-btn');
            const modalTitle = document.getElementById('modal-title');
            const form = document.getElementById('account-form');
            const accountList = document.getElementById('account-list');

            const openModal = (title) => {
                modalTitle.textContent = title;
                modal.classList.add('show');
            };

            const closeModal = () => {
                modal.classList.remove('show');
                form.reset(); // Reset form saat ditutup
            };

            // Buka modal untuk Tambah Akun
            if (addBtn) {
                addBtn.addEventListener('click', () => {
                    openModal('Tambah Akun Baru');
                });
            }

            // Buka modal untuk Edit Akun (delegasi event)
            if (accountList) {
                accountList.addEventListener('click', (e) => {
                    if (e.target.closest('.btn-edit-sm')) {
                        // Di aplikasi nyata, Anda akan mengambil data user via AJAX
                        // Di sini kita isi data dummy:
                        openModal('Edit Akun');
                        document.getElementById('user_id').value = '2';
                        document.getElementById('username').value = 'kasir_01';
                        document.getElementById('role').value = 'Kasir';
                        document.getElementById('status').value = '1';
                    }
                });
            }

            // Tombol-tombol penutup modal
            if (closeBtn) closeBtn.addEventListener('click', closeModal);
            if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
            if (modal) {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        closeModal();
                    }
                });
            }

            // --- Logika Toggle Password (dari login) ---
            const togglePassword = document.getElementById('toggle-password');
            const password = document.getElementById('password');

            if (togglePassword) {
                togglePassword.addEventListener('click', function () {
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);
                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-eye-slash');
                });
            }
        });
    </script>
</body>
</html>