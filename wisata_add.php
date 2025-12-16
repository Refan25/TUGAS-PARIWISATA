<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
  header("Location: login.php");
  exit;
}

include "../config.php";
include "../includes/header.php";

// ambil kategori
$kategori = $conn->query("SELECT * FROM kategori ORDER BY nama_kategori ASC");

if (isset($_POST['tambah'])) {
  $nama         = $_POST['nama'];
  $lokasi       = $_POST['lokasi'];
  $harga_tiket  = $_POST['harga_tiket'];
  $id_kategori  = $_POST['id_kategori'];
  $deskripsi    = $_POST['deskripsi'];

  // upload gambar
  $target_dir = "../uploads/";
  $file_name  = time() . "_" . basename($_FILES["gambar"]["name"]);
  $target_file = $target_dir . $file_name;

  if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
    $stmt = $conn->prepare("
      INSERT INTO wisata (nama, lokasi, harga_tiket, id_kategori, deskripsi, gambar)
      VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("ssdiss", $nama, $lokasi, $harga_tiket, $id_kategori, $deskripsi, $file_name);
    $stmt->execute();

    header("Location: wisata.php");
    exit;
  } else {
    echo "<script>alert('Gagal upload gambar');</script>";
  }
}
?>

<div class="form-box">
  <h3>Tambah Wisata Baru</h3>

  <form method="POST" enctype="multipart/form-data">

    <label>Nama Wisata</label>
    <input type="text" name="nama" required>

    <label>Lokasi</label>
    <input type="text" name="lokasi" required>

    <label>Harga Tiket (Rp)</label>
    <input type="number" name="harga_tiket" required>

    <label>Kategori</label>
    <select name="id_kategori" required>
      <option value="">-- Pilih Kategori --</option>
      <?php while ($row = $kategori->fetch_assoc()): ?>
        <option value="<?= $row['id_kategori'] ?>">
          <?= $row['nama_kategori'] ?>
        </option>
      <?php endwhile; ?>
    </select>

    <label>Deskripsi</label>
    <textarea name="deskripsi" rows="4" required></textarea>

    <label>Upload Gambar</label>
    <input type="file" name="gambar" accept="image/*" required>

    <br>
    <button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
    <a href="wisata.php" class="btn btn-warning">Batal</a>
  </form>
</div>

<?php include "../includes/footer.php"; ?>
