<?php
include 'includes/koneksi.php';

session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Ambil informasi user yang sedang login
$username = $_SESSION['username'];
$query_user = "SELECT role FROM user WHERE Username = '$username'";
$result_user = mysqli_query($conn, $query_user);
$user_data = mysqli_fetch_assoc($result_user);

// Cek apakah user memiliki role admin
if ($user_data['role'] !== 'admin') {
    echo "Anda tidak memiliki akses ke halaman ini.";
    exit;
}

// Inisialisasi variabel pencarian
$search = "";
if (isset($_POST['search'])) {
    $search = mysqli_real_escape_string($conn, $_POST['search']);
}

// Query untuk mengambil data dari tabel foto berdasarkan FotoID, JudulFoto, DeskripsiFoto, dan TanggalUnggah
$sql = "SELECT * FROM foto WHERE 
        FotoID LIKE '%$search%' OR
        JudulFoto LIKE '%$search%' OR 
        DeskripsiFoto LIKE '%$search%' OR
        TanggalUnggah LIKE '%$search%'";  // Tambahkan TanggalUnggah dalam kondisi pencarian
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Foto</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Gaya Umum */
        body {
            background-color: #f5f5f5; /* Warna latar belakang halaman */
            font-family: Arial, sans-serif; /* Gaya font */
        }

        .container {
            padding: 20px; /* Padding untuk container */
        }

        /* Navbar */
        .navbar {
            background-color: #343a40; /* Warna latar navbar */
        }
        .navbar-brand, .nav-link {
            color: #333 !important; /* Warna teks navbar */
        }

        /* Tabel */
        .table-custom {
            background-color: #ffffff; /* Warna latar tabel */
            border-radius: 5px; /* Sudut tabel melengkung */
            overflow: hidden; /* Mencegah overflow */
        }
        .table-custom th {
            background-color: #007bff; /* Warna latar kepala tabel */
            color: #ffffff; /* Warna teks kepala tabel */
            padding: 10px; /* Padding kepala tabel */
        }
        .table-custom td {
            background-color: #f8f9fa; /* Warna latar baris tabel */
            padding: 8px; /* Padding baris tabel */
        }

        /* Gaya Tombol */
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }
        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        /* Gaya gambar thumbnail */
        .img-thumbnail {
            max-width: 80px;
            max-height: 80px;
            object-fit: cover; /* Memastikan gambar tetap terpotong dengan baik tanpa mengubah proporsi */
        }

        /* Button Print */
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        /* Responsif */
        @media (max-width: 768px) {
            .table-custom {
                font-size: 12px; /* Ukuran font kecil untuk layar kecil */
            }
        }

        /* Gaya untuk pesan "Tidak memiliki akses" */
        .no-access {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .no-access h2 {
            color: #dc3545;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        /* Gaya untuk tombol kembali */
        .btn-back {
            background-color: #007bff;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1.2rem;
        }

        .btn-back:hover {
            background-color: #0056b3;
        }

        /* Gaya icon panah */
        .btn-back i {
            margin-right: 10px;
        }

    </style>
</head>
<body>
    <div class="container">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Galeri Foto</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Akun
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="index.php">Beranda</a>
                            <a class="dropdown-item" href="dashboardg.php">Admin Gallery</a>
                            <a class="dropdown-item" href="logout.php">Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <h1 class="my-4">Daftar Foto</h1>
        
        <!-- Tombol tambah foto dan pencarian -->
        <div class="d-flex justify-content-between mb-3">
            <a href="tambah.php" class="btn btn-primary">Tambah Foto</a>
            <form method="post" action="">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Cari foto..." name="search" value="<?php echo htmlspecialchars($search); ?>">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">Search</button>
                    </div>
                </div>
            </form>
        </div>

        <table class="table table-custom table-striped">
            <thead>
                <tr>
                    <th>FotoID</th> <!-- Kolom FotoID -->
                    <th>Judul Foto</th>
                    <th>Deskripsi</th>
                    <th>Tanggal Unggah</th>
                    <th>Gambar</th>
                    <th>Aksi</th> <!-- Kolom aksi -->
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['FotoID']) . "</td>"; // Menampilkan FotoID
                        echo "<td>" . htmlspecialchars($row['JudulFoto']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['DeskripsiFoto']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['TanggalUnggah']) . "</td>";
                        echo "<td>";
                        if (!empty($row['LokasiFile'])) {
                            echo "<img src='img/" . htmlspecialchars($row['LokasiFile']) . "' alt='" . htmlspecialchars($row['JudulFoto']) . "' class='img-thumbnail'>";
                        } else {
                            echo "No Image";
                        }
                        echo "</td>";
                        echo "<td>";
                        echo "<a href='view.php?FotoID=" . htmlspecialchars($row['FotoID']) . "' class='btn btn-info btn-sm'>View</a> ";
                        echo "<a href='edit.php?FotoID=" . htmlspecialchars($row['FotoID']) . "' class='btn btn-warning btn-sm'>Edit</a> ";
                        echo "<a href='delete.php?FotoID=" . htmlspecialchars($row['FotoID']) . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Apakah Anda yakin ingin menghapus foto ini?\");'>Delete</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Tidak ada data</td></tr>"; // Update colspan sesuai jumlah kolom
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS dan dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function confirmLogout() {
            // Tampilkan dialog konfirmasi
            if (confirm("Apakah Anda yakin ingin logout?")) {
                // Jika pengguna mengklik OK, arahkan ke halaman logout
                window.location.href = 'logout.php';
            }
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
