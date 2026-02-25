<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure PHPMailer is loaded via Composer

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com'; // Your SMTP server
    $mail->SMTPAuth   = true;
    $mail->Username   = 'youremail@gmail.com'; // Your Gmail address
    $mail->Password   = 'your_app_password';   // Your Gmail app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Recipients
    $mail->setFrom('youremail@gmail.com', 'Your Name');
    $mail->addAddress('recipient@example.com', 'Recipient Name'); // Add recipient

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email from PHPMailer';
    $mail->Body    = 'This is a test email sent using <b>PHPMailer</b>.';

    $mail->send();
    echo 'Message has been sent successfully!';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
