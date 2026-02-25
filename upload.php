<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imageFile'])) {
    // Define the directory to store uploaded images
    $uploadDir = 'uploads/gallery/';
    
    // Check if the directory exists, and create it if not
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true); // Create the directory with proper permissions
    }

    // Get the uploaded file's details
    $fileName = basename($_FILES['imageFile']['name']);
    $fileName = preg_replace("/[^a-zA-Z0-9\._-]/", "", $fileName); // Sanitize file name
    $imageFileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Validate file type
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowedTypes)) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.'
        ]);
        exit;
    }

    // Generate unique file name
    $fileName = uniqid('image_', true) . '.' . $imageFileType;
    $uploadFile = $uploadDir . $fileName;

    // Validate file size (max 5MB)
    $maxFileSize = 5 * 1024 * 1024; // 5MB limit
    if ($_FILES['imageFile']['size'] > $maxFileSize) {
        echo json_encode([
            'success' => false,
            'message' => 'File size exceeds the 5MB limit.'
        ]);
        exit;
    }

    // Check MIME type for added security
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $fileMimeType = finfo_file($finfo, $_FILES['imageFile']['tmp_name']);
    finfo_close($finfo);

    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($fileMimeType, $allowedMimeTypes)) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid MIME type. Only images are allowed.'
        ]);
        exit;
    }

    // Move the uploaded file to the target directory
    if (move_uploaded_file($_FILES['imageFile']['tmp_name'], $uploadFile)) {
        chmod($uploadFile, 0644); // Set correct permissions
        echo json_encode([
            'success' => true,
            'filePath' => '/' . $uploadFile,
            'message' => 'Image uploaded successfully!'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to upload the image.'
        ]);
    }
    exit;
}
?>
