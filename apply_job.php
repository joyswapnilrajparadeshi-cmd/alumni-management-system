<?php
session_start();

// Include database connection
require_once 'db_connection.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job_id = $_POST['job_id'];
    $applicant_name = $_POST['name']; // Map form 'name' field to 'applicant_name' column
    $email = $_POST['email'];
    $cover_letter = $_POST['cover_letter'];

    try {
        // Insert the application into the database
        $sql = "INSERT INTO applications (job_id, applicant_name, email, cover_letter, applied_at) 
                VALUES (:job_id, :applicant_name, :email, :cover_letter, GETDATE())";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':job_id' => $job_id,
            ':applicant_name' => $applicant_name,
            ':email' => $email,
            ':cover_letter' => $cover_letter,
        ]);

        // Redirect back with a success message
        header("Location: jobs.php?success=1");
        exit;
    } catch (PDOException $e) {
        // Redirect back with an error message
        header("Location: jobs.php?error=1");
        exit;
    }
} else {
    // If accessed directly, redirect to jobs page
    header("Location: jobs.php");
    exit;
}
?>
