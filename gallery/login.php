<?php
session_start();
include 'includes/koneksi.php'; // Koneksi ke database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query untuk mengecek username dan password
    $query = "SELECT * FROM user WHERE Username = '$username' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        // Verifikasi password
        if (password_verify($password, $user['Password'])) {
            // Simpan informasi penting ke sesi
            $_SESSION['username'] = $user['Username']; // Simpan username
            $_SESSION['user_id'] = $user['UserID']; // Simpan user_id
            $_SESSION['role'] = $user['role']; // Simpan role dari database

            // Cek role pengguna
            if ($_SESSION['role'] == 'user') {
                // Jika user, arahkan ke home.php
                header("Location: home.php");
                exit();
            } elseif ($_SESSION['role'] == 'admin') {
                // Jika admin, arahkan ke dashboard admin
                header("Location: index.php");
                exit();
            }
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>



<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f8ff;
            color: #333;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #228B22;
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        header h1 {
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2);
        }
        header nav a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            margin-left: 15px;
        }
        .login-form {
            max-width: 400px;
            margin: 50px auto;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        .login-form h2 {
            text-align: center;
            color: #228B22;
            margin-bottom: 20px;
        }
        .login-form form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .login-form label {
            font-weight: bold;
            color: #555;
        }
        .login-form input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
            transition: border-color 0.3s;
        }
        .login-form input:focus {
            border-color: #228B22;
        }
        .login-form input::placeholder {
            color: #aaa;
        }
        .login-form button {
            padding: 10px;
            background-color: #228B22;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .login-form button:hover {
            background-color: #1a6a1a;
        }
        .login-form p {
            color: red;
            text-align: center;
        }
        .login-form .register-link {
            text-align: center;
            margin-top: 10px;
        }
        .login-form .register-link a {
            color: #228B22;
            font-weight: bold;
            text-decoration: none;
            transition: color 0.3s;
        }
        .login-form .register-link a:hover {
            color: #1a6a1a;
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
    </style>
</head>
<body>

    <header>
        <h1>Login ke FIFA League Gallery</h1>
        <nav>
            <a href="index.php">Home</a>
        </nav>
    </header>

    <div class="login-form">
        <h2>Masuk ke Akun Anda</h2>
        <?php if (isset($error)): ?>
            <p><?php echo $error; ?></p>
        <?php endif; ?>
        
        <form method="POST" action="login.php">
            <label for="username">Username:</label>
            <input type="text" name="username" placeholder="Masukkan username Anda" required>

            <label for="password">Password:</label>
            <input type="password" name="password" placeholder="Masukkan password Anda" required>
            

            <button type="submit">Login</button>
        </form>

        <div class="register-link">
            <p>Belum punya akun? <a href="register.php" id="registerBtn">Daftar di sini</a></p>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 FIFA League Gallery. All rights reserved.</p>
    </footer>

    <script>
        // Animasi transisi untuk link register
        document.getElementById('registerBtn').addEventListener('click', function(event) {
            event.preventDefault();
            document.querySelector('.login-form').style.transform = 'translateX(-100%)';
            setTimeout(function() {
                window.location.href = 'register.php';
            }, 300); // Waktu tunggu sebelum diarahkan ke halaman register
        });
    </script>

</body>
</html>
