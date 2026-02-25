<?php
// Include database connection
include 'db_connection.php';

// Check if POST request is valid
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

    // Check if image was uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['image']['tmp_name'];
        $imageName = $_FILES['image']['name'];
        $imageType = $_FILES['image']['type'];

        // Validate file type (ensure it's an image)
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($imageType, $allowedTypes)) {
            echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, and GIF allowed.']);
            exit;
        }

        // Set upload path
        $uploadDir = 'images/';
        $imagePath = $uploadDir . uniqid() . '_' . basename($imageName);

        // Move file to the server
        if (move_uploaded_file($imageTmpPath, $imagePath)) {
            // Insert data into the database using MySQLi
            $stmt = $conn->prepare("INSERT INTO mentors (name, description, image_path) VALUES (?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("sss", $name, $description, $imagePath);

                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Mentor added successfully!']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Database insertion failed: ' . $stmt->error]);
                }

                $stmt->close();
            } else {
                echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'File upload failed.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No file uploaded or file upload error.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>