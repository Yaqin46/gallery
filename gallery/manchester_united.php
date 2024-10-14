<?php
// Koneksi ke database
include 'includes/koneksi.php';
session_start();

// Ambil data foto dengan kategori "Manchester United"
$query = "SELECT * FROM foto WHERE kategori='Manchester United' ORDER BY TanggalUnggah DESC";
$result = mysqli_query($conn, $query);

// Cek apakah pengguna sudah login
$loggedIn = isset($_SESSION['username']); 
$user_id = $_SESSION['user_id'] ?? null; 
$user_role = $_SESSION['role'] ?? 'user';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Album Manchester United</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .back-btn { margin-top: 20px; margin-bottom: 20px; }
        .like-btn { cursor: pointer; color: #007bff; }
        .liked { color: red; } /* Menandai tombol yang sudah di-like */
        .btn-download { margin-top: 15px; } /* Menambahkan jarak pada tombol Download */
    </style>
</head>
<body>

<div class="container">
    <!-- Tombol kembali ke index.php -->
    <div class="back-btn">
        <a href="index.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <h2 class="text-center my-4">Album Manchester United</h2>
    <div class="row">
        <?php while($row = mysqli_fetch_assoc($result)): ?>
        <div class="col-lg-4 col-md-6 col-sm-12 gallery-item">
            <div class="card">
                <img src="img/<?php echo $row['LokasiFile']; ?>" class="card-img-top" alt="<?php echo $row['JudulFoto']; ?>" style="height: 250px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $row['JudulFoto']; ?></h5>
                    <p class="card-text"><?php echo substr($row['DeskripsiFoto'], 0, 100); ?>...</p>
                    
                    <!-- Tombol Like/Unlike dan jumlah like -->
                    <div>
                        <span id="like-count-<?php echo $row['FotoID']; ?>"><?php echo $row['jumlah_like']; ?></span> Likes
                        <i class="fa fa-thumbs-up like-btn" id="like-btn-<?php echo $row['FotoID']; ?>" data-id="<?php echo $row['FotoID']; ?>" data-action="like"></i>
                        <i class="fa fa-thumbs-down like-btn" id="unlike-btn-<?php echo $row['FotoID']; ?>" data-id="<?php echo $row['FotoID']; ?>" data-action="unlike"></i>
                    </div>
                    
                    <!-- Tombol Download dengan jarak -->
                    <a href="img/<?php echo $row['LokasiFile']; ?>" download class="btn btn-primary btn-download">Download</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        // Periksa status like/unlike dari localStorage
        $('.like-btn').each(function() {
            var FotoID = $(this).data('id');
            var liked = localStorage.getItem('liked_' + FotoID);
            if (liked === 'true') {
                $('#like-btn-' + FotoID).addClass('liked');
                $('#unlike-btn-' + FotoID).removeClass('liked');
            } else if (liked === 'false') {
                $('#unlike-btn-' + FotoID).addClass('liked');
                $('#like-btn-' + FotoID).removeClass('liked');
            }
        });

        $('.like-btn').click(function() {
            var FotoID = $(this).data('id');
            var action = $(this).data('action');
            var likeCountElement = $('#like-count-' + FotoID);
            var likeCount = parseInt(likeCountElement.text()); // Ambil jumlah like saat ini
            
            if (action === 'like') {
                // Jika tombol Like ditekan
                if ($(this).hasClass('liked')) {
                    // Jika sudah di-like, jangan lakukan apa-apa
                    return;
                }
                likeCount += 1; // Tambahkan 1 ke jumlah like
                $(this).addClass('liked'); // Tandai tombol Like sebagai aktif
                $('#unlike-btn-' + FotoID).removeClass('liked'); // Hapus tanda pada tombol Unlike
                localStorage.setItem('liked_' + FotoID, 'true'); // Simpan status like
            } else {
                // Jika tombol Unlike ditekan
                if ($(this).hasClass('liked')) {
                    // Jika sudah di-unlike, jangan lakukan apa-apa
                    return;
                }
                likeCount -= 1; // Kurangi 1 dari jumlah like
                $(this).addClass('liked'); // Tandai tombol Unlike sebagai aktif
                $('#like-btn-' + FotoID).removeClass('liked'); // Hapus tanda pada tombol Like
                localStorage.setItem('liked_' + FotoID, 'false'); // Simpan status unlike
            }
            
            // Kirim permintaan AJAX untuk menyimpan perubahan di database
            $.ajax({
                url: 'like_unlike.php',
                type: 'POST',
                data: { FotoID: FotoID, action: action },
                success: function(response) {
                    if (response !== "error") {
                        likeCountElement.text(likeCount); // Update jumlah like di tampilan
                    }
                }
            });
        });
    });
</script>

</body>
</html>
