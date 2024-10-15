<?php
include '../includes/koneksi.php'; // Koneksi ke database
session_start(); // Memulai sesi

// Ambil FotoID dari parameter URL
$foto_id = $_GET['foto_id'] ?? null;

if ($foto_id) {
    // Ambil foto dari database untuk ditampilkan
    $fotoQuery = "SELECT * FROM foto WHERE FotoID='$foto_id'";
    $fotoResult = mysqli_query($conn, $fotoQuery);
    $fotoData = mysqli_fetch_assoc($fotoResult);
    
    // Ambil komentar untuk foto ini dengan join ke tabel user
    $komentarQuery = "
        SELECT k.*, u.NamaLengkap 
        FROM komentarfoto k 
        JOIN user u ON k.UserID = u.UserID 
        WHERE k.FotoID='$foto_id' 
        ORDER BY k.TanggalKomentar DESC
    ";
    $komentarResult = mysqli_query($conn, $komentarQuery);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Tombol Kembali -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Komentar Foto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .print-button {
            margin-left: 950px;
        }
    </style>
    <script>
        function printImage() {
            var printWindow = window.open('', '', 'width=800,height=600');
            printWindow.document.write('<html><head><title>Print Image</title></head><body>');
            printWindow.document.write('<img src="../img/<?php echo $fotoData['LokasiFile']; ?>" style="width:100%;" />');
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    </script>
</head>
<body>
<div class="container mt-5">
    <h2><?php echo htmlspecialchars($fotoData['JudulFoto']); ?></h2>
    <img src="../img/<?php echo htmlspecialchars($fotoData['LokasiFile']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($fotoData['JudulFoto']); ?>">

    <!-- Baris untuk tombol Kembali dan Print -->
    <div class="d-flex justify-content-between mt-3">
        
        
        <!-- Tombol Print -->
        <button class="btn btn-primary print-button" onclick="printImage()" >Print Gambar</button>
    </div>

    <p><?php echo nl2br(htmlspecialchars($fotoData['DeskripsiFoto'])); ?></p>

    <h3>Komentar</h3>
    <div class="comment-section">
        <?php while ($komentar = mysqli_fetch_assoc($komentarResult)): ?>
            <div class="comment">
                <p>
                    <strong><?php echo htmlspecialchars($komentar['NamaLengkap']); ?>:</strong>
                    <?php echo nl2br(htmlspecialchars($komentar['IsiKomentar'])); ?>
                    <em>(<?php echo htmlspecialchars($komentar['TanggalKomentar']); ?>)</em>
                </p>
            </div>
        <?php endwhile; ?>
    </div>

    <?php if (isset($_SESSION['username'])): // Tampilkan form komentar jika pengguna login ?>
        <form method="post" action="submit_komentar.php">
            <input type="hidden" name="foto_id" value="<?php echo htmlspecialchars($foto_id); ?>">
            <div class="form-group">
                <label for="komentar">Tambah Komentar:</label>
                <textarea name="isi_komentar" class="form-control" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Kirim Komentar</button>
        </form>
    <?php else: ?>
        <p>Silakan <a href="login.php">login</a> untuk meninggalkan komentar.</p>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
