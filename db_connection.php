<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// InfinityFree Database Credentials for AMS
$host = "sql107.infinityfree.com"; // Host
$user = "if0_41196114";             // Database user
$pass = "AM5RlhTPgwx";              // Your vPanel password
$dbname = "if0_41196114_ams";       // AMS database name

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}
?>