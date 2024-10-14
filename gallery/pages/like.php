<?php
// Koneksi ke database
include '../includes/koneksi.php';

// Ambil ID Foto dari URL
$foto_id = $_GET['foto_id'];

// Tambahkan like ke database
$query = "INSERT INTO like_foto (FotoID, UserID, TanggalLike) VALUES ($foto_id, 1, NOW())";
mysqli_query($conn, $query);

// Redirect kembali ke halaman utama
header("Location: ../index.php");
