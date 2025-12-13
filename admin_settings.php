<?php
require_once __DIR__ . '/config/database.php';
startSecureSession();
redirectIfNotLoggedIn('admin_login.php');

$role = $_SESSION['role'];
if ($role !== 'Super Admin') {
    $_SESSION['error_message'] = 'Halaman Pengaturan hanya bisa diakses oleh Super Admin.';
    if ($role == 'Kasir') header("Location: admin_dashboard.php");
    else if ($role == 'Dapur') header("Location: admin_menu.php");
    else header("Location: admin_login.php");
    exit();
}

$users_result = getAllAdminUsers($db); 

$settings_result = $db->query("SELECT * FROM settings");
$settings = [];
while ($row = $settings_result->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}
$table_count = $settings['table_count'] ?? '20'; 

$message = '';
$message_type = '';
if (isset($_GET['error'])) {
    $error = $_GET['error'];
    $message_type = 'error';
    if ($error == 'exists') $message = 'Username sudah terdaftar.';
    if ($error == 'empty') $message = 'Semua field wajib diisi (kecuali password saat edit).';
    if ($error == 'selfdelete') $message = 'Anda tidak dapat menghapus akun Anda sendiri.';
    if ($error == 'unauthorized') $message = 'Anda tidak memiliki izin untuk melakukan aksi ini.';
    if ($error == 'failed') $message = 'Terjadi kesalahan. Silakan coba lagi.';
    if ($error == 'invalid_number') $message = 'Jumlah meja harus berupa angka positif.';
}
if (isset($_GET['success'])) {
    $success = $_GET['success'];
    $message_type = 'success';
    if ($success == 'created') $message = 'Akun baru berhasil dibuat. Status: Nonaktif.';
    if ($success == 'deleted') $message = 'Akun berhasil dihapus.';
    if ($success == 'updated') $message = 'Akun berhasil diperbarui.';
    if ($success == 'settings_updated') $message = 'Pengaturan berhasil disimpan.'; 
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Pengaturan Akun</title>
    <link rel="stylesheet" href="css/variable.css">
    <link rel="stylesheet" href="css/admin_menu.css"> <link rel="stylesheet" href="css/admin_settings.css"> <link rel="stylesheet" href="css/menu_form.css"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600;700&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        .admin-message { padding: 15px; border-radius: 5px; margin-bottom: 20px; font-weight: 500; }
        .admin-message.success { background-color: var(--success-color); color: var(--light-text); }
        .admin-message.error { background-color: var(--danger-color); color: var(--light-text); }
        .switch { position: relative; display: inline-block; width: 50px; height: 28px; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: var(--tertiary-color); transition: .4s; }
        .slider:before { position: absolute; content: ""; height: 20px; width: 20px; left: 4px; bottom: 4px; background-color: white; transition: .4s; }
        input:checked + .slider { background-color: var(--success-color); }
        input:checked + .slider:before { transform: translateX(22px); }
        .slider.round { border-radius: 34px; }
        .slider.round:before { border-radius: 50%; }
        .form-group-toggle { display: flex; align-items: center; justify-content: space-between; margin-top: 15px; }
        
        .settings-card {
            background-color: var(--darker-bg);
            border: 1px solid var(--tertiary-color);
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .settings-card h4 {
            font-size: 1.5rem;
            margin: 0;
            padding: 24px 30px;
            border-bottom: 1px solid var(--tertiary-color);
        }
        .settings-card form {
            padding: 24px 30px;
        }
    </style>
</head>
<body>
    
    <div class="admin-layout">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="index.php" class="nav-logo">
                    <img src="images/logo_fix.png" alt="BalResplay Logo" class="logo-img">
                    <span class="logo-text">BalResplay</span>
                </a>
            </div>
            
            <nav class="nav-list">
                <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
                <?php if ($role == 'Super Admin' || $role == 'Kasir'): ?>
                    <a href="admin_dashboard.php" class="<?php echo $currentPage == 'admin_dashboard.php' ? 'active' : ''; ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <?php endif; ?>
                <a href="admin_menu.php" class="<?php echo ($currentPage == 'admin_menu.php' || $currentPage == 'admin_form_menu.php') ? 'active' : ''; ?>"><i class="fas fa-utensils"></i> Menu Cafe</a>
                <?php if ($role == 'Dapur'): ?>
                     <a href="kitchen_display.php" class="<?php echo $currentPage == 'kitchen_display.php' ? 'active' : ''; ?>"><i class="fas fa-receipt"></i> Antrian Dapur</a>
                <?php else: ?>
                    <a href="admin_orders.php" class="<?php echo $currentPage == 'admin_orders.php' ? 'active' : ''; ?>"><i class="fas fa-receipt"></i> Pesanan</a>
                <?php endif; ?>
                <?php if ($role == 'Super Admin'): ?>
                    <a href="admin_settings.php" class="<?php echo $currentPage == 'admin_settings.php' ? 'active' : ''; ?>"><i class="fas fa-cog"></i> Pengaturan</a>
                <?php endif; ?>
            </nav>

            <div class="sidebar-footer">
                <a href="actions/handle_logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </aside>

        <main class="main-content">
            <header class="admin-header">
                <button class="hamburger" id="hamburger">
                    <i class="fas fa-bars"></i>
                </button>
                <h1>Pengaturan</h1>
            </header>

            <?php if ($message): ?>
                <div class="admin-message <?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <div class="settings-card">
                <h4>Pengaturan Umum</h4>
                <form action="actions/save_settings.php" method="POST">
                    <div class="form-group">
                        <label for="table_count">Jumlah Meja</label>
                        <input type="number" id="table_count" name="table_count" value="<?php echo htmlspecialchars($table_count); ?>" min="1" required>
                        <small style="color: var(--text-muted); margin-top: 8px; display: block;">
                            Ini akan menentukan jumlah pilihan meja di halaman pelanggan.
                        </small>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                </form>
            </div>


            <div class="settings-card">
                <h4>Pengaturan Akun</h4>
                <div class="admin-toolbar" style="padding: 0 30px 20px 30px; margin: 0;">
                    <button class="btn btn-primary" id="add-account-btn">
                        <i class="fas fa-plus"></i> Tambah Akun
                    </button>
                </div>

                <div class="table-container" style="border-top: 1px solid var(--tertiary-color); border-radius: 0 0 10px 10px;">
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
                                            <button class="btn btn-edit-sm btn-edit-account" 
                                                    data-id="<?php echo $user['user_id']; ?>"
                                                    data-username="<?php echo htmlspecialchars($user['username']); ?>"
                                                    data-role="<?php echo htmlspecialchars($user['role']); ?>"
                                                    data-status="<?php echo $user['status']; ?>">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            
                                            <a href="actions/delete_account.php?id=<?php echo $user['user_id']; ?>" 
                                               class="btn btn-delete-sm" 
                                               onclick="return confirm('Apakah Anda yakin ingin menghapus akun <?php echo htmlspecialchars($user['username']); ?>?');"
                                               <?php if ($user['user_id'] == $_SESSION['user_id']) echo 'style="display:none;"'; ?>>
                                                <i class="fas fa-trash"></i> Hapus
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" style="text-align: center;">Tidak ada data akun.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
        
        <div class="sidebar-overlay" id="sidebar-overlay"></div>
    </div>

    <div class="modal-overlay" id="account-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modal-title">Tambah Akun Baru</h3>
                <span class="modal-close" id="modal-close">&times;</span>
            </div>
            
            <form id="account-form" action="actions/handle_account.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" id="user_id" name="user_id" value="">
                    
                    <div class="form-group">
                        <label for="username">Username</label>
                        <div class="input-with-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" id="username" name="username" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <small id="password-hint">Wajib diisi untuk akun baru.</small>
                        <div class="input-with-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="password" name="password" class="form-control">
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
                    
                    <div class="form-group-toggle" id="status-toggle-group" style="display: none;">
                        <label for="status" style="margin-bottom: 0;">Status Akun (Aktif/Nonaktif)</label>
                        <label class="switch">
                            <input type="checkbox" id="status" name="status" value="1">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="modal-cancel-btn">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script src="js/admin_settings.js"></script>
</body>
</html>