<?php
// Hubungkan dengan file konfigurasi database
include 'config.php'; 

// Ambil data dari AJAX
$fotoid = $_POST['fotoid'];
$comment = $_POST['comment'];
$userid = $_SESSION['UserID']; // Mengambil UserID dari session login

// Pastikan semua data terisi
if (empty($fotoid) || empty($comment) || empty($userid)) {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
    exit;
}

// Query untuk memasukkan komentar ke database
$query = "INSERT INTO komentarfoto (FotoID, UserID, IsiKomentar, TanggalKomentar) 
          VALUES ('$fotoid', '$userid', '$comment', NOW())";

$result = mysqli_query($conn, $query);

// Cek apakah query berhasil
if ($result) {
    // Jika berhasil, kembalikan respons sukses
    echo json_encode([
        'status' => 'success',
        'username' => $_SESSION['Username'], // Gunakan username dari session
        'tanggalkomentar' => date('Y-m-d H:i:s') // Format tanggal komentar
    ]);
} else {
    // Jika gagal, berikan pesan error
    echo json_encode(['status' => 'error', 'message' => 'Gagal menambahkan komentar']);
}
?>
