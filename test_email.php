<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Create an instance of PHPMailer
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com'; // SMTP server
    $mail->SMTPAuth   = true;
    $mail->Username   = 'joyswapnilrajparadeshi@gmail.com'; // Your Gmail address
    $mail->Password   = 'puge ilbh nlhb ejhh'; // App password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Add SMTPOptions for SSL/TLS verification bypass
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer'       => false,
            'verify_peer_name'  => false,
            'allow_self_signed' => true,
        ],
    ];

    // Recipients
    $mail->setFrom('joyswapnilrajparadeshi@gmail.com', 'swapnil'); // Your email and name
    $mail->addAddress('sumithstanly1@gmail.com', 'sumith'); // Replace with recipient's email and name

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email from PHPMailer';
    $mail->Body    = '<h1>This is a test email</h1><p>Sent using PHPMailer and Gmail SMTP.</p>';
    $mail->AltBody = 'This is a test email sent using PHPMailer and Gmail SMTP.';

    // Send the email
    if ($mail->send()) {
        echo 'Email has been sent successfully.';
    }
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
