<?php
session_start();
include "api/config.php"; 

// --- BAGIAN 1: CEK COOKIE SAAT HALAMAN DIBUKA ---
if (!isset($_COOKIE['login']) && isset($_COOKIE['id']) && isset($_COOKIE['key'])) {
    $id = $_COOKIE['id'];
    $key = $_COOKIE['key'];

    // Ambil data user berdasarkan id cookie
    $result = mysqli_query($config, "SELECT * FROM users WHERE id_user = '$id'");
    $data = mysqli_fetch_assoc($result);

    // Cek apakah 'key' (hash email) di cookie cocok dengan di database
    if ($key === hash('sha256', $data['email'])) {
        $_COOKIE['login'] = true;
        $_COOKIE['nama'] = $data['nama'];
        $_COOKIE['role'] = $data['role'];
    }
}

// Jika sudah ada session login, langsung lempar ke dashboard
if (isset($_COOKIE['login'])) {
    header("Location: user_dashboard.php");
    exit;
}

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($config, $_POST['email']);
    $password = md5($_POST['password']);

    $query = mysqli_query($config, "SELECT * FROM users WHERE email='$email' AND password='$password'");

    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);

        // Set Session standar
        $_COOKIE['login'] = true;
        $_COOKIE['nama'] = $data['nama'];
        $_COOKIE['role'] = $data['role'];

        // --- BAGIAN 2: BUAT COOKIE (Remember Me) ---
        // Kita simpan selama 1 jam (3600 detik). Bisa diganti ke 30 hari (3600 * 24 * 30)
        setcookie('id', $data['id_user'], time() + 3600, "/");
        setcookie('key', hash('sha256', $data['email']), time() + 3600, "/");

        if ($data['role'] == 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: user_dashboard.php");
        }
        exit;
    } else {
        echo "<script>alert('Email atau Password salah!');</script>";
    }
}
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