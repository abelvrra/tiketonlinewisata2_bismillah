<?php
session_start(); // Wajib di baris paling atas!
include "api/config.php";

if(isset($_POST['login'])){
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = md5($_POST['password']);

    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email' AND password='$password'");

    if(mysqli_num_rows($query) > 0){
        $data = mysqli_fetch_assoc($query);
        
        // Simpan data ke session
        $_SESSION['nama'] = $data['nama']; 
        $_SESSION['role'] = $data['role'];
        $_SESSION['foto'] = $data['foto'];

        // Cek role untuk menentukan halaman tujuan
        if($data['role'] == 'admin'){
            echo "<script>alert('Login Admin Berhasil'); window.location='admin_dashboard.php';</script>";
        } else {
            echo "<script>alert('Login Berhasil'); window.location='user_dashboard.php';</script>";
        }
    } else {
        // Tambahkan alert jika login gagal agar tidak bingung
        echo "<script>alert('Email atau Password salah!'); window.location='login.php';</script>";
    }
} // <--- KURUNG INI YANG TADI HILANG UNTUK MENUTUP if(isset($_POST['login']))
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Tiket Wisata</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-sm" style="width: 350px;">
            <h3 class="text-center fw-bold">Login</h3>
            <form method="POST">
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100">Masuk Sekarang</button>
            </form>
            <p class="text-center mt-3 small">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
        </div>
    </div>
</body>
</html>