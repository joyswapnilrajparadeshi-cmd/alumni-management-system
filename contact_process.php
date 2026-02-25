<?php
// Enable full error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/htdocs/swapnilprojectfinal/PHPMailer-master/src/PHPMailer.php';
require 'C:/xampp/htdocs/swapnilprojectfinal/PHPMailer-master/src/Exception.php';
require 'C:/xampp/htdocs/swapnilprojectfinal/PHPMailer-master/src/SMTP.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitize inputs
    $name    = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email   = htmlspecialchars(trim($_POST['email'] ?? ''));
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));

    // Validate inputs
    if (empty($name) || empty($email) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: contact.php?error=invalid_input");
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'joyswapnilrajparadeshi@gmail.com'; // Your Gmail
        $mail->Password   = 'kdbddmffsotggrjt';                 // Your App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;     // TLS encryption
        $mail->Port       = 587;

        // Optional SSL/TLS options to prevent certificate errors
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ]
        ];

        // Sender & recipient
        $mail->setFrom('joyswapnilrajparadeshi@gmail.com', 'Smart Cultivation System'); // Sender
        $mail->addAddress('joyswapnilrajparadeshi@gmail.com'); // Receiver (you)

        // Email content
        $mail->isHTML(false);
        $mail->Subject = "Contact Form Submission from $name";
        $mail->Body    = "Name: $name\nEmail: $email\n\nMessage:\n$message";

        // Send email
        $mail->send();

        // Redirect to contact page with success
        header("Location: contact.php?success=1");
        exit;

    } catch (Exception $e) {
        // Redirect on failure with error
        header("Location: contact.php?error=mailer_failed");
        exit;
    }

} else {
    // Invalid request method
    header("Location: contact.php?error=invalid_request");
    exit;
}
