<?php
session_start();

// 1. Konfigurasi Koneksi
$host = "localhost";
$user = "root";
$pass = "";
$db   = "tiket_db";

$conn = mysqli_connect($host, $user, $pass, $db);

// Cek Koneksi
if(!$conn){
    die("Koneksi gagal: " . mysqli_connect_error());
}

// 2. Tangkap data dari URL dan Session
// Pastikan kamu sudah login agar $_SESSION['nama'] ada isinya
$nama_user = isset($_SESSION['nama']) ? $_SESSION['nama'] : "Guest"; 
$wisata    = $_GET['wisata'];
$harga     = $_GET['harga'];

// 3. Masukkan ke Database
$query = "INSERT INTO laporan_pesanan (nama_user, nama_wisata, harga) VALUES ('$nama_user', '$wisata', '$harga')";
$insert = mysqli_query($conn, $query);

// 4. Cek Berhasil atau Tidak
if($insert) {
    echo "<script>alert('Pemesanan Berhasil!'); window.location='transaksi.php';</script>";
} else {
    // Jika error, tampilkan pesan errornya apa
    echo "Gagal menyimpan: " . mysqli_error($conn);
}
?>