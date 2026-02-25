<?php
// Start the session securely
session_start();
session_regenerate_id(true);

// Include database connection
require 'db_connection.php'; 
require 'vendor/autoload.php'; // Required for OTP verification using otphp

use OTPHP\TOTP;

// Function to send a JSON response
function sendResponse($success, $message)
{
    echo json_encode(['success' => $success, 'message' => $message]);
    exit();
}

// Function to verify OTP
function verifyOTP($secret, $otp)
{
    $totp = TOTP::create($secret);
    return $totp->verify($otp); // Returns true if valid
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);
    $captchaResponse = trim($_POST['captcha']);
    $otp = isset($_POST['otp']) ? trim($_POST['otp']) : null;

    // Validate email and password
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendResponse(false, "Invalid email format.");
    }

    if (strlen($password) < 8) {
        sendResponse(false, "Password must be at least 8 characters long.");
    }

    // Validate CAPTCHA
    $captchaSecretKey = 'YOUR_RECAPTCHA_SECRET_KEY';
    $verifyURL = "https://www.google.com/recaptcha/api/siteverify?secret={$captchaSecretKey}&response={$captchaResponse}";
    $verifyResponse = file_get_contents($verifyURL);
    $captchaValidation = json_decode($verifyResponse);

    if (!$captchaValidation->success) {
        sendResponse(false, "Invalid CAPTCHA.");
    }

    // Fetch user data securely
    $stmt = $conn->prepare("SELECT id, password, role, otp_secret, is_2fa_enabled FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        sendResponse(false, "No user found with this email.");
    }

    $user = $result->fetch_assoc();

    // Verify role
    if ($role !== $user['role']) {
        sendResponse(false, "Invalid role selected.");
    }

    // Verify password
    if (!password_verify($password, $user['password'])) {
        sendResponse(false, "Incorrect password.");
    }

    // Verify OTP if 2FA is enabled
    if ($user['is_2fa_enabled']) {
        if ($otp === null || !verifyOTP($user['otp_secret'], $otp)) {
            sendResponse(false, "Invalid or missing OTP.");
        }
    }

    // Create a secure session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['email'] = $email;
    $_SESSION['role'] = $role;

    sendResponse(true, "Login successful.");
} else {
    sendResponse(false, "Invalid request method.");
}
