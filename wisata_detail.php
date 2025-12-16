<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
  header("Location: login.php");
  exit;
}

include "../config.php";
include "../includes/header.php";

$id = $_GET['id'] ?? 0;

$data = $conn->query("
  SELECT w.*, k.nama_kategori 
  FROM wisata w 
  JOIN kategori k ON w.id_kategori = k.id_kategori
  WHERE w.id_wisata = $id
")->fetch_assoc();

if (!$data) {
  echo "<p>Data tidak ditemukan</p>";
  include "../includes/footer.php";
  exit;
}
?>

<div class="content">
  <div class="card detail-card">
    <h2>Detail Wisata</h2>

    <img src="../uploads/<?= $data['gambar'] ?>" class="detail-img">

    <table class="detail-table">
      <tr>
        <th>Nama Wisata</th>
        <td><?= $data['nama'] ?></td>
      </tr>
      <tr>
        <th>Lokasi</th>
        <td><?= $data['lokasi'] ?></td>
      </tr>
      <tr>
        <th>Kategori</th>
        <td><?= $data['nama_kategori'] ?></td>
      </tr>
      <tr>
        <th>Harga Tiket</th>
        <td>Rp <?= number_format($data['harga_tiket'],0,',','.') ?></td>
      </tr>
      <tr>
        <th>Deskripsi</th>
        <td><?= nl2br($data['deskripsi']) ?></td>
      </tr>
    </table>

    <div class="form-action">
      <a href="wisata.php" class="btn btn-warning">Kembali</a>
      <a href="wisata_edit.php?id=<?= $data['id_wisata'] ?>" class="btn btn-primary">Edit</a>
    </div>
  </div>
</div>

<?php include "../includes/footer.php"; ?>
