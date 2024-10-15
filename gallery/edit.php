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

    // Jika form di-submit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $judul_foto = mysqli_real_escape_string($conn, $_POST['JudulFoto']);
        $deskripsi_foto = mysqli_real_escape_string($conn, $_POST['DeskripsiFoto']);
        $album = mysqli_real_escape_string($conn, $_POST['album']); // Menyimpan nilai album

        // Cek apakah ada file gambar yang diunggah
        if (isset($_FILES['LokasiFile']) && $_FILES['LokasiFile']['error'] == 0) {
            $target_dir = "img/";
            $target_file = $target_dir . basename($_FILES["LokasiFile"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Validasi format gambar
            $valid_extensions = array("jpg", "jpeg", "png", "gif", "jfif", "webp", "svg");
            if (in_array($imageFileType, $valid_extensions)) {
                // Pindahkan file gambar ke direktori yang ditentukan
                move_uploaded_file($_FILES["LokasiFile"]["tmp_name"], $target_file);

                // Perbarui data di database dengan gambar baru
                $sql = "UPDATE foto SET JudulFoto='$judul_foto', DeskripsiFoto='$deskripsi_foto', LokasiFile='" . basename($_FILES["LokasiFile"]["name"]) . "', kategori='$album' WHERE FotoID='$foto_id'";
            } else {
                echo "Hanya file gambar yang diperbolehkan.";
                exit;
            }
        } else {
            // Jika tidak ada file baru, tetap gunakan gambar yang lama
            $sql = "UPDATE foto SET JudulFoto='$judul_foto', DeskripsiFoto='$deskripsi_foto', kategori='$album' WHERE FotoID='$foto_id'";
        }

        if ($conn->query($sql) === TRUE) {
            header("Location: dashboard.php");
            exit;
        } else {
            echo "Error: " . $conn->error;
        }
    }

    // Ambil data foto berdasarkan FotoID
    $sql = "SELECT * FROM foto WHERE FotoID='$foto_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Data tidak ditemukan.";
        exit;
    }
} else {
    echo "ID foto tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Foto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Edit Foto</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="JudulFoto" class="form-label">Judul Foto</label>
                <input type="text" class="form-control" id="JudulFoto" name="JudulFoto" value="<?php echo htmlspecialchars($row['JudulFoto']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="DeskripsiFoto" class="form-label">Deskripsi Foto</label>
                <textarea class="form-control" id="DeskripsiFoto" name="DeskripsiFoto" rows="4" required><?php echo htmlspecialchars($row['DeskripsiFoto']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="album" class="form-label">Pilih Album</label>
                <select class="form-control" id="album" name="album" required>
                    <option value="Manchester United" <?php if ($row['kategori'] == 'Manchester United') echo 'selected'; ?>>Manchester United</option>
                    <option value="Barcelona" <?php if ($row['kategori'] == 'Barcelona') echo 'selected'; ?>>Barcelona</option>
                    <option value="Real Madrid" <?php if ($row['kategori'] == 'Real Madrid') echo 'selected'; ?>>Real Madrid</option>
                    <option value="Legenda" <?php if ($row['kategori'] == 'Legenda') echo 'selected'; ?>>Legenda</option>
                    <option value="Negara" <?php if ($row['kategori'] == 'Negara') echo 'selected'; ?>>Negara</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="LokasiFile" class="form-label">Gambar (biarkan kosong jika tidak ingin mengganti)</label>
                <input type="file" class="form-control" id="LokasiFile" name="LokasiFile">
                <?php if (!empty($row['LokasiFile'])): ?>
                    <img src="img/<?php echo htmlspecialchars($row['LokasiFile']); ?>" alt="<?php echo htmlspecialchars($row['JudulFoto']); ?>" class="img-thumbnail" style="max-width: 200px; margin-top: 10px;">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
