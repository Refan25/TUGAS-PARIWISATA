<?php
session_start();
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Pariwisata Jateng</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- FONT AWESOME -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- FILE CSS UTAMA -->
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

  <header class="navbar">
    <div class="container">
      <nav>
        <div class="nav-left">
          <a href="index.php" class="logo">Pariwisata Jateng</a>
        </div>

        <div class="nav-right">
          <?php if (isset($_SESSION['user_id'])): ?>
            <a href="favorit.php" class="nav-link">
  <i class="fa-solid fa-heart"></i> Favorit
</a>

            <a href="user_logout.php" class="nav-link">Logout</a>
          <?php else: ?>
            <a href="user_login.php" class="nav-link">Login</a>
          <?php endif; ?>
        </div>
      </nav>
    </div>
  </header>

  <hr>
