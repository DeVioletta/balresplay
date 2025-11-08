<?php
require_once __DIR__ . '/config/database.php';
startSecureSession();
redirectIfNotLoggedIn('admin_login.php');

// // Hanya Super Admin yang bisa mengakses halaman ini
if ($_SESSION['role'] !== 'Super Admin') {
    // Jika bukan, tendang ke dashboard
    header("Location: admin_dashboard.php");
    exit();
}

// Ambil semua data pengguna untuk ditampilkan di tabel
$users_result = getAllAdminUsers($db);

// Logika untuk menampilkan pesan sukses/error
$message = '';
if (isset($_GET['error'])) {
    $error = $_GET['error'];
    if ($error == 'exists') $message = '<div class="admin-message error">Username sudah terdaftar.</div>';
    if ($error == 'empty') $message = '<div class="admin-message error">Semua field wajib diisi.</div>';
    if ($error == 'selfdelete') $message = '<div class="admin-message error">Anda tidak dapat menghapus akun Anda sendiri.</div>';
    if ($error == 'unauthorized') $message = '<div class="admin-message error">Anda tidak memiliki izin untuk melakukan aksi ini.</div>';
    if ($error == 'failed') $message = '<div class="admin-message error">Terjadi kesalahan. Silakan coba lagi.</div>';
}
if (isset($_GET['success'])) {
    $success = $_GET['success'];
    if ($success == 'created') $message = '<div class="admin-message success">Akun baru berhasil dibuat. Status: Nonaktif.</div>';
    if ($success == 'deleted') $message = '<div class="admin-message success">Akun berhasil dihapus.</div>';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Pengaturan Akun</title>
    <!-- CSS Utama -->
    <link rel="stylesheet" href="css/variable.css">
    <link rel="stylesheet" href="css/admin_menu.css"> <!-- Base Admin -->
    <link rel="stylesheet" href="css/admin_settings.css"> <!-- (FILE CSS BARU) -->
    <!-- Font & Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600;700&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        /* (BARU) CSS untuk pesan error/sukses */
        .admin-message {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .admin-message.success {
            background-color: var(--success-color);
            color: var(--light-text);
        }
        .admin-message.error {
            background-color: var(--danger-color);
            color: var(--light-text);
        }
    </style>
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
                <a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="admin_menu.php"><i class="fas fa-utensils"></i> Menu Cafe</a>
                <a href="admin_orders.php"><i class="fas fa-receipt"></i> Pesanan</a>
                <a href="admin_settings.php" class="active"><i class="fas fa-cog"></i> Pengaturan</a>
            </nav>

            <!-- (BARU) Tombol Logout -->
            <div class="sidebar-footer">
                <a href="actions/handle_logout.php" class="logout-link">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
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

            <!-- (BARU) Tampilkan pesan sukses/error -->
            <?php echo $message; ?>

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
                        <?php if ($users_result && $users_result->num_rows > 0): ?>
                            <?php foreach ($users_result as $user): ?>
                                <tr>
                                    <td data-label="Username"><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td data-label="Role"><?php echo htmlspecialchars($user['role']); ?></td>
                                    <td data-label="Status">
                                        <?php if ($user['status'] == 1): ?>
                                            <span class="status-badge status-active">Aktif</span>
                                        <?php else: ?>
                                            <span class="status-badge status-inactive">Nonaktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td data-label="Tindakan">
                                        <!-- Tombol Edit belum difungsikan sesuai permintaan -->
                                        <button class="btn btn-edit-sm" data-id="<?php echo $user['user_id']; ?>" disabled>
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <!-- (PERUBAHAN) Tombol Hapus jadi link ke skrip delete -->
                                        <a href="actions/delete_account.php?id=<?php echo $user['user_id']; ?>" 
                                           class="btn btn-delete-sm" 
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus akun <?php echo htmlspecialchars($user['username']); ?>?');">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center;">Tidak ada data akun.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </main>
        
        <div class="sidebar-overlay" id="sidebar-overlay"></div>
    </div>

    <!-- ===== MODAL TAMBAH AKUN (DIRUBAH) ===== -->
    <div class="modal-overlay" id="account-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modal-title">Tambah Akun Baru</h3>
                <span class="modal-close" id="modal-close">&times;</span>
            </div>
            <!-- (PERUBAHAN) Form mengarah ke skrip handle_account.php -->
            <form id="account-form" action="actions/handle_account.php" method="POST">
                <div class="modal-body">
                    
                    <div class="form-group">
                        <label for="username">Username</label>
                        <div class="input-with-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" id="username" name="username" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <!-- (PERUBAHAN) Hapus <small> dan buat password required -->
                        <div class="input-with-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="password" name="password" class="form-control" required>
                            <i class="fas fa-eye" id="toggle-password"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select id="role" name="role" class="form-control" required>
                            <option value="" disabled selected>Pilih role</option>
                            <option value="Kasir">Kasir</option>
                            <option value="Dapur">Dapur</option>
                            <option value="Super Admin">Super Admin</option>
                        </select>
                    </div>
                    <!-- (PERUBAHAN) Hapus field Status sesuai permintaan -->
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

            // --- Logika Modal Akun (DISIMPLIFIKASI) ---
            const modal = document.getElementById('account-modal');
            const addBtn = document.getElementById('add-account-btn');
            const closeBtn = document.getElementById('modal-close');
            const cancelBtn = document.getElementById('modal-cancel-btn');
            const form = document.getElementById('account-form');

            const openModal = () => {
                modal.classList.add('show');
            };

            const closeModal = () => {
                modal.classList.remove('show');
                form.reset(); // Reset form saat ditutup
            };

            // Buka modal untuk Tambah Akun
            if (addBtn) {
                addBtn.addEventListener('click', openModal);
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