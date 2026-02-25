<?php
// Enable Error Logging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start the session
session_start();
include 'db_connection.php';

header('Content-Type: application/json');

// Razorpay API credentials
$razorpay_key = "rzp_test_BMlhRECn1dAaK4";
$razorpay_secret = "9pFnPfFC93o6iJjXVquHKzqz";

// Get the JSON data sent from the client
$data = json_decode(file_get_contents('php://input'), true);

// Log incoming data for debugging
file_put_contents('payment_log.txt', json_encode($data, JSON_PRETTY_PRINT), FILE_APPEND);

// Extract payment details
$payment_id = $data['payment_id'] ?? '';
$name = $data['name'] ?? '';
$email = $data['email'] ?? '';
$amount = $data['amount'] ?? 0;

// Validate inputs
if (empty($payment_id) || empty($name) || empty($email) || $amount <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid input data."]);
    exit;
}

// Verify payment with Razorpay
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.razorpay.com/v1/payments/$payment_id");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERPWD, "$razorpay_key:$razorpay_secret");
$response = curl_exec($ch);
$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Log Razorpay API response
file_put_contents('razorpay_verification_log.txt', $response, FILE_APPEND);

// Check if the verification was successful
if ($http_status !== 200 || empty($response)) {
    echo json_encode([
        "success" => false,
        "message" => "Payment verification failed. Please try again.",
        "http_status" => $http_status
    ]);
    exit;
}

$payment_data = json_decode($response, true);

// Ensure the payment is captured
if ($payment_data['status'] !== 'captured') {
    echo json_encode([
        "success" => false,
        "message" => "Payment not captured. Payment status: " . $payment_data['status']
    ]);
    exit;
}

// Insert the donation record into the database
$sql = "INSERT INTO donations (name, email, amount, payment_id, donation_date) VALUES (?, ?, ?, ?, GETDATE())";
$stmt = sqlsrv_prepare($conn, $sql, [$name, $email, $amount, $payment_id]);

if (sqlsrv_execute($stmt)) {
    echo json_encode(["success" => true, "message" => "Donation recorded successfully."]);
} else {
    $errors = sqlsrv_errors();
    echo json_encode(["success" => false, "message" => "Failed to record donation.", "errors" => $errors]);
}
?>
