<?php
include 'includes/koneksi.php';
session_start();

if (isset($_POST['FotoID']) && isset($_POST['action'])) {
    $foto_id = $_POST['FotoID'];
    $action = $_POST['action'];

    if ($action == 'like') {
        // Update jumlah like (menambahkan 1)
        $sql = "UPDATE foto SET jumlah_like = jumlah_like + 1 WHERE FotoID = '$foto_id'";
    } else if ($action == 'unlike') {
        // Update jumlah like (mengurangi 1)
        $sql = "UPDATE foto SET jumlah_like = jumlah_like - 1 WHERE FotoID = '$foto_id' AND jumlah_like > 0";
    }

    if ($conn->query($sql) === TRUE) {
        // Kembalikan jumlah like terbaru
        $query = "SELECT jumlah_like FROM foto WHERE FotoID = '$foto_id'";
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        echo $row['jumlah_like'];
    } else {
        echo "error";
    }
}

$conn->close();
?>
