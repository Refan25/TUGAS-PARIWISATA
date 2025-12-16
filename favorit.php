<?php

include "includes/header.php";
include "../pariwisata/config.php";

// =====================
// CEK LOGIN
// =====================
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit;
}

$id_user = $_SESSION['user_id'];

// =====================
// AMBIL DATA FAVORIT USER
// =====================
$stmt = $conn->prepare("
    SELECT w.*
    FROM favorit f
    JOIN wisata w ON f.id_wisata = w.id_wisata
    WHERE f.id_user = ?
    ORDER BY f.created_at DESC
");
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Favorit Saya</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<div class="container">
    <h2 style="margin:30px 0">❤️ Wisata Favorit Saya</h2>

    <?php if ($result->num_rows > 0): ?>
        <div class="grid-wisata">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="card-wisata">
                    <div class="card-img">
                        <img src="../pariwisata/uploads/<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['nama']) ?>">

                        <!-- UNFAVORITE -->
                        <button class="fav-btn active" data-id="<?= $row['id_wisata'] ?>">
                            <i class="fa-solid fa-heart"></i>
                        </button>
                    </div>

                    <div class="card-content">
                        <h3><?= htmlspecialchars($row['nama']) ?></h3>
                        <p class="lokasi"><?= htmlspecialchars($row['lokasi']) ?></p>
                        <p class="harga">Rp<?= number_format($row['harga_tiket'], 0, ',', '.') ?></p>
                        <a href="detail.php?id=<?= $row['id_wisata'] ?>" class="btn-detail">Detail</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>Belum ada wisata favorit.</p>
    <?php endif; ?>

    <p style="margin-top:30px">
        <a href="index.php" class="btn-back">Kembali ke Beranda</a>
    </p>
</div>

<?php include "includes/footer.php"; ?>

<!-- ===================== -->
<!-- JS UNFAVORITE -->
<!-- ===================== -->
<script>
document.querySelectorAll('.fav-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const id = this.dataset.id;

        fetch('tambah_favorit.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'id_wisata=' + id
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'removed') {
                this.closest('.card-wisata').remove();
            }
        });
    });
});
</script>

<style>
.fav-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    background: white;
    border: none;
    border-radius: 50%;
    padding: 8px;
    cursor: pointer;
}
.fav-btn i {
    color: red;
}
</style>

</body>
</html>
