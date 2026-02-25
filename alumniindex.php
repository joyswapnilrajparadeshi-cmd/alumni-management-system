<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Alumni Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #ff7e5f, #feb47b, #56ccf2, #6a11cb);
            color: #333;
            margin: 0;
            padding: 0;
        }
        header {
            padding: 50px 20px;
            color: #fff;
            text-align: center;
            background: linear-gradient(90deg, #ff6f61, #d6336c, #8a2be2);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
        header h1, header p {
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
        }
        .info-section {
            padding: 20px;
            text-align: center;
        }
        .info-section h2 {
            margin-bottom: 20px;
            color: inear-gradient(90deg, #ff6f61, #d6336c, #8a2be2);
            font-weight: bold;
        }
        .info-section p {
            font-size: 1.5em; /* 5 times larger */
            font-weight: bold;
        }
        nav {
            background: #333;
            padding: 10px 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
        }
        nav a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
            font-size: 1.2em;
            font-weight: bold;
        }
        nav a:hover {
            color: #feb47b;
        }
        .image-gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            padding: 20px;
        }
        .image-gallery img {
            width: 100%;
            max-width: 200px;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
        }
        footer {
            background: linear-gradient(90deg, #8a2be2, #d6336c, #ff6f61);
            color: #fff;
            text-align: center;
            padding: 20px;
            box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body>
    <header>
        <h1>Alumni Management System</h1>
    </header>

    <div class="info-section">
        <h2>About the Alumni Management System</h2>
        <p>
            The Alumni Management System of JNTUA is designed to foster connections between alumni and their alma mater. 
            It provides a platform for networking, career growth, and collaboration among alumni, students, and the university. 
            This system serves as a bridge to share achievements, offer mentorship, and promote lifelong engagement with the university community.
        </p>
        <p>
            Join us to stay updated on alumni events, contribute to institutional development, and celebrate your achievements with a global network of peers.
        </p>
    </div>

    <nav>
        <a href="alumniindex.php">Home</a>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="login2.html">Login</a>
            <a href="register2.html">Register</a>
        <?php else: ?>
            <a href="logout.php">Logout</a>
        <?php endif; ?>
        <a href="events.php">Events</a>
        <a href="jobs.php">Jobs</a>
        <a href="mentorship.php">Mentorship</a>
        <a href="gallery2.php">Gallery</a>
        <a href="contact.php">Contact Us</a>
        <a href="profile.php">Profile</a>
        <a href="video.php">Videos</a>
        <a href="donate2.html">Donate</a>
    </nav>

    <div class="image-gallery">
        <img src="jntua1.jpg" alt="JNTUA Building">
        <img src="jntua2.jpg" alt="JNTUA Campus">
        <img src="jntua3.jpg" alt="JNTUA Event">
        <img src="jntua4.jpg" alt="JNTUA Alumni Meet">
    </div>

    <footer>
        <p>&copy; 2025 Alumni Management System. All rights reserved.</p>
    </footer>
</body>
</html>
