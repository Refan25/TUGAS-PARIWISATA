<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
  header("Location: login.php");
  exit;
}

include "../config.php";
include "../includes/header.php";

$search = isset($_GET['search']) ? $_GET['search'] : '';

if ($search != '') {
  $stmt = $conn->prepare("
    SELECT w.*, k.nama_kategori
    FROM wisata w
    JOIN kategori k ON w.id_kategori = k.id_kategori
    WHERE w.nama LIKE ? OR w.lokasi LIKE ?
    ORDER BY w.id_wisata DESC
  ");
  $param = "%$search%";
  $stmt->bind_param("ss", $param, $param);
  $stmt->execute();
  $result = $stmt->get_result();
} else {
  $result = $conn->query("
    SELECT w.*, k.nama_kategori
    FROM wisata w
    JOIN kategori k ON w.id_kategori = k.id_kategori
    ORDER BY w.id_wisata DESC
  ");
}
?>

<div class="table-box">
  <h3>Data Tempat Wisata</h3>
  <br>

  <!-- SEARCH -->
  <form method="get" style="margin-bottom:15px;display:flex;gap:10px;">
    <input type="text" name="search"
           placeholder="Cari wisata / lokasi..."
           value="<?= htmlspecialchars($search) ?>"
           style="padding:8px 12px;border-radius:8px;border:1px solid #ccc;">
    <button class="btn btn-primary">Cari</button>
  </form>

  <a href="wisata_add.php" class="btn btn-primary">Tambah Wisata</a>

  <table>
    <tr>
      <th>No</th>
      <th>Gambar</th>
      <th>Nama</th>
      <th>Lokasi</th>
      <th>Kategori</th>
      <th>Harga</th>
      <th>Aksi</th>
    </tr>

    <?php $no=1; while($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $no++ ?></td>
      <td>
        <img src="../uploads/<?= $row['gambar'] ?>" width="90" style="border-radius:10px">
      </td>
      <td><?= $row['nama'] ?></td>
      <td><?= $row['lokasi'] ?></td>
      <td><?= $row['nama_kategori'] ?></td>
      <td>Rp <?= number_format($row['harga_tiket'],0,',','.') ?></td>
      <td>
        <a href="wisata_detail.php?id=<?= $row['id_wisata'] ?>" class="btn btn-primary">Detail</a>
        <a href="wisata_edit.php?id=<?= $row['id_wisata'] ?>" class="btn btn-warning">Edit</a>
        <a href="wisata_delete.php?id=<?= $row['id_wisata'] ?>"
           class="btn btn-danger"
           onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
      </td>
    </tr>
    <?php endwhile; ?>

    <?php if ($result->num_rows == 0): ?>
    <tr>
      <td colspan="7" style="text-align:center;color:#999;">Data tidak ditemukan</td>
    </tr>
    <?php endif; ?>
  </table>
</div>

<?php include "../includes/footer.php"; ?>
