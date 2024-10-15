<?php
session_start();

// Koneksi ke database
$host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "gallery";
$conn = new mysqli($host, $db_user, $db_password, $db_name);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_GET['FotoID'])) {
    // Simpan nilai dari GET parameter FotoID ke dalam variabel $foto_id
    $foto_id = $conn->real_escape_string($_GET['FotoID']);
    
    // Query menggunakan $foto_id
    $sql = "SELECT * FROM foto WHERE FotoID='$foto_id'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Data tidak ditemukan.";
        exit; // Hentikan eksekusi jika tidak ada data
    }
} else {
    echo "ID foto tidak ditemukan.";
    exit; // Hentikan eksekusi jika FotoID tidak ada
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Foto</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            padding-top: 56px; /* Sesuaikan jika tinggi navbar berbeda */
        }
        .container {
            background: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px;
        }
        .table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }
        .table th {
            background: #fff;
            color: black;
            font-weight: bold;
        }
        .table td {
            vertical-align: middle;
        }
        .btn-secondary {
            background: #6c757d;
            border-color: #6c757d;
        }
        .btn-secondary:hover {
            background: #5a6268;
            border-color: #545b62;
        }
        img {
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

    <div class="container">
        <h1 class="my-4">Detail Foto</h1>
        <table class="table table-striped">
            <tr>
                <th>Judul Foto</th>
                <td><?php echo htmlspecialchars($row['JudulFoto']); ?></td>
            </tr>
            <tr>
                <th>Deskripsi</th>
                <td><?php echo htmlspecialchars($row['DeskripsiFoto']); ?></td>
            </tr>
            <tr>
                <th>Tanggal Unggah</th>
                <td><?php echo htmlspecialchars($row['TanggalUnggah']); ?></td>
            </tr>
            <tr>
                <th>Gambar</th>
                <td>
                    <img src="img/<?php echo htmlspecialchars($row['LokasiFile']); ?>" alt="<?php echo htmlspecialchars($row['JudulFoto']); ?>" style="width: 200px; height: auto;">
                </td>
            </tr>
            <tr>
                <th>Jumlah Like</th>
                <td><?php echo $row['jumlah_like']; ?></td>
            </tr>
        </table>
        <a href="dashboard.php" class="btn btn-secondary mt-3">Kembali</a>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
