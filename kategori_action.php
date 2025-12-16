<?php
session_start();
include "../config.php";

// TAMBAH
if (isset($_POST['tambah'])) {
  $nama = trim($_POST['nama_kategori']);

  if ($nama != "") {
    $stmt = $conn->prepare("INSERT INTO kategori (nama_kategori) VALUES (?)");
    $stmt->bind_param("s", $nama);
    $stmt->execute();
  }
  header("Location: kategori.php");
  exit;
}

// UPDATE
if (isset($_POST['update'])) {
  $id   = intval($_POST['id_kategori']);
  $nama = trim($_POST['nama_kategori']);

  $stmt = $conn->prepare("UPDATE kategori SET nama_kategori=? WHERE id_kategori=?");
  $stmt->bind_param("si", $nama, $id);
  $stmt->execute();

  header("Location: kategori.php");
  exit;
}

// HAPUS
if (isset($_GET['hapus'])) {
  $id = intval($_GET['hapus']);

  $conn->query("DELETE FROM kategori WHERE id_kategori=$id");

  header("Location: kategori.php");
  exit;
}
