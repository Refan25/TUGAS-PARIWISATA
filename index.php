<?php include "includes/header.php"; ?>
<?php
include "../pariwisata/config.php";

// ambil semua kategori dari database
$kategori = $conn->query("SELECT * FROM kategori ORDER BY nama_kategori ASC");

// AMBIL QUERY SEARCH
$q = isset($_GET['q']) ? trim($_GET['q']) : "";

// jika user mencari
if ($q != "") {
    $stmt = $conn->prepare("
        SELECT * FROM wisata
        WHERE nama LIKE ? 
        OR lokasi LIKE ?
        OR deskripsi LIKE ?
        ORDER BY id_wisata DESC
    ");
    $like = "%".$q."%";
    $stmt->bind_param("sss", $like, $like, $like);
    $stmt->execute();
    $hasil_search = $stmt->get_result();
} else {
    // default tampil semua wisata
    $hasil_search = $conn->query("SELECT * FROM wisata ORDER BY id_wisata DESC");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Wisata Indonesia</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- HERO -->
<section class="hero">
  <div class="hero-overlay">
    <h1>AYO JELAJAHI WISATA JAWA TENGAH</h1>
    <form method="GET" action="index.php" class="search-box">
      <input type="text" name="q" placeholder="Cari wisata..." value="<?= htmlspecialchars($q) ?>">
      <button type="submit">Cari</button>
    </form>
  </div>
</section>

<!-- HASIL SEARCH (DITAMPILKAN JIKA ADA) -->
<?php if ($q != ""): ?>
<section class="hasil-search" style="padding:40px; text-align:center;">
  <h2>Hasil pencarian untuk: <strong><?= htmlspecialchars($q) ?></strong></h2>

  <div class="grid-wisata">
    <?php if ($hasil_search->num_rows > 0): ?>
      <?php while ($w = $hasil_search->fetch_assoc()): ?>
        <div class="card-wisata">
          <div class="card-img">
            <img src="../pariwisata/uploads/<?= $w['gambar'] ?>" alt="<?= htmlspecialchars($w['nama']) ?>">
            <a href="detail.php?id=<?= $w['id_wisata'] ?>" class="btn-detail-top">Detail</a>
          </div>
          
          <div class="card-content">
            <h3><?= htmlspecialchars($w['nama']) ?></h3>
            <p class="lokasi"><?= htmlspecialchars($w['lokasi']) ?></p>
            <p class="harga">Rp<?= number_format($w['harga_tiket'], 0, ',', '.') ?></p>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p style="margin-top:20px;">Tidak ada hasil yang cocok.</p>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>

<!-- PILIH KATEGORI -->
<section class="kategori">
  <h2>PILIH KATEGORI KAMU!</h2>
  <div class="kategori-grid">
    <?php while ($k = $kategori->fetch_assoc()): ?>
      <?php 
        $gambar = !empty($k['gambar']) 
          ? "../pariwisata/uploads/" . $k['gambar'] 
          : "assets/img/default_kategori.jpg";
      ?>
      <div class="kategori-card">
        <a href="kategori.php?id=<?= $k['id_kategori'] ?>">
          <img src="<?= $gambar ?>" alt="<?= htmlspecialchars($k['nama_kategori']) ?>">
          <div class="kategori-nama"><?= htmlspecialchars($k['nama_kategori']) ?></div>
        </a>
      </div>
    <?php endwhile; ?>
  </div>
</section>

<!-- ALASAN PILIH KAMI -->
<section class="alasan">
  <h2>MENGAPA PILIH KAMI?</h2>
  <p>KARENA KAMI MEMBERIKAN PENGALAMAN WISATA YANG MENYENANGKAN DAN BERKESAN</p>
  <div class="alasan-grid">
    <div class="alasan-item">
      <!-- <img src="../img/harga.png" alt="Harga Murah"> -->
      <h3>HARGA DIJAMIN MURAH</h3>
      <p>Menawarkan destinasi terbaik dengan harga yang ramah di kantong.</p>
    </div>
    <div class="alasan-item">
      <!-- <img src="../img/destinasi.png" alt="Banyak Destinasi"> -->
      <h3>BANYAK DESTINASI KUNJUNGAN</h3>
      <p>Temukan berbagai pilihan wisata menarik di seluruh Indonesia.</p>
    </div>
    <div class="alasan-item">
      <!-- <img src="../img/pelayanan.png" alt="Pelayanan Terbaik"> -->
      <h3>KUALITAS PELAYANAN TINGGI</h3>
      <p>Kami siap membantu perjalanan wisata kamu dengan profesional.</p>
    </div>
  </div>
</section>

<!-- TESTIMONI -->
<section class="testimoni">
  <h2>APA KATA MEREKA?</h2>
  <div class="testimoni-grid">
    <div class="testimoni-card">
      <p>"Pelayanan sangat cepat dan informatif. Saya mudah menemukan destinasi impian saya!"</p>
      <h4>Dwi Lestari</h4>
      <span>Traveler dari Bandung</span>
    </div>
    <div class="testimoni-card">
      <p>"Website ini membantu saya merencanakan liburan dengan mudah. Banyak pilihan menarik."</p>
      <h4>Rafi Pratama</h4>
      <span>Traveler dari Jakarta</span>
    </div>
    <div class="testimoni-card">
      <p>"Desainnya keren dan mudah digunakan. Saya pasti rekomendasikan ke teman-teman!"</p>
      <h4>Sinta Dewi</h4>
      <span>Traveler dari Surabaya</span>
    </div>
  </div>
</section>

<?php include "includes/footer.php"; ?>

</body>
</html>
