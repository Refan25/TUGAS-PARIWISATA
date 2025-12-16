<?php include "includes/header.php"; ?>
<link rel="stylesheet" href="assets/css/detail.css">
<?php
include "../pariwisata/config.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
  echo '<p>ID wisata tidak valid.</p>';
  include "includes/footer.php";
  exit;
}

$stmt = $conn->prepare("
  SELECT w.*, k.nama_kategori 
  FROM wisata w 
  JOIN kategori k ON w.id_kategori = k.id_kategori
  WHERE w.id_wisata = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$data = $res->fetch_assoc();

if (!$data) {
  echo '<p>Data wisata tidak ditemukan.</p>';
  include "includes/footer.php";
  exit;
}
?>

<!-- HERO GAMBAR -->
<div class="detail-hero">
 <img src="../pariwisata/uploads/<?= htmlspecialchars($data['gambar']) ?>" alt="">


  <div class="detail-hero-title"><?= htmlspecialchars($data['nama']) ?></div>
</div>

<!-- CONTENT -->
<div class="detail-container">

  <div class="detail-info">
    <div>Kategori: <?= htmlspecialchars($data['nama_kategori']) ?></div>
    <div>Lokasi: <?= htmlspecialchars($data['lokasi']) ?></div>
    <div>Harga Tiket: Rp<?= number_format($data['harga_tiket'], 0, ',', '.') ?></div>
  </div>

  <h3>Deskripsi Wisata</h3>
  <p><?= nl2br(htmlspecialchars($data['deskripsi'])) ?></p>

  <div class="detail-nav">
    <a href="kategori.php?id=<?= $data['id_kategori'] ?>">Kembali ke Kategori</a>
    <a href="index.php">Kembali ke Beranda</a>
  </div>

</div>

<?php include "includes/footer.php"; ?>
