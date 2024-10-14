<?php
// Koneksi ke database
include 'includes/koneksi.php';
session_start(); // Memulai sesi

// Ambil data foto dari database
$query = "SELECT * FROM foto ORDER BY TanggalUnggah DESC";
$result = mysqli_query($conn, $query);

// Cek apakah pengguna sudah login
$loggedIn = isset($_SESSION['username']); // Cek apakah sesi login tersedia
$user_id = $_SESSION['user_id'] ?? null; // Ambil user_id dari sesi jika ada
$user_role = $_SESSION['role'] ?? ''; // Ambil peran pengguna dari sesi, default ke 'user'

// Menangani upload foto
if ($loggedIn && isset($_POST['upload'])) {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $file = $_FILES['file'];

    // Mendapatkan informasi file
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];

    // Validasi file
    $allowed = ['image/jpeg', 'image/png', 'image/gif'];
    if (in_array($fileType, $allowed) && $fileError === 0) {
        if ($fileSize < 1000000) { // Batas ukuran 1MB
            // Buat nama file unik
            $fileNameNew = uniqid('', true) . "." . pathinfo($fileName, PATHINFO_EXTENSION);
            $fileDestination = 'img/' . $fileNameNew; // Pastikan folder img ada dan memiliki izin tulis

            // Pindahkan file ke folder
            move_uploaded_file($fileTmpName, $fileDestination);

            // Simpan data ke database
            $insertQuery = "INSERT INTO foto (JudulFoto, DeskripsiFoto, LokasiFile, TanggalUnggah, liked_by, jumlah_like) VALUES ('$judul', '$deskripsi', '$fileNameNew', NOW(), '', 0)";
            mysqli_query($conn, $insertQuery);
            echo "<script>alert('Foto berhasil diupload!');</script>"; // Notifikasi berhasil
        } else {
            echo "<script>alert('Ukuran file terlalu besar!');</script>"; // Notifikasi error ukuran
        }
    } else {
        echo "<script>alert('Format file tidak didukung atau terjadi kesalahan saat upload.');</script>"; // Notifikasi error format
    }
}



// Menangani like
if ($loggedIn && isset($_POST['like'])) {
    $foto_id = $_POST['foto_id'];
    
    // Ambil data foto terkait untuk cek liked_by
    $queryFoto = "SELECT liked_by, jumlah_like FROM foto WHERE FotoID='$foto_id'";
    $resultFoto = mysqli_query($conn, $queryFoto);
    $fotoData = mysqli_fetch_assoc($resultFoto);

    // Ubah liked_by menjadi array
    $likedBy = !empty($fotoData['liked_by']) ? explode(',', $fotoData['liked_by']) : [];
    
    // Cek apakah user sudah memberikan like
    if (!in_array($user_id, $likedBy)) {
        // Jika belum, tambahkan user_id ke array likedBy
        $likedBy[] = $user_id;
        $likedByString = implode(',', $likedBy); // Ubah array ke string untuk disimpan
        
        // Update tabel foto
        $updateQuery = "UPDATE foto SET liked_by='$likedByString', jumlah_like=jumlah_like+1 WHERE FotoID='$foto_id'";
        mysqli_query($conn, $updateQuery);
    }
}

