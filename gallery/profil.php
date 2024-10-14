<?php
// Koneksi ke database
include 'includes/koneksi.php';

// Ambil data user dan album dari database (contoh user ID 1)
$user_id = 1;
$query_user = "SELECT * FROM user WHERE UserID = $user_id";
$result_user = mysqli_query($conn, $query_user);
$user = mysqli_fetch_assoc($result_user);

// Ambil data album
$query_album = "SELECT * FROM album WHERE UserID = $user_id";
$result_album = mysqli_query($conn, $query_album);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Profil Saya</title>
</head>
<body style="font-family: 'Arial', sans-serif; background-color: #e3f2e1; color: #333; margin: 0; padding: 0;">

    <header style="background-color: #228B22; color: white; padding: 20px; text-align: center;">
        <h1 style="margin: 0;">Profil Saya</h1>
        <nav>
            <a href="index.php" style="margin: 0 15px; color: white; text-decoration: none;">Home</a>
        </nav>
    </header>

    <div style="padding: 20px;">
        <h2 style="color: #228B22;"><?php echo $user['NamaLengkap']; ?></h2>
        <p>Email: <?php echo $user['Email']; ?></p>
        <p>Alamat: <?php echo $user['Alamat']; ?></p>

        <h3 style="color: #228B22;">Album Saya</h3>
        <ul>
            <?php while($row = mysqli_fetch_assoc($result_album)): ?>
                <li><?php echo $row['NamaAlbum']; ?> - Dibuat pada: <?php echo $row['TanggalDibuat']; ?></li>
            <?php endwhile; ?>
        </ul>
    </div>

    <footer style="background-color: #228B22; color: white; text-align: center; padding: 10px 0; position: fixed; bottom: 0; width: 100%;">
        <p>&copy; 2024 Nature Gallery</p>
    </footer>
</body>
</html>
