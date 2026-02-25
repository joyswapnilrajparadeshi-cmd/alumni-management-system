<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Include database connection
require_once 'db_connection.php';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $company = $_POST['company'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $requirements = $_POST['requirements'];

    // Validate input
    if (!empty($title) && !empty($company) && !empty($location) && !empty($description) && !empty($requirements)) {

        // MySQLi prepared statement
        $sql = "INSERT INTO jobs (title, company, location, description, requirements) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        // Bind parameters
        $stmt->bind_param("sssss", $title, $company, $location, $description, $requirements);

        // Execute
        if ($stmt->execute()) {
            header("Location: jobs.php?success=1");
            exit;
        } else {
            echo "Failed to add job: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "All fields are required!";
    }
} else {
    header("Location: jobs.php");
    exit;
}
?>