<?php
include "../config.php";

$msg = "";

if (isset($_POST['register'])) {
  $username = $_POST['username'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  $stmt = $conn->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
  $stmt->bind_param("ss", $username, $password);

  if ($stmt->execute()) {
    $msg = "Registrasi berhasil! Silakan login.";
  } else {
    $msg = "Username sudah digunakan!";
  }
}
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Register Admin</title>
  <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>

<div class="auth-box">
  <h2>Register Admin</h2>

  <?php if ($msg): ?>
    <div class="alert"><?= $msg ?></div>
  <?php endif; ?>

  <form method="post">
    <label>Username</label>
    <input type="text" name="username" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <button type="submit" name="register">Register</button>
  </form>

  <!-- LINK BALIK KE LOGIN -->
  <p>
    Sudah punya akun?
    <a href="login.php">Login di sini</a>
  </p>
</div>

</body>
</html>
