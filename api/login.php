<?php
session_start();
include "koneksi.php";

// --- BAGIAN 1: CEK COOKIE SAAT HALAMAN DIBUKA ---
if (!isset($_COOKIE['login']) && isset($_COOKIE['id']) && isset($_COOKIE['key'])) {
    $id  = mysqli_real_escape_string($koneksi, $_COOKIE['id']);
    $key = $_COOKIE['key'];

    $result = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user = '$id'");

    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);

        if ($key === hash('sha256', $data['email'])) {
            // Perpanjang cookie
            setcookie('login', '1',              time() + 3600, "/");
            setcookie('nama',  $data['nama'],    time() + 3600, "/");
            setcookie('role',  $data['role'],    time() + 3600, "/");

            // Langsung redirect
            if ($data['role'] == 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: user_dashboard.php");
            }
            exit;
        }
    }
}

// Jika sudah ada cookie login, langsung redirect
if (isset($_COOKIE['login']) && $_COOKIE['login'] === '1') {
    $role = $_COOKIE['role'] ?? '';
    if ($role == 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: user_dashboard.php");
    }
    exit;
}

// --- BAGIAN 2: PROSES FORM LOGIN ---
if (isset($_POST['login'])) {
    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = md5($_POST['password']);

    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email' AND password='$password'");

    if ($query && mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);

        // Set cookie login
        setcookie('login', '1',              time() + 3600, "/");
        setcookie('nama',  $data['nama'],    time() + 3600, "/");
        setcookie('role',  $data['role'],    time() + 3600, "/");
        setcookie('id',    $data['id'],      time() + 3600, "/");
        
        if ($data['role'] == 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: user_dashboard.php");
        }
        exit;
    } else {
        $error = "Email atau Password salah!";
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

            <?php if (isset($error)): ?>
                <div class="alert alert-danger text-center py-2"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

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