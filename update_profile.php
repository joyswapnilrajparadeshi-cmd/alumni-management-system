<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user_id = $_SESSION['user_id'];
    $name  = trim($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = trim($_POST['phone']);
    $bio   = trim($_POST['bio']);

    // MySQLi prepared statement
    $sql = "UPDATE users SET name=?, email=?, phone=?, bio=? WHERE id=?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssssi", $name, $email, $phone, $bio, $user_id);

    if ($stmt->execute()) {
        header("Location: profile.php?success=profile_updated");
        exit;
    } else {
        echo "Update failed: " . $stmt->error;
    }
}
?>