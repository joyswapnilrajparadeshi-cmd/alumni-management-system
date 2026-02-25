<?php
session_start();
require_once 'db_connection.php';

// Check if user is admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: jobs.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['job_id'])) {
    $job_id = intval($_POST['job_id']);

    // Start transaction
    $conn->autocommit(FALSE);

    try {
        // Delete related applications
        $stmt = $conn->prepare("DELETE FROM applications WHERE job_id = ?");
        if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);
        $stmt->bind_param("i", $job_id);
        if (!$stmt->execute()) throw new Exception("Execute failed: " . $stmt->error);
        $stmt->close();

        // Delete the job
        $stmt = $conn->prepare("DELETE FROM jobs WHERE id = ?");
        if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);
        $stmt->bind_param("i", $job_id);
        if (!$stmt->execute()) throw new Exception("Execute failed: " . $stmt->error);
        $stmt->close();

        // Commit transaction
        $conn->commit();
        $conn->autocommit(TRUE);

        header("Location: jobs.php?success=Job deleted successfully");
        exit;
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $conn->autocommit(TRUE);
        header("Location: jobs.php?error=" . urlencode($e->getMessage()));
        exit;
    }
} else {
    header("Location: jobs.php?error=Invalid job ID");
    exit;
}
?>