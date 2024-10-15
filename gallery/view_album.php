<?php
// Koneksi ke database
include 'includes/koneksi.php';
session_start(); // Memulai sesi

// Cek apakah album_id di URL
if (isset($_GET['album_id'])) {
    $album_id = mysqli_real_escape_string($conn, $_GET['album_id']);
    
    // Ambil detail album dari database
    $albumQuery = "SELECT * FROM album WHERE AlbumID='$album_id'";
    $albumResult = mysqli_query($conn, $albumQuery);
    $album = mysqli_fetch_assoc($albumResult);
    
    // Ambil foto dari album
    $fotoQuery = "SELECT * FROM foto WHERE AlbumID='$album_id' ORDER BY TanggalUnggah DESC";
    $fotoResult = mysqli_query($conn, $fotoQuery);
} else {
    // Redirect ke halaman utama jika album_id tidak ditemukan
    header('Location: index.php');
    exit();
}

// Proses Like dan Unlike
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['like']) || isset($_POST['unlike'])) {
        $foto_id = mysqli_real_escape_string($conn, $_POST['foto_id']);
        $user_id = $_SESSION['user_id'];

        // Ambil foto yang bersangkutan
        $fotoQuery = "SELECT * FROM foto WHERE FotoID='$foto_id'";
        $fotoResult = mysqli_query($conn, $fotoQuery);
        $foto = mysqli_fetch_assoc($fotoResult);
        
        // Mengupdate liked_by
        $liked_by = explode(',', $foto['liked_by']);
        if (isset($_POST['like'])) {
            // Jika tidak ada dalam daftar liked_by, tambahkan
            if (!in_array($user_id, $liked_by)) {
                $liked_by[] = $user_id;
                $new_liked_by = implode(',', $liked_by);
                $updateQuery = "UPDATE foto SET liked_by='$new_liked_by', jumlah_like=jumlah_like + 1 WHERE FotoID='$foto_id'";
                mysqli_query($conn, $updateQuery);
            }
        } elseif (isset($_POST['unlike'])) {
            // Jika ada dalam daftar liked_by, hapus
            if (in_array($user_id, $liked_by)) {
                $liked_by = array_diff($liked_by, [$user_id]);
                $new_liked_by = implode(',', $liked_by);
                $updateQuery = "UPDATE foto SET liked_by='$new_liked_by', jumlah_like=jumlah_like - 1 WHERE FotoID='$foto_id'";
                mysqli_query($conn, $updateQuery);
            }
        }

        // Redirect kembali ke halaman album setelah melakukan aksi
        header("Location: view_album.php?album_id=$album_id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($album['NamaAlbum']); ?> - Album</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .album-header {
            text-align: center;
            margin: 20px 0;
        }
        .card img {
            height: 200px;
            object-fit: cover;
            cursor: pointer; /* Menambahkan kursor pointer untuk menunjukkan bahwa gambar bisa diklik */
        }
    </style>
</head>
<body>

<div class="container">
    <div class="album-header">
        <h1><?php echo htmlspecialchars($album['NamaAlbum']); ?></h1>
        <p><?php echo htmlspecialchars($album['Deskripsi']); ?></p>
        <a href="home.php" class="btn btn-secondary">Kembali ke Halaman Utama</a>
    </div>

    <h2>Foto dalam Album</h2>
    <div class="row">
        <?php while ($foto = mysqli_fetch_assoc($fotoResult)): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <a href="pages/komentar.php?foto_id=<?php echo $foto['FotoID']; ?>">
                        <img src="img/<?php echo htmlspecialchars($foto['LokasiFile']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($foto['JudulFoto']); ?>">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($foto['JudulFoto']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($foto['DeskripsiFoto']); ?></p>
                        <p class="card-text">Tanggal Unggah: <?php echo date('d M Y', strtotime($foto['TanggalUnggah'])); ?></p>
                        <p class="card-text">Jumlah Like: <?php echo $foto['jumlah_like']; ?></p>
                        <form method="post" action="">
                            <input type="hidden" name="foto_id" value="<?php echo $foto['FotoID']; ?>">
                            <?php if (isset($_SESSION['username'])): ?>
                                <?php
                                $user_id = $_SESSION['user_id'];
                                $liked_by = explode(',', $foto['liked_by']);
                                if (in_array($user_id, $liked_by)): ?>
                                    <button type="submit" name="unlike" class="btn btn-danger">Unlike</button>
                                <?php else: ?>
                                    <button type="submit" name="like" class="btn btn-success">Like</button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<footer class="text-center" style="margin-top: 40px;">
    <p>&copy; 2024 FIFA League Gallery. All Rights Reserved.</p>
</footer>

</body>
</html>
