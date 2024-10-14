<?php
include 'includes/koneksi.php';
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Ambil data dari form
$FotoID = $_POST['FotoID'];
$UserID = $_SESSION['user_id'];
$IsiKomentar = $_POST['IsiKomentar'];
$TanggalKomentar = date('Y-m-d H:i:s');

// Simpan komentar ke tabel komentarfoto
$sql = "INSERT INTO komentarfoto (FotoID, UserID, IsiKomentar, TanggalKomentar) 
        VALUES ('$FotoID', '$UserID', '$IsiKomentar', '$TanggalKomentar')";

if (mysqli_query($conn, $sql)) {
    header("Location: gallery.php"); // Ganti dengan halaman yang sesuai setelah komentar berhasil ditambahkan
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>
