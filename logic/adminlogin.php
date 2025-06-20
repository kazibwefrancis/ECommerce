<?php
session_start();

// Hardcoded admin credentials (for demo; use DB in production)
$admin_username = 'admin';
$admin_password = 'admin123'; // Change this!

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: ../screens/layouts/pages/admindashboard.php');
        exit;
    } else {
        header('Location: ../screens/adminlogin.php?error=1');
        exit;
    }
}
?>