<?php
    // Logika error
    $error_message = '';
    if (isset($_GET['error'])) {
        if ($_GET['error'] == '1') {
            $error_message = 'Username atau password salah.';
        } else if ($_GET['error'] == '2') {
            $error_message = 'Akun Anda nonaktif. Hubungi Super Admin.';
        } else {
            $error_message = 'Terjadi kesalahan. Silakan coba lagi.';
        }
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Login</title>
    <!-- CSS Utama -->
    <link rel="stylesheet" href="css/variable.css">
    <link rel="stylesheet" href="css/admin_login.css">
    <!-- Font & Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600;700&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
</head>
<body>
    
    <div class="login-container">
        
        <img src="images/logo_fix.png" alt="BalResplay Logo" class="login-logo">

        <form action="actions/handle_login.php" method="POST" class="login-form">
            <h2>Login</h2>

            <?php if ($error_message): ?>
                <div class="login-error">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-with-icon">
                    <i class="fas fa-user"></i>
                    <input type="text" id="username" name="username" placeholder="Masukkan username" required>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-with-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                    <i class="fas fa-eye" id="toggle-password"></i>
                </div>
            </div>

            <button type="submit" class="btn btn-login">Login</button>
        </form>

    </div>

    <script src="js/admin_login.js"></script>

</body>
</html>