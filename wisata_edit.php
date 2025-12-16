<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
  header("Location: login.php");
  exit;
}

include "../config.php";
include "../includes/header.php";

$id = $_GET['id'] ?? 0;

/* ambil data wisata */
$data = $conn->query("SELECT * FROM wisata WHERE id_wisata = $id")->fetch_assoc();
if (!$data) {
  echo "<p>Data tidak ditemukan</p>";
  include "../includes/footer.php";
  exit;
}

/* ambil kategori */
$kategori = $conn->query("SELECT * FROM kategori ORDER BY nama_kategori ASC");

/* proses update */
if (isset($_POST['update'])) {
  $nama        = $_POST['nama'];
  $lokasi      = $_POST['lokasi'];
  $harga_tiket = $_POST['harga_tiket'];
  $id_kategori = $_POST['id_kategori'];
  $deskripsi   = $_POST['deskripsi'];

  /* cek upload gambar */
  if (!empty($_FILES['gambar']['name'])) {
    $file_name = time() . "_" . basename($_FILES["gambar"]["name"]);
    move_uploaded_file($_FILES["gambar"]["tmp_name"], "../uploads/" . $file_name);

    $sql = "UPDATE wisata SET
              nama=?,
              lokasi=?,
              harga_tiket=?,
              id_kategori=?,
              deskripsi=?,
              gambar=?
            WHERE id_wisata=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdissi", $nama, $lokasi, $harga_tiket, $id_kategori, $deskripsi, $file_name, $id);
  } else {
    $sql = "UPDATE wisata SET
              nama=?,
              lokasi=?,
              harga_tiket=?,
              id_kategori=?,
              deskripsi=?
            WHERE id_wisata=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdisi", $nama, $lokasi, $harga_tiket, $id_kategori, $deskripsi, $id);
  }

  $stmt->execute();
  header("Location: wisata.php");
  exit;
}
?>

<div class="form-box">
  <h3>Edit Wisata</h3>

  <form method="POST" enctype="multipart/form-data">

    <label>Nama Wisata</label>
    <input type="text" name="nama" value="<?= $data['nama'] ?>" required>

    <label>Lokasi</label>
    <input type="text" name="lokasi" value="<?= $data['lokasi'] ?>" required>

    <label>Harga Tiket</label>
    <input type="number" name="harga_tiket" value="<?= $data['harga_tiket'] ?>" required>

    <label>Kategori</label>
    <select name="id_kategori" required>
      <?php while ($k = $kategori->fetch_assoc()): ?>
        <option value="<?= $k['id_kategori'] ?>"
          <?= $k['id_kategori'] == $data['id_kategori'] ? 'selected' : '' ?>>
          <?= $k['nama_kategori'] ?>
        </option>
      <?php endwhile; ?>
    </select>

    <label>Deskripsi</label>
    <textarea name="deskripsi" rows="4"><?= $data['deskripsi'] ?></textarea>

    <label>Gambar Sekarang</label><br>
    <img src="../uploads/<?= $data['gambar'] ?>" width="120"><br><br>

    <label>Ganti Gambar (opsional)</label>
    <input type="file" name="gambar" accept="image/*">

    <br><br>
    <button type="submit" name="update" class="btn btn-primary">Update</button>
    <a href="wisata.php" class="btn btn-warning">Batal</a>

  </form>
</div>

<?php include "../includes/footer.php"; ?>
