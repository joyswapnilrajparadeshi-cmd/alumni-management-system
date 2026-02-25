<?php
// Start the session
session_start();

// Include database connection
include 'db_connection.php';

// Razorpay API keys
$razorpay_key = "rzp_test_BMIhRECn1dAaK4";
$razorpay_secret = "9pFn9FFC93o6JiXvquHKz";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate</title>
    <style>
        /* General styles for the donation page */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        .donate-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .donate-container h1 {
            font-size: 2rem;
            color: #3399cc;
            margin-bottom: 10px;
        }

        .donate-container p {
            font-size: 1rem;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
            text-align: left;
        }

        input[type="text"],
        input[type="email"],
        input[type="number"] {
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
        }

        button {
            padding: 10px 15px;
            font-size: 1rem;
            color: #fff;
            background-color: #3399cc;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #287aa1;
        }

        button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .donate-container {
                margin: 20px;
                padding: 15px;
            }

            .donate-container h1 {
                font-size: 1.8rem;
            }

            button {
                font-size: 0.9rem;
            }
        }
    </style>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
    <div class="donate-container">
        <h1>Donate to Alumni Management System</h1>
        <p>Your contributions help us grow and support alumni initiatives.</p>
        
        <form id="donationForm">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="amount">Amount (INR):</label>
            <input type="number" id="amount" name="amount" required>

            <button type="button" id="payButton">Donate Now</button>
        </form>
    </div>

    <script>
        document.getElementById("payButton").addEventListener("click", function (e) {
            e.preventDefault();

            // Get user input
            const name = document.getElementById("name").value;
            const email = document.getElementById("email").value;
            const amount = document.getElementById("amount").value * 100; // Convert to paise

            // Razorpay Checkout Options
            const options = {
                key: "<?php echo $razorpay_key; ?>", // Replace with your Razorpay Test Key ID
                amount: amount,
                currency: "INR",
                name: "Swapnil Alumni Services",
                description: "Donation",
                image: "https://example.com/logo.png", // Optional logo URL
                handler: function (response) {
                    // Log the payment_id returned from Razorpay
                    console.log("Payment ID: ", response.razorpay_payment_id);

                    // Send the payment_id and other details to the backend
                    fetch("process_donation.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({
                            payment_id: response.razorpay_payment_id,
                            name: name,
                            email: email,
                            amount: amount / 100, // Convert back to INR
                        }),
                    })
                    .then((res) => res.json())
                    .then((data) => {
                        alert(data.message); // Display success or error message
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                    });
                },
                prefill: {
                    name: name,
                    email: email,
                },
                theme: {
                    color: "#3399cc", // Theme color
                },
            };

            // Open Razorpay Checkout
            const rzp = new Razorpay(options);
            rzp.open();
        });
    </script>
</body>
</html>
