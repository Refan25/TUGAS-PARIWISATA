<?php include "includes/header.php"; ?>
<?php
include "../pariwisata/config.php";

$id_kategori = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_kategori <= 0) {
  echo '<p>ID kategori tidak valid.</p>';
  include "includes/footer.php";
  exit;
}

// ================== TAMBAHAN 1: AMBIL FAVORIT USER ==================
$favoritUser = [];
if (isset($_SESSION['user_id'])) {
  $id_user = $_SESSION['user_id'];
  $fav = $conn->prepare("SELECT id_wisata FROM favorit WHERE id_user = ?");
  $fav->bind_param("i", $id_user);
  $fav->execute();
  $resFav = $fav->get_result();
  while ($f = $resFav->fetch_assoc()) {
    $favoritUser[] = $f['id_wisata'];
  }
}
// ===================================================================

// ambil nama kategori
$kat = $conn->prepare("SELECT * FROM kategori WHERE id_kategori = ?");
$kat->bind_param("i", $id_kategori);
$kat->execute();
$res_kat = $kat->get_result();
$kategori = $res_kat->fetch_assoc();

if (!$kategori) {
  echo '<p>Kategori tidak ditemukan.</p>';
  include "includes/footer.php";
  exit;
}

// ambil wisata berdasarkan kategori
$stmt = $conn->prepare("
  SELECT w.*, k.nama_kategori 
  FROM wisata w 
  JOIN kategori k ON w.id_kategori = k.id_kategori
  WHERE w.id_kategori = ?
  ORDER BY w.id_wisata DESC
");
$stmt->bind_param("i", $id_kategori);
$stmt->execute();
$wisata = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kategori - <?= htmlspecialchars($kategori['nama_kategori']) ?></title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<div class="container">
  <h2 class="judul-kategori">Kategori: <?= htmlspecialchars($kategori['nama_kategori']) ?></h2>

  <div class="grid-wisata">
    <?php if ($wisata->num_rows > 0): ?>
      <?php while ($row = $wisata->fetch_assoc()): ?>
        <?php
          // ================== TAMBAHAN 2: CEK APAKAH FAVORIT ==================
          $isFav = in_array($row['id_wisata'], $favoritUser);
        ?>
        <div class="card-wisata">
          <div class="card-img">
            <img src="../pariwisata/uploads/<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['nama']) ?>">

            <a href="detail.php?id=<?= $row['id_wisata'] ?>" class="btn-detail-top">Detail</a>

            <?php if (isset($_SESSION['user_id'])): ?>
              <button
                type="button"
                class="fav-btn <?= $isFav ? 'active' : '' ?>"
                data-id="<?= $row['id_wisata'] ?>">
                <i class="<?= $isFav ? 'fa-solid' : 'fa-regular' ?> fa-heart"></i>
              </button>
            <?php else: ?>
              <a href="user_login.php" class="fav-btn">
                <i class="fa-regular fa-heart"></i>
              </a>
            <?php endif; ?>
          </div>

          <div class="card-content">
            <h3><?= htmlspecialchars($row['nama']) ?></h3>
            <p class="lokasi"><?= htmlspecialchars($row['lokasi']) ?></p>
            <p class="harga">Rp<?= number_format($row['harga_tiket'], 0, ',', '.') ?></p>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>Belum ada wisata pada kategori ini.</p>
    <?php endif; ?>
  </div>

  <p style="text-align:center;margin-top:30px;">
    <a href="index.php" class="btn-back">Kembali ke Beranda</a>
  </p>
</div>

<?php include "includes/footer.php"; ?>

<!-- ================= FAVORIT JS ================= -->
<script>
document.querySelectorAll('.fav-btn').forEach(btn => {
  btn.addEventListener('click', function (e) {
    e.preventDefault();

    const button = e.currentTarget;
    const idWisata = button.dataset.id;
    if (!idWisata) return;

    fetch('tambah_favorit.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: 'id_wisata=' + idWisata
    })
    .then(res => res.json())
    .then(data => {
      if (data.status === 'added') {
        button.classList.add('active');
        button.innerHTML = '<i class="fa-solid fa-heart"></i>';
      } else if (data.status === 'removed') {
        button.classList.remove('active');
        button.innerHTML = '<i class="fa-regular fa-heart"></i>';
      } else {
        alert(data.message);
      }
    })
    .catch(() => {
      alert('Gagal memproses favorit');
    });
  });
});
</script>

<style>
/* ================= TAMBAHAN 3: WARNA MERAH ================= */
.fav-btn.active i {
  color: red;
}
</style>

</body>
</html>
