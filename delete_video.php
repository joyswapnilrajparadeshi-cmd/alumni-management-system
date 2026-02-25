<?php
session_start();
include 'db_connection.php';

// Check if the user is logged in and an admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

// Check if the video ID is set
if (isset($_POST['videoId'])) {
    $videoId = intval($_POST['videoId']);

    // Prepare the SQL query to delete the video
    $query = "DELETE FROM videos WHERE id = ?";
    $params = [$videoId];
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt) {
        echo json_encode(['success' => true, 'message' => 'Video deleted successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete video from the database.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No video ID provided.']);
}
?>
