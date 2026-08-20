<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['admin_id']) && !isset($_SESSION['admin']) && !isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}
?>