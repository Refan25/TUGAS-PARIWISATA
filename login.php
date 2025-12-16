<?php
session_start();
include "../config.php";

$error = "";

if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $q = $conn->prepare("SELECT * FROM admin WHERE username=?");
  $q->bind_param("s", $username);
  $q->execute();
  $res = $q->get_result();

  if ($res->num_rows == 1) {
    $data = $res->fetch_assoc();
    if (password_verify($password, $data['password'])) {
      $_SESSION['admin_logged'] = true;
      $_SESSION['admin_name'] = $data['username'];
      header("Location: index.php");
      exit;
    }
  }

  $error = "Username atau password salah!";
}
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login Admin</title>
  <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>

<div class="auth-box">
  <h2>Login Admin</h2>

  <?php if ($error): ?>
    <div class="alert"><?= $error ?></div>
  <?php endif; ?>

  <form method="post">
    <label>Username</label>
    <input type="text" name="username" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <button type="submit" name="login">Login</button>
  </form>

  <!-- LINK KE REGISTER -->
  <p>
    Belum punya akun?
    <a href="register.php">Daftar di sini</a>
  </p>
</div>

</body>
</html>
