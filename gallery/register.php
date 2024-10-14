<?php
session_start();
include 'includes/koneksi.php'; // Koneksi ke database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $namaLengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $role = mysqli_real_escape_string($conn, $_POST['role']); // Ambil data role

    // Meng-hash password sebelum disimpan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query untuk mengecek apakah username sudah ada
    $query = "SELECT * FROM user WHERE Username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $error = "Username sudah digunakan!";
    } else {
        // Query untuk memasukkan data pengguna baru dengan role
        $query_insert = "INSERT INTO user (Username, Password, Email, NamaLengkap, Alamat, role) 
                         VALUES ('$username', '$hashed_password', '$email', '$namaLengkap', '$alamat', '$role')";
        if (mysqli_query($conn, $query_insert)) {
            // Redirect ke halaman register dengan parameter success
            header("Location: register.php?success=true");
            exit();
        } else {
            $error = "Terjadi kesalahan saat mendaftar. Silakan coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #e3f2e1;
        }
        .header {
            background-color: #228B22;
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header h1 {
            margin: 0;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2);
        }
        header nav a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            margin-left: 15px;
        }
        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            transition: transform 0.5s ease-in-out; /* Durasi transisi halus */
            transform: translateX(0); /* Pastikan posisi awal diatur ke 0 */
        }
        .card-body {
            padding: 30px;
        }
        .btn-success {
            background-color: #228B22;
            border: none;
            transition: background-color 0.3s;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .btn-success:hover {
            background-color: #1a6a1a;
        }
        footer {
            background-color: #228B22;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        .login-link a {
            color: #228B22;
            font-weight: bold;
            text-decoration: none;
            transition: color 0.3s;
        }
        .login-link a:hover {
            color: #1a6a1a;
        }

        select.form-control {
            display: block;
            width: 100%;
            padding: .375rem 1.75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: .25rem;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.075);
        }
    </style>
</head>
<body>

<header class="header">
    <h1>Daftar Akun Baru</h1>
    <nav>
        <a href="index.php">Home</a>
    </nav>
</header>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">

                    <!-- Notifikasi sukses setelah berhasil registrasi -->
                    <?php if (isset($_GET['success']) && $_GET['success'] == 'true'): ?>
                        <div class="alert alert-success">
                            Pendaftaran berhasil! Silakan <a href="login.php" class="alert-link">login di sini</a>.
                        </div>
                    <?php endif; ?>

                    <!-- Menampilkan notifikasi error jika ada -->
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST" action="register.php">
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" class="form-control" name="username" placeholder="Masukkan username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" class="form-control" name="password" placeholder="Masukkan password" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" name="email" placeholder="Masukkan email" required>
                        </div>
                        <div class="form-group">
                            <label for="nama_lengkap">Nama Lengkap:</label>
                            <input type="text" class="form-control" name="nama_lengkap" placeholder="Masukkan nama lengkap" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat:</label>
                            <input type="text" class="form-control" name="alamat" placeholder="Masukkan alamat" required>
                        </div>

                        <!-- Dropdown untuk memilih role -->
                        <div class="form-group">
                            <label for="role">Daftar Sebagai:</label>
                            <select class="form-control" name="role" required>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success btn-block">Daftar</button>
                    </form>

                    <div class="login-link text-center mt-3">
                        <p>Sudah punya akun? <a href="login.php" id="loginBtn">Login di sini</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<footer>
    <p class="mb-0">&copy; 2024 Nature Gallery</p>
</footer>

<script>
    // Animasi transisi untuk link login
    document.getElementById('loginBtn').addEventListener('click', function(event) {
        event.preventDefault();
        const card = document.querySelector('.card');
        
        // Menambahkan kelas untuk animasi keluar
        card.style.transform = 'translateX(100%)';
        
        // Waktu tunggu sebelum diarahkan ke halaman login
        setTimeout(function() {
            window.location.href = 'login.php';
        }, 500); // Perpanjang waktu tunggu untuk mencocokkan durasi transisi
    });
</script>

</body>
</html>
