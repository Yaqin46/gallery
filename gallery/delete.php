<?php
include 'includes/koneksi.php';
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Ambil ID Foto dari URL
if (isset($_GET['FotoID'])) {
    $foto_id = mysqli_real_escape_string($conn, $_GET['FotoID']);

    // Hapus data dari database
    $sql = "DELETE FROM foto WHERE FotoID='$foto_id'";
    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "ID foto tidak ditemukan.";
    exit;
}

$conn->close();
?>
