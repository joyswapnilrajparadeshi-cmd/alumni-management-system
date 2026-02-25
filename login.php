<?php
require_once 'db_connection.php';

// Start session securely
session_start([
    'cookie_lifetime' => 0,
    'cookie_secure' => false, // set true only with HTTPS
    'cookie_httponly' => true,
    'cookie_samesite' => 'Strict'
]);

// Get and sanitize form data
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$password = $_POST['password'];
$role = filter_var($_POST['role'], FILTER_SANITIZE_STRING);

// Validate input
if (empty($email) || empty($password) || empty($role)) {
    header("Location: login.html?error=empty_fields");
    exit;
}

// Prepare SQL using MySQLi
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    if (password_verify($password, $user['password'])) {
        if ($user['role'] !== $role) {
            header("Location: login.html?error=role_mismatch");
            exit;
        }

        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['LAST_ACTIVITY'] = time();

        // Redirect to dashboard
        header("Location: alumniindex.php");
        exit;
    } else {
        header("Location: login.html?error=invalid_password");
        exit;
    }
} else {
    header("Location: login.html?error=user_not_found");
    exit;
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
