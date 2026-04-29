<?php
session_start();
include "api/koneksi.php";

// Proteksi: Jika belum login, dialihkan ke login.php
if(!isset($_COOKIE['nama'])){
    header("Location: login.php");
    exit();
}

$nama_user = $_COOKIE['nama'];

// Ambil data transaksi milik user yang sedang login
$query = mysqli_query($koneksi, "SELECT * FROM laporan_pesanan WHERE nama_user = '$nama_user' ORDER BY id_pesanan DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Transaksi Tiket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .ticket-card {
            border-left: 5px solid #0d6efd;
            background: white;
        }
        .status-badge {
            font-size: 0.8rem;
        }
    </style>
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-primary mb-4 shadow">
        <div class="container">
            <a class="navbar-brand" href="user_dashboard.php">⬅ Kembali ke Dashboard</a>
        </div>
    </nav>

    <div class="container">
        <div class="row mb-4">
            <div class="col">
                <h2>Tiket Saya</h2>
                <p class="text-muted">Berikut adalah daftar tiket yang berhasil Anda pesan.</p>
            </div>
        </div>

        <div class="row">
            <?php 
            if(mysqli_num_rows($query) > 0) {
                while($row = mysqli_fetch_assoc($query)) {
            ?>
                <div class="col-md-6 mb-3">
                    <div class="card ticket-card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="card-title mb-1"><?php echo $row['nama_wisata']; ?></h5>
                                    <p class="text-muted small mb-2">ID Transaksi: #TK-00<?php echo $row['id_pesanan']; ?></p>
                                </div>
                                <span class="badge bg-success status-badge">BERHASIL</span>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted d-block">Pemesan:</small>
                                    <strong><?php echo $row['nama_user']; ?></strong>
                                </div>
                                <div class="col-6 text-end">
                                    <small class="text-muted d-block">Harga:</small>
                                    <strong class="text-primary">Rp<?php echo number_format($row['harga'], 0, ',', '.'); ?></strong>
                                </div>
                            </div>
                            <div class="mt-3 small text-muted">
                                Dipesan pada: <?php echo date('d M Y, H:i', strtotime($row['tanggal_pesan'])); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php 
                }
            } else {
                echo '<div class="col-12 text-center mt-5">
                        <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" width="100" class="mb-3 opacity-50">
                        <p class="text-muted">Anda belum memiliki riwayat transaksi.</p>
                        <a href="user_dashboard.php" class="btn btn-primary">Beli Tiket Sekarang</a>
                      </div>';
            }
            ?>
        </div>
    </div>

    <footer class="text-center py-5 text-muted small">
        &copy; 2026 Tiket Wisata Online
    </footer>

</body>
</html>