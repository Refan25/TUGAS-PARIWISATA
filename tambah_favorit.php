<?php
session_start();
header('Content-Type: application/json');

include "../pariwisata/config.php";

// CEK LOGIN
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Silakan login terlebih dahulu.'
    ]);
    exit;
}

$id_user = $_SESSION['user_id'];

// Ambil id_wisata dari POST
$id_wisata = isset($_POST['id_wisata']) ? intval($_POST['id_wisata']) : 0;

// VALIDASI ID WISATA
if ($id_wisata <= 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'ID wisata tidak valid.'
    ]);
    exit;
}

// CEK APAKAH SUDAH FAVORIT
$cek = $conn->prepare("SELECT id_favorit FROM favorit WHERE id_user = ? AND id_wisata = ?");
$cek->bind_param("ii", $id_user, $id_wisata);
$cek->execute();
$result = $cek->get_result();

// JIKA SUDAH ADA → HAPUS (UNFAVORITE)
if ($result->num_rows > 0) {
    $hapus = $conn->prepare("DELETE FROM favorit WHERE id_user = ? AND id_wisata = ?");
    $hapus->bind_param("ii", $id_user, $id_wisata);
    $hapus->execute();

    echo json_encode([
        'status' => 'removed',
        'message' => 'Dihapus dari favorit.'
    ]);
    exit;
}

// JIKA BELUM ADA → TAMBAH (FAVORITE)
$tambah = $conn->prepare("INSERT INTO favorit (id_user, id_wisata) VALUES (?, ?)");
$tambah->bind_param("ii", $id_user, $id_wisata);
$tambah->execute();

echo json_encode([
    'status' => 'added',
    'message' => 'Ditambahkan ke favorit!'
]);
exit;
