<?php
session_start();
session_unset();
session_destroy();

// Hapus Cookie dengan set waktu ke -3600 (masa lalu)
setcookie('id', '', time() - 3600, "/");
setcookie('key', '', time() - 3600, "/");

header("Location: login.php");
exit;
?>