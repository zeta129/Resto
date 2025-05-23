<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'Koneksi.php';
session_start();

$registerError = '';
$registerSuccess = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $nama = $_POST['nama_user'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->fetch_assoc()) {
        $registerError = "Username sudah terdaftar!";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO user (username, nama_user, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $nama, $hash);
        if ($stmt->execute()) {
            $registerSuccess = "Registrasi berhasil! Silakan <a href='login.php'>login</a>.";
        } else {
            $registerError = "Registrasi gagal!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="auth.css">
</head>
<body class="auth-bg">
    <div class="auth-card animate-pop">
        <h2>SIGN UP</h2>
        <?php if ($registerError): ?>
            <div class="error-message animate-shake"><?php echo $registerError; ?></div>
        <?php endif; ?>
        <?php if ($registerSuccess): ?>
            <div class="success-message animate-pop"><?php echo $registerSuccess; ?></div>
        <?php endif; ?>
        <form method="post" autocomplete="off">
            <label>Username</label>
            <input name="username" type="text" placeholder="Username" required autofocus>
            <label>Nama</label>
            <input name="nama" type="text" placeholder="Nama Lengkap" required>
            <label>Password</label>
            <input name="password" type="password" placeholder="Password" required>
            <button type="submit" class="btn-auth">SIGN UP</button>
            <div class="or-divider"><span>OR</span></div>
            <div class="auth-footer">
                Sudah punya akun? <a href="login.php">LOGIN</a>
            </div>
        </form>
    </div>
</body>
</html>