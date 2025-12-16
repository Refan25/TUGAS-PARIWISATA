<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
  header("Location: login.php");
  exit;
}
include "../config.php";

$id = $_GET['id'];
$conn->query("DELETE FROM wisata WHERE id_wisata=$id");

header("Location: wisata.php");
exit;
?>
