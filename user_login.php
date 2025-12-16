<?php
session_start();
include "../pariwisata/config.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM user WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_logged'] = true;
        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['user_nama'] = $user['nama_lengkap'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login User</title>
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body>

<div class="auth-container">
    <h2>Login</h2>
    <p class="subtitle">Silakan login untuk melanjutkan</p>

    <?php if ($error): ?>
        <div class="error-box"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <div class="auth-link">
        Belum punya akun? <a href="user_register.php">Daftar di sini</a>
    </div>
</div>

</body>
</html>
