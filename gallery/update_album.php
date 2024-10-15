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

// Ambil data album dari database
$query = "SELECT * FROM album WHERE AlbumID = '$album_id'";
$result = mysqli_query($conn, $query);
$album = mysqli_fetch_assoc($result);

// Jika album tidak ditemukan
if (!$album) {
    echo "Album tidak ditemukan.";
    exit();
}

// Proses update data jika form disubmit
if (isset($_POST['update'])) {
    $nama_album = mysqli_real_escape_string($conn, $_POST['nama_album']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    // Validasi input form
    if (empty($nama_album)) {
        echo "Nama album tidak boleh kosong!";
    } else {
        // Update data album di database
        $update_query = "UPDATE album SET NamaAlbum='$nama_album', Deskripsi='$deskripsi' WHERE AlbumID='$album_id'";
        if (mysqli_query($conn, $update_query)) {
            echo "Album berhasil diperbarui.";
            header("location: album.php"); // Redirect ke halaman daftar album setelah update
            exit();
        } else {
            echo "Gagal memperbarui album: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Album</title>
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
        <h2>Edit Album</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="nama_album" class="form-label">Nama Album</label>
                <input type="text" class="form-control" id="nama_album" name="nama_album" value="<?= htmlspecialchars($album['NamaAlbum']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4"><?= htmlspecialchars($album['Deskripsi']) ?></textarea>
            </div>
            <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
            <a href="album.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