// Menangani unlike
if ($loggedIn && isset($_POST['unlike'])) {
    $foto_id = $_POST['foto_id'];
    
    // Ambil data foto terkait untuk cek liked_by
    $queryFoto = "SELECT liked_by, jumlah_like FROM foto WHERE FotoID='$foto_id'";
    $resultFoto = mysqli_query($conn, $queryFoto);
    $fotoData = mysqli_fetch_assoc($resultFoto);

    // Ubah liked_by menjadi array
    $likedBy = !empty($fotoData['liked_by']) ? explode(',', $fotoData['liked_by']) : [];
    
    // Cek apakah user sudah memberikan like
    if (in_array($user_id, $likedBy)) {
        // Jika sudah, hapus user_id dari array likedBy
        $likedBy = array_diff($likedBy, [$user_id]);
        $likedByString = implode(',', $likedBy); // Ubah array ke string untuk disimpan
        
        // Update tabel foto
        $updateQuery = "UPDATE foto SET liked_by='$likedByString', jumlah_like=jumlah_like-1 WHERE FotoID='$foto_id'";
        mysqli_query($conn, $updateQuery);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FIFA League Gallery</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .header {
            background-image: url('footb1.jpg'); /* Ganti dengan path gambar background */
            background-size: cover;
            background-position: center;
            height: 80vh;
            color: white;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }
        .header h1 {
            font-size: 3.5rem;
            font-weight: bold;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
        }
        .search-bar {
            max-width: 600px;
            margin: 20px auto 0;
        }
        .search-bar input {
            padding: 15px;
            border-radius: 50px;
            width: 100%;
            border: none;
        }
        .search-bar button {
            margin-left: -50px;
            background-color: transparent;
            border: none;
            cursor: pointer;
            color: white;
        }
        .nav-links {
            margin-top: 20px;
        }
        .nav-links a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
            font-weight: bold;
        }
        .login-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: green;
            padding: 10px 20px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        .gallery {
            padding: 40px 0;
        }
        .gallery-item {
            margin-bottom: 30px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .gallery-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .card-img-top {
            height: 250px;
            object-fit: cover;
            border-bottom: 1px solid #ddd;
        }
        .card-title {
            color: #228B22;
            font-size: 1.25rem;
        }
        footer {
            background-color: #228B22;
            color: white;
            padding: 20px;
            text-align: center;
            margin-top: 50px;
        }

        /* Tambahkan CSS untuk mengatur posisi tombol Admin dan Logout */
.admin-logout-container {
    position: absolute;
    top: 20px;
    right: 20px;
    display: flex;
    gap: 10px; /* Jarak antara tombol Admin dan Logout */
}

.admin-btn, .logout-btn {
    background-color: green;
    padding: 10px 20px;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
}

.logout-btn {
    background-color: red; /* Warna merah untuk tombol Logout */
}

.login-btn {
    background-color: green;
    padding: 10px 20px;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
}

.download-btn {
    position: absolute;
    bottom: 10px;
    right: 10px;
    background-color: #007bff;
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    font-weight: bold;
}

.admin-likes {
    font-weight: bold;
    color: #228B22;
}



        </style>
</head>
<body>

<header class="header">
    <!-- Tambahkan div untuk membungkus tombol Admin dan Logout -->
    <div class="admin-logout-container">
        <?php if ($loggedIn): ?>
            <button class="admin-btn" onclick="window.location.href='dashboard.php'">Control</button>
            <button class="logout-btn" onclick="confirmLogout()">Logout</button>
            <?php else: ?>
            <button class="login-btn" onclick="window.location.href='login.php'">Login</button>
        <?php endif; ?>
    </div>
    
    <h1>Stunning Free Footballers Photos</h1>
    <p>For websites and commercial use</p>
    
    
    <div class="nav-links">
    <a href="manchester_united.php">Manchester United</a>
    <a href="barcelona.php">Barcelona</a>
    <a href="real_madrid.php">Real Madrid</a>
    <a href="legenda.php">Legenda</a>
    <a href="negara.php">Negara</a>
</div>

</header>

<div class="container">
    <!-- Logika ini di bagian upload foto -->
<?php if ( $user_role == 'user'): ?>
    <h2 class="mt-5">Upload Foto</h2>
    <form method="post" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label for="judul">Judul Foto:</label>
            <input type="text" name="judul" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="deskripsi">Deskripsi Foto:</label>
            <textarea name="deskripsi" class="form-control" rows="4" required></textarea>
        </div>
        <div class="form-group">
            <label for="file">Pilih Foto:</label>
            <input type="file" name="file" class="form-control-file" accept="image/*" required>
        </div>
        <button type="submit" name="upload" class="btn btn-primary">Upload</button>
    </form>
<?php endif; ?>

</div>



    <!-- Galeri Foto -->
    <div class="container gallery">
    <div class="row">
    <?php while($row = mysqli_fetch_assoc($result)): ?>
        <div class="col-lg-4 col-md-6 col-sm-12 gallery-item">
            <div class="card">
            <a href="pages/komentar.php?foto_id=<?php echo $row['FotoID']; ?>">
                    <img src="img/<?php echo $row['LokasiFile']; ?>" class="card-img-top" alt="<?php echo $row['JudulFoto']; ?>">
                </a>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $row['JudulFoto']; ?></h5>
                    <p class="card-text"><?php echo substr($row['DeskripsiFoto'], 0, 100); ?>...</p>
                    
                    <?php if ($loggedIn && $user_role !== 'admin'): ?>
                    <?php
                        // Cek apakah user sudah memberikan like
                        $likedBy = !empty($row['liked_by']) ? explode(',', $row['liked_by']) : [];
                    ?>
                    <?php if (in_array($user_id, $likedBy)): ?>
                        <form method="post" action="" onsubmit="setTimeout(function(){ window.location.reload(); }, 10);">
                            <input type="hidden" name="foto_id" value="<?php echo $row['FotoID']; ?>">
                            <button type="submit" name="unlike" class="btn btn-danger">
                                <i class="fas fa-heart"></i> Unlike
                            </button>
                        </form>
                    <?php else: ?>
                        <form method="post" action="" onsubmit="setTimeout(function(){ window.location.reload(); }, 10);">
                            <input type="hidden" name="foto_id" value="<?php echo $row['FotoID']; ?>">
                            <button type="submit" name="like" class="btn btn-warning">
                                <i class="far fa-heart"></i> Like
                            </button>
                        </form>
                    <?php endif; ?>
                    <?php else: ?>
                        <p><?php echo $row['jumlah_like']; ?> Likes</p>
                    <?php endif; ?>
                    
                    <p class="admin-likes">
                        <?php echo $row['jumlah_like']; ?> Likes
                    </p>

                    <a href="img/<?php echo $row['LokasiFile']; ?>" download class="btn btn-primary download-btn">
                        <i class="fas fa-download"></i> 
                    </a>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
    </div>
</div>


    <!-- Footer -->
    <footer>
        <p>&copy; 2024 FIFA League Gallery. All rights reserved.</p>
    </footer>

   <script>
    // Function untuk membuka modal dan mengambil data dari view.php
    function openPhotoModal(fotoID) {
    $.ajax({
        url: 'view.php', // Lokasi file view.php
        type: 'GET',
        data: { kodehw: fotoID }, // Mengirim foto_id sebagai parameter
        success: function(response) {
            // Menampilkan response di dalam modal
            $('#modalContent').html(response);
            // Memunculkan modal
            $('#photoModal').modal('show');

            // Ambil komentar dari database
            $.ajax({
                url: 'pages/komentar.php', // Mengambil komentar
                type: 'GET',
                data: { foto_id: fotoID },
                success: function(comments) {
                    $('#commentSection').html(comments);
                },
                error: function() {
                    alert('Gagal memuat komentar.');
                }
            });

            // Set foto_id ke input
            $('#foto_id_input').val(fotoID);
        },
        error: function() {
            alert('Gagal memuat detail foto.');
        }
    });
}


    // Event listener untuk tombol close modal
    $('#photoModal').on('hidden.bs.modal', function () {
        $('#modalContent').html(''); // Kosongkan konten modal setelah ditutup
    });

    function confirmLogout() {
        // Tampilkan dialog konfirmasi
        if (confirm("Apakah Anda yakin ingin logout?")) {
            // Jika pengguna mengklik OK, arahkan ke halaman logout
            window.location.href = 'logout.php';
        }
    }

    
</script>


<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>



</body>
</html>