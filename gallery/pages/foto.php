<?php
// Koneksi ke database
include '../includes/koneksi.php';

// Ambil ID Foto dari URL
$foto_id = $_GET['foto_id'];

// Query untuk mengambil detail foto
$query_foto = "SELECT * FROM foto WHERE FotoID = $foto_id";
$result_foto = mysqli_query($conn, $query_foto);
$foto = mysqli_fetch_assoc($result_foto);

// Query untuk mengambil komentar foto
$query_komentar = "SELECT * FROM komentar_foto WHERE FotoID = $foto_id ORDER BY TanggalKomentar DESC";
$result_komentar = mysqli_query($conn, $query_komentar);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title><?php echo $foto['JudulFoto']; ?></title>
</head>
<body style="font-family: 'Arial', sans-serif; background-color: #f4f4f4; color: #333; margin: 0; padding: 20px;">
    
    <div style="max-width: 800px; margin: 0 auto; background-color: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <img src="../img/<?php echo $foto['LokasiFile']; ?>" alt="<?php echo $foto['JudulFoto']; ?>" style="width: 100%; height: auto; border-radius: 10px;">
        <h2 style="color: #228B22;"><?php echo $foto['JudulFoto']; ?></h2>
        <p><?php echo $foto['DeskripsiFoto']; ?></p>
        <p><small>Uploaded on: <?php echo $foto['TanggalUnggah']; ?></small></p>

        <!-- Komentar -->
        <h3 style="color: #228B22;">Komentar</h3>
        <div style="margin-top: 20px;">
            <?php while($row = mysqli_fetch_assoc($result_komentar)): ?>
                <div style="padding: 10px; margin-bottom: 15px; background-color: #f0f0f0; border-radius: 5px;">
                    <p style="margin: 0;"><?php echo $row['IsiKomentar']; ?></p>
                    <p style="font-size: 0.8em; color: #555;"><small>By User <?php echo $row['UserID']; ?> on <?php echo $row['TanggalKomentar']; ?></small></p>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Form Komentar -->
        <form action="tambah_komentar.php" method="POST" style="margin-top: 20px;">
            <input type="hidden" name="foto_id" value="<?php echo $foto_id; ?>">
            <textarea name="isi_komentar" style="width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px;" rows="4" required></textarea>
            <button type="submit" style="padding: 10px 20px; background-color: #228B22; color: white; border: none; border-radius: 5px; cursor: pointer;">Kirim Komentar</button>
        </form>
    </div>
    
</body>
</html>
