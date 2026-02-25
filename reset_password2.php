<?php
require_once 'db_connection.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $otp = $_POST['otp'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT id FROM password_resets WHERE email = ? AND otp = ?");
    $stmt->bind_param("ss", $email, $otp);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $new_password, $email);
        $stmt->execute();
        echo "Password Reset Successful!";
    } else {
        echo "Invalid OTP!";
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
