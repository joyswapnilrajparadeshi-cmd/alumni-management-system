<?php
// Start the session
session_start();

// Destroy session data
session_unset();
session_destroy();

// Redirect to login page
header("Location: login2.html?message=logged_out");
exit();
?>
