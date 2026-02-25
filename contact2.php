<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Contact Us</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #ff9a9e, #fad0c4);
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        header {
            width: 100%;
            text-align: center;
            padding: 20px 0;
            background: #ff9a9e;
            color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1 {
            font-size: 2.5em;
            margin: 0;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
        }

        .content {
            width: 90%;
            max-width: 1200px;
            margin: 20px 0;
        }

        section {
            background: white;
            padding: 30px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        form label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
            color: #333;
        }

        form input[type="text"],
        form input[type="email"],
        form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
        }

        form textarea {
            resize: none;
        }

        form button {
            width: 100%;
            padding: 10px;
            background: #ff9a9e;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.2em;
            cursor: pointer;
        }

        form button:hover {
            background: #fad0c4;
        }

        .map iframe {
            width: 100%;
            height: 300px;
            border: none;
            border-radius: 10px;
        }

        .social-links a {
            margin: 0 10px;
            color: #333;
            text-decoration: none;
            font-size: 1.2em;
        }

        .social-links a:hover {
            color: #ff9a9e;
        }

        .faq p {
            margin-bottom: 10px;
        }

        footer {
            background: #333;
            color: white;
            padding: 10px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <h1>Contact Us</h1>
    </header>
    <div class="content">
        <section>
            <h2>Send Us a Message</h2>
            <form>
                <label for="name">Name:</label>
                <input type="text" id="name" placeholder="Enter your name" required>
                
                <label for="email">Email:</label>
                <input type="email" id="email" placeholder="Enter your email" required>
                
                <label for="message">Message:</label>
                <textarea id="message" rows="5" placeholder="Type your message here..." required></textarea>
                
                <button type="submit">Send</button>
            </form>
        </section>
        
        <section class="map">
            <h2>Our Location</h2>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.8436813776956!2d-122.08424948468247!3d37.422065979824814!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x808fba2b1d3cc5d1%3A0xbc9d01f5f6b9b8e2!2sGoogleplex!5e0!3m2!1sen!2sin!4v1683115027500!5m2!1sen!2sin" allowfullscreen></iframe>
        </section>
        
        <section class="social-links">
            <h2>Connect With Us</h2>
            <p>
                <a href="https://facebook.com" target="_blank">Facebook</a>
                <a href="https://twitter.com" target="_blank">Twitter</a>
                <a href="https://linkedin.com" target="_blank">LinkedIn</a>
            </p>
        </section>
        
        <section class="faq">
            <h2>Frequently Asked Questions</h2>
            <p><strong>Q:</strong> How can I update my alumni details?</p>
            <p><strong>A:</strong> Use the 'Update Profile' option in your dashboard.</p>
            <p><strong>Q:</strong> Who do I contact for technical support?</p>
            <p><strong>A:</strong> Please email us at support@example.com.</p>
        </section>
    </div>
    <footer>
        <p>&copy; 2025 Alumni Management System. All Rights Reserved.</p>
    </footer>
</body>
</html>
