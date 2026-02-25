<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'db_connection.php';

$user_id = $_SESSION['user_id'];

// MySQLi prepared statement
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

// Message handling
$successMsg = '';
$errorMsg   = '';

if (isset($_GET['success'])) $successMsg = "Profile updated successfully!";
if (isset($_GET['error']))   $errorMsg   = "Something went wrong. Please try again!";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile - Alumni Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #6a11cb, #56ccf2);
            margin: 0;
            padding: 0;
            color: #333;
        }

        header {
            padding: 20px;
            text-align: center;
            background: linear-gradient(90deg, #ff6f61, #d6336c, #8a2be2);
            color: #fff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }

        header a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
        }

        .profile-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 25px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .profile-container h2 {
            text-align: center;
            color: #8a2be2;
        }

        .message {
            text-align: center;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-weight: bold;
        }

        .success {
            background: #d4edda;
            color: #155724;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
        }

        .profile-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .profile-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 18px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .profile-form button {
            width: 100%;
            background: linear-gradient(90deg, #ff6f61, #d6336c);
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }

        .profile-form button:hover {
            background: #8a2be2;
        }
    </style>
</head>
<body>

<header>
    <h1>Manage Your Profile</h1>
    <a href="logout.php">Logout</a>
</header>

<div class="profile-container">
    <h2>Update Your Information</h2>

    <?php if ($successMsg): ?>
        <div class="message success"><?= $successMsg ?></div>
    <?php endif; ?>

    <?php if ($errorMsg): ?>
        <div class="message error"><?= $errorMsg ?></div>
    <?php endif; ?>

    <form class="profile-form" method="POST" action="update_profile.php">
        <label>Full Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

        <label>Email Address</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

        <label>Phone Number</label>
        <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>

        <label>Short Bio</label>
        <input type="text" name="bio" value="<?= htmlspecialchars($user['bio']) ?>">

        <button type="submit">Save Profile</button>
    </form>
</div>

</body>
</html>