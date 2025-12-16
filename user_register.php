<?php
include "../pariwisata/config.php";

$success = "";
$error   = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $nama     = $_POST['nama_lengkap'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("
        INSERT INTO user (username, password, nama_lengkap, email)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("ssss", $username, $password, $nama, $email);

    if ($stmt->execute()) {
        $success = "Registrasi berhasil! Silakan login.";
    } else {
        $error = "Username atau email sudah digunakan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register User</title>
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body>

<div class="auth-container">
    <h2>Register</h2>
    <p class="subtitle">Buat akun baru untuk melanjutkan</p>

    <?php if ($success): ?>
        <div class="success-box"><?= $success ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="error-box"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Nama Lengkap</label>
        <input type="text" name="nama_lengkap" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Register</button>
    </form>

    <div class="auth-link">
        Sudah punya akun?
        <a href="user_login.php">Login di sini</a>
    </div>
</div>

</body>
</html>
