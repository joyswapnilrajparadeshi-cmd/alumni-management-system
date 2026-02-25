<?php
require_once 'db_connection.php';

// Adjust PHP's default time zone (optional but recommended)
date_default_timezone_set("Asia/Kolkata");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enteredOtp = $_POST['otp']; // Retrieve OTP entered by the user

    if (empty($enteredOtp)) {
        echo "Please enter a valid OTP.";
        exit;
    }

    try {
        // Get current time adjusted to IST
        $current_time = date("Y-m-d H:i:s");

        // Query to check if OTP is valid and not expired
        $sql = "SELECT * FROM users WHERE otp = :otp AND otp_expiry > :current_time";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':otp' => $enteredOtp, ':current_time' => $current_time]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // OTP is valid, pass OTP in the URL and redirect to reset_password.php
            header("Location: reset_password.php?otp=" . urlencode($enteredOtp)); // Pass OTP to the next page
            exit;
        } else {
            // Invalid or expired OTP
            echo "Invalid or expired OTP.";
        }
    } catch (PDOException $e) {
        // Handle database errors
        echo "Database error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
</head>
<body>
    <h1>Verify OTP</h1>
    <form action="verify_otp.php" method="POST">
        <label for="otp">Enter OTP:</label>
        <input type="text" name="otp" id="otp" required>
        <button type="submit">Verify OTP</button>
    </form>
</body>
</html>
