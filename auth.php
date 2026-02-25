<?php
session_start(); // Start the session to access session variables

// Check if the user is logged in by verifying the session variable
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page with a message
    header("Location: login.html?error=not_logged_in");
    exit;
}
?>
