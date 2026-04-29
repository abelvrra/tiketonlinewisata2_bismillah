<?php
session_start();
include "koneksi.php";

// 1. PROTEKSI: Cek apakah sudah login DAN apakah dia admin
if(!isset($_COOKIE['login']) || $_COOKIE['role'] != 'admin'){
    echo "<script>alert('Akses Ditolak!'); window.location='login.php';</script>";
    exit();
}

// 2. LOGIKA SIMPAN DESTINASI (Ke tabel tempat_wisata)
if(isset($_POST['tambah_wisata'])){
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_wisata']);
    $harga = $_POST['harga'];
    
    // Query simpan tanpa kolom 'ikon' agar aman dari error kolom tidak ditemukan
    $query_tambah = "INSERT INTO tempat_wisata (nama_wisata, harga) VALUES ('$nama', '$harga')";
    
    if(mysqli_query($koneksi, $query_tambah)){
        echo "<script>alert('Destinasi Berhasil Disimpan!'); window.location='admin_dashboard.php';</script>";
    } else {
        echo "Gagal menyimpan ke tempat_wisata: " . mysqli_error($koneksi);
    }
}

// 3. LOGIKA HAPUS DESTINASI
if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM tempat_wisata WHERE id_wisata=$id");
    header("Location: admin_dashboard.php");
}

// 4. Simulasi Status API Cuaca
$api_weather = "Cerah - 29°C"; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - Monitoring System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .card { border-radius: 12px; }
        .bg-danger-custom { background-color: #dc3545; }
    </style>
</head>
<body class="bg-light text-dark">
    <nav class="navbar navbar-dark bg-danger shadow mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">ADMIN PANEL - Monitoring System</a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3 small">Status API Cuaca: <span class="badge bg-warning"><?= $api_weather ?></span></span>
                <a class="btn btn-outline-light btn-sm" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3"><i class="fas fa-plus-circle text-primary"></i> Tambah Destinasi</h5>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label small">Nama Wisata</label>
                                <input type="text" name="nama_wisata" class="form-control" placeholder="Contoh: Pantai Kuta" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small">Harga Tiket</label>
                                <input type="number" name="harga" class="form-control" placeholder="Contoh: 50000" required>
                            </div>
                            <button type="submit" name="tambah_wisata" class="btn btn-primary w-100">Simpan Destinasi</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-bold">Daftar Tiket Tersedia</div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Wisata</th>
                                    <th>Harga</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $query_tampil = mysqli_query($koneksi, "SELECT * FROM tempat_wisata ORDER BY id_wisata DESC");
                                if(mysqli_num_rows($query_tampil) > 0){
                                    while($row = mysqli_fetch_assoc($query_tampil)) : ?>
                                    <tr>
                                        <td><i class="fas fa-map-marker-alt text-danger me-2"></i><?= $row['nama_wisata']; ?></td>
                                        <td>Rp<?= number_format($row['harga']); ?></td>
                                        <td class="text-center">
                                            <a href="?hapus=<?= $row['id_wisata']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus destinasi ini?')"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endwhile; 
                                } else { ?>
                                    <tr><td colspan="3" class="text-center text-muted p-4">Belum ada data tiket.</td></tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white fw-bold">Laporan Penjualan Tiket</div>
                    <div class="table-responsive">
                        <table class="table table-striped align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Pembeli</th>
                                    <th>Destinasi</th>
                                    <th>Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                // Pastikan Anda sudah membuat tabel 'pesanan' atau 'laporan_pesanan' di database
                                $query_laporan = mysqli_query($koneksi, "SELECT * FROM laporan_pesanan ORDER BY id_pesanan DESC");
                                if($query_laporan && mysqli_num_rows($query_laporan) > 0){
                                    while($lap = mysqli_fetch_assoc($query_laporan)) : ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $lap['nama_user']; ?></td>
                                        <td><?= $lap['nama_wisata']; ?></td>
                                        <td>Rp<?= number_format($lap['harga']); ?></td>
                                    </tr>
                                <?php endwhile; 
                                } else { ?>
                                    <tr><td colspan="4" class="text-center text-muted p-4">Belum ada transaksi penjualan.</td></tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>