<?php
session_start();

// Only admin can add
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

// Include database connection (MySQLi)
include 'db_connection.php';

if (isset($_POST['videoUrl'])) {
    $videoUrl = trim($_POST['videoUrl']);

    // Normalize YouTube URL to embed format
    $videoId = null;

    // Case 1: Normal watch URL
    if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $videoUrl, $matches)) {
        $videoId = $matches[1];
    }
    // Case 2: youtu.be short URL
    elseif (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $videoUrl, $matches)) {
        $videoId = $matches[1];
    }
    // Case 3: Already embed URL
    elseif (preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/', $videoUrl, $matches)) {
        $videoId = $matches[1];
    }

    if ($videoId) {
        $embedUrl = "https://www.youtube.com/embed/$videoId";

        // Insert using MySQLi prepared statement
        $stmt = $conn->prepare("INSERT INTO videos (url, added_by) VALUES (?, ?)");
        if ($stmt) {
            $addedBy = $_SESSION['user_id'];
            $stmt->bind_param("si", $embedUrl, $addedBy);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Video added successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
            }

            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid YouTube URL. Please paste a valid YouTube link.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No video URL provided.']);
}
?>