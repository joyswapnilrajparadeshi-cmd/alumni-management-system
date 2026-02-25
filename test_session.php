<?php
session_start();

// Set a session variable
$_SESSION['user'] = 'Test User';

// Display session information
echo "Session save path: " . session_save_path() . "<br>";
echo "Session ID: " . session_id() . "<br>";

if (isset($_SESSION['user'])) {
    echo "Session is working. User: " . $_SESSION['user'];
} else {
    echo "Session is not working.";
}
?>
