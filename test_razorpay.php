<?php
require 'razorpay-php/Razorpay.php';
use Razorpay\Api\Api;

// Replace with your Razorpay credentials
$keyId = "YOUR_KEY_ID";
$keySecret = "YOUR_KEY_SECRET";

try {
    $api = new Api($keyId, $keySecret);
    echo "Razorpay SDK is installed successfully!";
} catch (Exception $e) {
    echo "Error initializing Razorpay SDK: " . $e->getMessage();
}
?>
