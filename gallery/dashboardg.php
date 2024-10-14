<?php
include "includes/koneksi.php";
session_start();
if (!isset($_SESSION["username"])) {
    header("location:login.php");
}
$user_role = $_SESSION['role'] ?? ''; // Mengambil role pengguna dari sesi

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ALBUM</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* CSS for the navbar */
        .nav {
            background: rgba(255, 255, 255, 0.7); /* White with some transparency */
            backdrop-filter: blur(10px); /* Blurring effect */
            border-radius: 8px; /* Rounded corners */
            padding: 10px 20px; /* Padding for better spacing */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        /* Navbar links styling */
        .nav-link {
            color: #343a40; /* Dark color for links */
            font-weight: bold; /* Bold text */
            transition: color 0.3s ease; /* Smooth transition for hover effect */
        }

        .nav-link:hover {
            color: #007bff; /* Change color on hover */
        }

        table {
            width: 100%;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            text-align: center;
        }

        th, td {
            padding: 10px;
        }
        .nav-item.dropdown:hover .dropdown-menu {
    display: block;
    position: relative;
    z-index: 10000; /* pastikan dropdown berada di atas elemen lain */
}

    </style>
</head>
<body>
<ul class="nav nav-pills mb-3 justify-content-center">
    <li class="nav-item"><a class="nav-link" href="home.php">HOME</a></li>
    <li class="nav-item"><a class="nav-link" href="album.php">ALBUM</a></li>
    <li class="nav-item"><a class="nav-link" href="foto.php">FOTO</a></li>

    <!-- Cek apakah pengguna adalah admin -->
    <?php if ($user_role == 'admin'): ?>
        <li class="nav-item dropdown" style="position: relative; z-index: 9999;">
            <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                ADMIN
            </a>
            <ul class="dropdown-menu" aria-labelledby="adminDropdown" style="z-index: 10000;">
                <li><a class="dropdown-item" href="admingaleri.php">Admin Galeri</a></li><br>
                <li><a class="dropdown-item" href="adminfoto.php">Admin Foto</a></li>
            </ul>
        </li>
    <?php endif; ?>

    <li class="nav-item"><a class="nav-link" href="logout.php">LOGOUT</a></li>
</ul>

    <div class="container">
        <!-- Form Pencarian -->
        <form method="GET" action="">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Cari Album..." name="search" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
                <button class="btn btn-primary" type="submit">Cari</button>
            </div>
        </form>

        <h5>Daftar Album</h5>
        <table>
            <thead>
                <tr>
                    <th>AlbumID</th> <!-- Menambahkan kolom AlbumID -->
                    <th>Nama Album</th>
                    <th>Deskripsi</th>
                    <th>Tanggal Dibuat</th> <!-- Perbaikan nama kolom -->
                    <?php if ($_SESSION['role'] == 'admin') { ?>
                        <th>Uploader</th> <!-- Menampilkan uploader hanya untuk admin -->
                    <?php } ?>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Ambil input pencarian
                $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
                if ($_SESSION['role'] == 'admin') {
                    $sql = mysqli_query($conn, "
                        SELECT album.*, user.NamaLengkap 
                        FROM album 
                        JOIN user ON album.userid=user.userid
                        WHERE album.albumid LIKE '%$search%' 
                        OR album.namaalbum LIKE '%$search%' 
                        OR album.deskripsi LIKE '%$search%' 
                        OR user.namalengkap LIKE '%$search%'
                    ");
                } else {
                    // Query untuk user biasa
                    $UserID = $_SESSION['userid'];
                    $sql = mysqli_query($conn, "
                        SELECT album.* 
                        FROM album 
                        WHERE album.userid='$UserID'
                        AND (album.albumid LIKE '%$search%' 
                        OR album.namaalbum LIKE '%$search%' 
                        OR album.deskripsi LIKE '%$search%')
                    ");
                }

                // Tampilkan hasil pencarian
                while ($data = mysqli_fetch_array($sql)) {
                ?>
                <tr>
                    <td><?=$data['albumid']?></td> <!-- Menampilkan AlbumID -->
                    <td><?=$data['namaalbum']?></td>
                    <td><?=$data['deskripsi']?></td>
                    <td><?=$data['tanggaldibuat']?></td> <!-- Mengganti menjadi tanggaldibuat -->
                    <?php if ($_SESSION['level'] == 'admin') { ?>
                        <td><?=$data['namalengkap']?></td>
                    <?php } ?>
                    <td>
                        <a href="update-album.php?albumid=<?=$data['albumid']?>" class="btn btn-warning btn-sm">Edit</a>
                        <?php if ($_SESSION['level'] == 'admin') { ?>
                            <a href="hapus-album.php?albumid=<?=$data['albumid']?>" class="btn btn-danger btn-sm">Hapus</a>
                        <?php } ?>
                    </td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>