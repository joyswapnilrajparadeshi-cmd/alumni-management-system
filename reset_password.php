<?php
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $otp = isset($_GET['otp']) ? $_GET['otp'] : null;

    if (empty($otp)) {
        echo "OTP is missing or invalid.";
        exit;
    }

    if ($newPassword === $confirmPassword) {
        try {
            // Get current time for validation
            $current_time = date("Y-m-d H:i:s");

            // Validate the OTP and check expiry
            $sql = "SELECT * FROM users WHERE otp = :otp AND otp_expiry > :current_time";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':otp' => $otp,
                ':current_time' => $current_time
            ]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // OTP is valid; proceed to update the password
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                // Update password and clear OTP fields
                $updateSql = "UPDATE users SET password = :password, otp = NULL, otp_expiry = NULL WHERE otp = :otp";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->execute([
                    ':password' => $hashedPassword,
                    ':otp' => $otp
                ]);

                echo "Password reset successfully. You can now <a href='login.php'>login</a>.";
            } else {
                echo "Invalid or expired OTP.";
            }
        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        }
    } else {
        echo "Passwords do not match.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Your Password</h2>
    <form action="reset_password.php?otp=<?php echo htmlspecialchars($_GET['otp']); ?>" method="POST">
        <label for="password">New Password:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" id="confirm_password" required>
        <br>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
