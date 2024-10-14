<?php
include 'includes/koneksi.php';

session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Cek apakah pengguna adalah admin
$username = $_SESSION['username'];
$query_user = "SELECT role FROM user WHERE Username = '$username'";
$result_user = mysqli_query($conn, $query_user);
$user_data = mysqli_fetch_assoc($result_user);
if ($user_data['role'] !== 'admin') {
    echo "Anda tidak memiliki akses untuk menghapus kategori.";
    exit;
}

// Ambil KategoriID dari URL
if (isset($_GET['KategoriID'])) {
    $kategori_id = mysqli_real_escape_string($conn, $_GET['KategoriID']);

    // Query untuk menghapus kategori
    $sql = "DELETE FROM kategori WHERE KategoriID = '$kategori_id'"; // Ubah 'kategori' dan 'KategoriID' sesuai dengan nama tabel dan kolom Anda
    if (mysqli_query($conn, $sql)) {
        echo "Kategori berhasil dihapus.";
    } else {
        echo "Terjadi kesalahan saat menghapus kategori: " . mysqli_error($conn);
    }
} else {
    echo "Kategori tidak ditemukan.";
}

$conn->close();

// Arahkan kembali ke dashboard
header("Location: dashboardg.php");
exit;
?>
