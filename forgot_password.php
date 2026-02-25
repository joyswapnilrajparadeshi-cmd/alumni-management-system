<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userEmail = $_POST['email'];

    try {
        // Check if the email exists in the database
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':email' => $userEmail]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Generate OTP and expiry
            $otp = rand(100000, 999999); // Generate a 6-digit OTP
            $otp_expiry = date("Y-m-d H:i:s", strtotime("+15 minutes") + 19800); // Add 5 hours 30 mins (19800 seconds)

            // Update the database
            $updateSql = "UPDATE users SET otp = :otp, otp_expiry = :otp_expiry WHERE email = :email";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->execute([
                ':otp' => $otp,
                ':otp_expiry' => $otp_expiry,
                ':email' => $userEmail
            ]);

            // Email setup
            $mail = new PHPMailer(true);
            try {
                // SMTP configuration
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'joyswapnilrajparadeshi@gmail.com'; // Your Gmail
                $mail->Password = 'puge ilbh nlhb ejhh'; // Your app password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Add SSL/TLS options
                $mail->SMTPOptions = [
                    'ssl' => [
                        'verify_peer'       => false,
                        'verify_peer_name'  => false,
                        'allow_self_signed' => true,
                    ],
                ];

                // Sender and recipient
                $mail->setFrom('joyswapnilrajparadeshi@gmail.com', 'Swapnil Alumni Services');
                $mail->addAddress($userEmail);

                // Email content
                $mail->isHTML(true);
                $mail->Subject = 'Your OTP for Password Reset';
                $mail->Body = "Your OTP for password reset is: <strong>$otp</strong>. It is valid for 15 minutes.";
                $mail->AltBody = "Your OTP for password reset is: $otp. It is valid for 15 minutes.";

                // Send the email
                $mail->send();
                header("Location: verify_otp.php?email=" . urlencode($userEmail));
                exit;
            } catch (Exception $e) {
                echo "Failed to send the email. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "No account found with that email address.";
        }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
}
?> 