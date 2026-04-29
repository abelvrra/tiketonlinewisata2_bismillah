<?php
session_start();

// Hapus semua cookie yang dibuat saat login
setcookie('login', '', time() - 3600, "/");
setcookie('nama',  '', time() - 3600, "/");
setcookie('role',  '', time() - 3600, "/");
setcookie('id',    '', time() - 3600, "/");
setcookie('key',   '', time() - 3600, "/");

// Hapus session juga
session_unset();
session_destroy();

header("Location: login.php");
exit;
?>