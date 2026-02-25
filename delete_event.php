<?php
session_start();
require_once 'db_connection.php';

// Check if user is an admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: events.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
    $event_id = intval($_POST['event_id']);

    // Prepare MySQLi statement
    $sql = "DELETE FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        $_SESSION['error'] = "Prepare failed: " . $conn->error;
        header("Location: events.php");
        exit;
    }

    $stmt->bind_param("i", $event_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Event deleted successfully!";
    } else {
        $_SESSION['error'] = "Failed to delete the event: " . $stmt->error;
    }

    $stmt->close();
}

// Redirect back to events page
header("Location: events.php");
exit;
?>