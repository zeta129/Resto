<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'koneksi.php';
session_start();

$loginError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Ambil data user berdasarkan username
    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verifikasi password
    if ($user && password_verify($password, $user['password'])) {
        // Simpan data ke session
        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama_user'] = $user['nama_user'];
        $_SESSION['id_level'] = $user['id_level'];

        // Redirect berdasarkan level user
        switch ($user['id_level']) {
            case 'admin':
                header('Location: pesanan.php');
                break;
            case '16':
                header('Location: dashboard.php');
                break;
            default:
                header('Location: dashboard.php');
                break;
        }
        exit;
    } else {
        $loginError = "Login gagal! Username atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="auth.css">
</head>
<body class="auth-bg">
    <div class="auth-card animate-pop">
        <h2>LOGIN</h2>

        <?php if ($loginError): ?>
            <div class="error-message animate-shake"><?php echo htmlspecialchars($loginError); ?></div>
        <?php endif; ?>

        <form method="post" autocomplete="off">
            <label for="username">Username</label>
            <input name="username" id="username" type="text" placeholder="Username" required autofocus>

            <label for="password">Password</label>
            <input name="password" id="password" type="password" placeholder="Password" required>

            <button type="submit" class="btn-auth">LOGIN</button>
        </form>

        <div class="or-divider"><span>OR</span></div>
        <div class="auth-footer">
            Belum punya akun? <a href="register.php">SIGN UP</a>
        </div>
    </div>
</body>
</html>
