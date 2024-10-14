<?php
include 'includes/koneksi.php'; // Ganti dengan path yang sesuai

// Mengatur zona waktu ke WIB (Asia/Jakarta)
date_default_timezone_set('Asia/Jakarta');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul_foto = $_POST['judul_foto'];
    $deskripsi_foto = $_POST['deskripsi_foto'];
    $album = $_POST['album']; // Mengambil nilai album dari form
    $gambar = $_FILES['gambar']['name'];
    $target_dir = "img/"; // Menggunakan folder img untuk gambar
    $target_file = $target_dir . basename($gambar);
    $tanggal_unggah = date('Y-m-d H:i:s'); // Mendapatkan tanggal dan waktu saat ini dengan format WIB

    // Buat folder img jika belum ada
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    // Memindahkan file yang diupload ke folder target
    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
        // Menyimpan data ke database
        $sql = "INSERT INTO foto (JudulFoto, DeskripsiFoto, LokasiFile, kategori, TanggalUnggah) VALUES ('$judul_foto', '$deskripsi_foto', '$gambar', '$album', '$tanggal_unggah')";
        
        if ($conn->query($sql) === TRUE) {
            header("Location: dashboard.php"); // Redirect ke dashboard
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Error uploading file.";
    }
}

$conn->close();
?>



<!DOCTYPE html>
<html>
<head>
    <title>Tambah Foto</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5; /* Warna latar belakang */
        }
        .container {
            padding: 20px; /* Padding untuk container */
        }
        h1 {
            margin-bottom: 20px; /* Margin bawah untuk judul */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="my-4">Tambah Foto</h1>
        <form method="post" action="tambah.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="judul_foto">Judul Foto:</label>
                <input type="text" class="form-control" id="judul_foto" name="judul_foto" required>
            </div>
            <div class="form-group">
                <label for="deskripsi_foto">Deskripsi:</label> 
                <textarea class="form-control" id="deskripsi_foto" name="deskripsi_foto" required></textarea>
            </div>
            <div class="form-group">
                <label for="album">Pilih Album:</label>
                <select class="form-control" id="album" name="album" required>
                    <option value="Manchester United">Manchester United</option>
                    <option value="Barcelona">Barcelona</option>
                    <option value="Real Madrid">Real Madrid</option>
                    <option value="Legenda">Legenda</option>
                    <option value="Negara">Negara</option>
                </select>
            </div>
            <div class="form-group">
                <label for="gambar">Gambar:</label>
                <input type="file" class="form-control" id="gambar" name="gambar" required>
            </div>
            <!-- Menampilkan tanggal unggah -->
            <div class="form-group">
                <label for="tanggal_unggah">Tanggal Unggah:</label>
                <input type="text" class="form-control" id="tanggal_unggah" value="<?php echo date('Y-m-d H:i:s'); ?>" readonly>
            </div>
            <button type="submit" class="btn btn-primary">Tambah</button>
        </form>
        <a href="dashboard.php" class="btn btn-secondary mt-3">Kembali</a>
    </div>
</body>
</html>

