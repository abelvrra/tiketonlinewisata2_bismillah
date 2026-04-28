<?php
session_start();

if(!isset($_SESSION['nama'])){
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5 text-center">

<h3>Selamat datang, <?php echo $_SESSION['nama']; ?> 👋</h3>

<a href="logout.php" class="btn btn-danger mt-3">Logout</a>

</div>

</body>
</html>