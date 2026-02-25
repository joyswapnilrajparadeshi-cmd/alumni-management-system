<?php
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = $_POST['name'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;
    $confirm_password = $_POST['confirm-password'] ?? null;
    $grad_year = $_POST['grad-year'] ?? null;
    $department = $_POST['department'] ?? null;
    $admission_number = $_POST['admission-number'] ?? null;

    // Validate inputs
    if (!$name || !$email || !$password || !$confirm_password || !$grad_year || !$department || !$admission_number) {
        die("All fields are required!");
    }

    if ($password !== $confirm_password) {
        die("Passwords do not match!");
    }

    // Check if email already exists
    $check_sql = "SELECT id FROM users WHERE email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        die("This email is already registered. Please use another email.");
    }
    $check_stmt->close();

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $sql = "INSERT INTO users (name, email, password, grad_year, department, admission_number) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param(
        "ssssss",
        $name,
        $email,
        $hashed_password,
        $grad_year,
        $department,
        $admission_number
    );

    if ($stmt->execute()) {
        // Redirect to login page
        header("Location: login.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    die("Invalid request method.");
}
?>
