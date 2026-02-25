<?php
if (!isset($_GET['transaction_id']) || !isset($_GET['amount'])) {
    header("Location: donate.php");
    exit;
}

$transactionId = htmlspecialchars($_GET['transaction_id']);
$amount = htmlspecialchars($_GET['amount']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Thank You for Your Donation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background: #f4f4f4;
            padding: 50px;
        }
        .thank-you {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }
        .thank-you h1 {
            color: #2f80ed;
        }
    </style>
</head>
<body>
    <div class="thank-you">
        <h1>Thank You!</h1>
        <p>Your donation of ₹<?php echo $amount; ?> has been successfully processed.</p>
        <p>Transaction ID: <strong><?php echo $transactionId; ?></strong></p>
    </div>
</body>
</html>
