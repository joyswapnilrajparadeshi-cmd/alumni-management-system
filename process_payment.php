<?php
require 'razorpay-php/Razorpay.php'; // Include Razorpay SDK
require 'db_connection.php'; // Include database connection

use Razorpay\Api\Api;

session_start();

$apiKey = "YOUR_RAZORPAY_API_KEY"; // Replace with your Razorpay Key
$apiSecret = "YOUR_RAZORPAY_API_SECRET"; // Replace with your Razorpay Secret

$api = new Api($apiKey, $apiSecret);

// Fetch JSON data from Razorpay checkout
$input = file_get_contents('php://input');
$postData = json_decode($input, true);

if (isset($postData['paymentId']) && isset($postData['amount'])) {
    $paymentId = htmlspecialchars($postData['paymentId']);
    $amount = htmlspecialchars($postData['amount']);
    $userId = $_SESSION['user_id'];
    $transactionId = uniqid(); // Generate unique transaction ID

    try {
        // Fetch payment details from Razorpay
        $payment = $api->payment->fetch($paymentId);

        if ($payment->status == "captured") {
            // Payment is successful
            $paymentStatus = "Success";
            $createdAt = date('Y-m-d H:i:s');

            // Insert transaction into the database
            $query = "INSERT INTO donations (user_id, amount, payment_method, payment_status, transaction_id, created_at)
                      VALUES (:user_id, :amount, :payment_method, :payment_status, :transaction_id, :created_at)";
            $stmt = $conn->prepare($query);
            $stmt->execute([
                ':user_id' => $userId,
                ':amount' => $amount,
                ':payment_method' => 'Razorpay',
                ':payment_status' => $paymentStatus,
                ':transaction_id' => $transactionId,
                ':created_at' => $createdAt
            ]);

            echo json_encode([
                'success' => true,
                'transaction_id' => $transactionId
            ]);
        } else {
            // Payment failed
            echo json_encode(['success' => false, 'message' => 'Payment verification failed.']);
        }
    } catch (Exception $e) {
        // Handle errors
        error_log("Payment verification error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
