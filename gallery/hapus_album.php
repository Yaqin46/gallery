<?php
include "includes/koneksi.php";
session_start();
if (!isset($_SESSION["username"])) {
    header("location:login.php");
    exit();
}

$album_id = $_GET['albumid'] ?? ''; // Mengambil AlbumID dari URL

// Cek apakah album_id ada
if (empty($album_id)) {
    echo "ID Album tidak valid.";
    exit();
}

// Ambil data album untuk konfirmasi penghapusan
$query = "SELECT * FROM album WHERE AlbumID = '$album_id'";
$result = mysqli_query($conn, $query);
$album = mysqli_fetch_assoc($result);

// Jika album tidak ditemukan
if (!$album) {
    echo "Album tidak ditemukan.";
    exit();
}

// Jika pengguna mengkonfirmasi penghapusan
if (isset($_POST['delete'])) {
    // Proses penghapusan dari database
    $delete_query = "DELETE FROM album WHERE AlbumID = '$album_id'";
    if (mysqli_query($conn, $delete_query)) {
        echo "Album berhasil dihapus.";
        header("location: album.php"); // Redirect ke halaman daftar album setelah penghapusan
        exit();
    } else {
        echo "Gagal menghapus album: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Album</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 600px;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Hapus Album</h2>
        <p>Apakah Anda yakin ingin menghapus album <strong><?= htmlspecialchars($album['NamaAlbum']) ?></strong>?</p>
        
        <form method="POST" action="">
            <button type="submit" name="delete" class="btn btn-danger">Hapus</button>
            <a href="album.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
