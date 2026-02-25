<?php
session_start();

// Check if the user is an admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Get the image name from the request
if (isset($_POST['image'])) {
    $imageDir = 'uploads/gallery/';
    $imageName = basename($_POST['image']); // Sanitize the file name
    $filePath = $imageDir . $imageName;

    // Check if the file exists and delete it
    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            echo json_encode(['success' => true, 'message' => 'Image deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete image']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Image not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No image specified']);
}
?>
