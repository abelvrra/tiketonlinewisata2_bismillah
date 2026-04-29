<?php
session_start();
include "api/koneksi.php";

// 1. Proteksi halaman
if(!isset($_COOKIE['login'])){
    header("Location: login.php");
    exit();
}

// 2. Ambil data terbaru user
$nama_saat_ini = $_COOKIE['nama'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE nama='$nama_saat_ini'");
$data = mysqli_fetch_assoc($query);

// 3. Proses Update Profil
if(isset($_POST['update_profil'])){
    $nama_baru = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $nama_file = $_FILES['foto']['name'];
    $tmp_name = $_FILES['foto']['tmp_name'];
    $folder_tujuan = 'img/';
    
    if (!file_exists($folder_tujuan)) {
        mkdir($folder_tujuan, 0777, true);
    }

    if(!empty($nama_file)){
        move_uploaded_file($tmp_name, $folder_tujuan . $nama_file);
        $sql = "UPDATE users SET nama='$nama_baru', foto='$nama_file' WHERE nama='$nama_saat_ini'";
        $_COOKIE['foto'] = $nama_file;
    } else {
        $sql = "UPDATE users SET nama='$nama_baru' WHERE nama='$nama_saat_ini'";
    }

    if(mysqli_query($koneksi, $sql)){
        $_COOKIE['nama'] = $nama_baru;
        echo "<script>alert('Profil berhasil diperbarui!'); window.location='profil.php';</script>";
    }
}

$foto_profil = !empty($data['foto']) ? $data['foto'] : 'default.jpg';
$role = !empty($data['role']) ? strtoupper($data['role']) : 'USER';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .profile-card {
            background: #fff; width: 450px; border-radius: 15px; padding: 30px;
            text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .profile-img {
            width: 120px; height: 120px; border-radius: 50%; object-fit: cover;
            margin-bottom: 15px; border: 4px solid #fff; box-shadow: 0 5px 15px rgba(25, 64, 94, 0.1);
        }
        .badge-pro {
            background-color: #6c757d; color: white; padding: 5px 20px;
            border-radius: 5px; font-size: 0.8rem; margin-bottom: 15px; display: inline-block;
        }
        .social-icons i { font-size: 1.2rem; margin: 15px 10px; color: #554fcd; cursor: pointer; }
        .btn-edit-toggle { background-color: #554fcd; color: white; font-weight: bold; width: 100%; border-radius: 8px; }
        .btn-edit-toggle:hover { background-color: #f7f7f9; color: white; }
        #editForm { display: none; margin-top: 20px; text-align: left; }
    </style>
</head>
<body>

<div class="profile-card">
    <img src="img/<?php echo $foto_profil; ?>" alt="Profile" class="profile-img">
    <div><span class="badge-pro"><?php echo $role; ?></span></div>
    <h4 class="fw-bold"><?php echo $data['nama']; ?></h4>
    <p class="text-muted small">Informatics Student</p>

    <div class="social-icons">
        <i class="fab fa-facebook-f"></i>
        <i class="fab fa-instagram"></i>
        <i class="fab fa-linkedin-in"></i>
    </div>

    <div class="mt-3">
        <button onclick="toggleEdit()" id="btnTeks" class="btn btn-edit-toggle">Edit Profile</button>
        <a href="dashboard.php" class="btn btn-outline-secondary w-100 mt-2">Dashboard</a>
    </div>

    <div id="editForm">
        <hr>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label small fw-bold">Nama Baru</label>
                <input type="text" name="nama" class="form-control" value="<?php echo $data['nama']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Foto Baru</label>
                <input type="file" name="foto" class="form-control">
            </div>
            <button type="submit" name="update_profil" class="btn btn-success btn-sm w-100">Simpan Perubahan</button>
        </form>
    </div>
</div>

<script>
    function toggleEdit() {
        var form = document.getElementById("editForm");
        var btn = document.getElementById("btnTeks");
        if (form.style.display === "none" || form.style.display === "") {
            form.style.display = "block";
            btn.innerText = "Batal Edit";
            btn.classList.replace("btn-edit-toggle", "btn-danger");
        } else {
            form.style.display = "none";
            btn.innerText = "Edit Profile";
            btn.classList.replace("btn-danger", "btn-edit-toggle");
        }
    }
</script>

</body>
</html>