<?php
include '../includes/koneksi.php'; // Koneksi ke database
session_start(); // Memulai sesi

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $foto_id = $_POST['foto_id'];
    $isi_komentar = mysqli_real_escape_string($conn, $_POST['isi_komentar']);
    $user_id = $_SESSION['user_id'];

    // Simpan komentar ke database
    $query = "INSERT INTO komentarfoto (FotoID, UserID, IsiKomentar, TanggalKomentar) VALUES ('$foto_id', '$user_id', '$isi_komentar', NOW())";
    mysqli_query($conn, $query);

    // Redirect kembali ke halaman komentar
    header("Location: komentar.php?foto_id=$foto_id");
    exit();
}
