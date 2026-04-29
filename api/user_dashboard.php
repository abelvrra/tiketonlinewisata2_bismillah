<?php
session_start();
include "koneksi.php";

// Proteksi: Hanya User yang boleh masuk
if(!isset($_COOKIE['nama']) || $_COOKIE['role'] != 'user'){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; }
        .card { border-radius: 12px; transition: 0.3s; border: none; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow mb-4">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="fas fa-ticket-alt"></i> Tiket Wisata</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="transaksi.php">Riwayat</a>
                <a class="nav-link" href="profil.php">Profil Saya</a>
                <a class="nav-link text-warning" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Halo, <?php echo $_COOKIE['nama']; ?>!</h2>
        <p class="text-muted">Pilih destinasi favoritmu</p>
    </div>

   <div class="row">
    <?php
    // Mengambil data dari tabel tempat_wisata
    $get_wisata = mysqli_query($koneksi, "SELECT * FROM tempat_wisata");
    if($get_wisata && mysqli_num_rows($get_wisata) > 0){
        while($data = mysqli_fetch_assoc($get_wisata)) :
    ?>
    <div class="col-md-4 mb-4">
    <div class="card p-3 text-center shadow-sm h-100 border-0">
        <div class="card-body">
            <div class="mb-3">
                <i class="fas fa-map-marked-alt fa-3x text-primary"></i>
            </div>
            <h5 class="fw-bold"><?= $data['nama_wisata']; ?></h5>
            <p class="text-success fw-bold">Rp<?= number_format($data['harga'], 0, ',', '.'); ?></p>
            
            <a href="proses_beli.php?wisata=<?= $data['nama_wisata']; ?>&harga=<?= $data['harga']; ?>" class="btn btn-primary w-100">
                <i class="fas fa-shopping-cart me-2"></i>Beli Tiket
            </a>
        </div>
    </div>
</div>
    <?php 
        endwhile; 
    } else {
        echo "<div class='text-center p-5'><p class='text-muted'>Maaf, saat ini belum ada tiket yang tersedia.</p></div>";
    }
    ?>
</div>