<?php
// Gunakan path langsung karena file config.php sejajar di dalam folder api
include "config.php"; 

if (isset($_POST['register'])) {
    // Tambahkan pengecekan ini untuk memastikan koneksi benar-benar masuk
    if (!isset($koneksi)) {
        die("Error: Variabel konesi tidak ditemukan. Pastikan file config.php sudah benar.");
    }

    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = md5($_POST['password']);

    $cek_email = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email'");

    if (mysqli_num_rows($cek_email) > 0) {
        echo "<script>alert('Email sudah digunakan!');</script>";
    } else {
        // Gunakan id_user atau id (sesuaikan dengan nama kolom di database kamu)
        $query = "INSERT INTO users (nama, email, password, role, foto)
                  VALUES ('$nama', '$email', '$password', 'user', 'default.jpg')";

        if (mysqli_query($koneksi, $query)) {
            echo "<script>alert('Register berhasil, silahkan login'); window.location='login.php';</script>";
        } else {
            echo "Gagal daftar: " . mysqli_error($koneksi);
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow" style="width: 350px;">
            <h3 class="text-center">Register</h3>
            <form method="POST">
                <input type="text"     name="nama"     class="form-control mb-3" placeholder="Nama"     required>
                <input type="email"    name="email"    class="form-control mb-3" placeholder="Email"    required>
                <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
                <button name="register" class="btn btn-success w-100">Daftar</button>
            </form>
            <p class="text-center mt-3">Sudah punya akun? <a href="login.php">Login</a></p>
        </div>
    </div>
</body>
</html>