<?php
// Start session and check for admin role
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

// Include database connection
include 'db_connection.php';

// Check if ID is provided
if (!isset($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'Mentor ID is missing.']);
    exit;
}

$mentorId = intval($_POST['id']);

// Delete mentor using MySQLi
$stmt = $conn->prepare("DELETE FROM mentors WHERE id = ?");
if ($stmt) {
    $stmt->bind_param("i", $mentorId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Mentor deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete mentor: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
}
?>