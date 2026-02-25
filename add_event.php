<?php
session_start();
require_once 'db_connection.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title']);
    $date = trim($_POST['date']);
    $venue = trim($_POST['venue']);
    $description = trim($_POST['description']);

    // Validate input
    if (!empty($title) && !empty($date) && !empty($venue) && !empty($description)) {

        $sql = "INSERT INTO events (title, date, venue, description) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("ssss", $title, $date, $venue, $description);

        if ($stmt->execute()) {
            header("Location: events.php?success=1");
            exit;
        } else {
            echo "Insert Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "All fields are required!";
    }
}
?>