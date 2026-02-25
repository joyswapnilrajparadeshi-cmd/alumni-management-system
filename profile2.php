<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Include database connection
require_once 'db_connection.php';

// Fetch user data
$user_id = $_SESSION['user_id'];
try {
    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching user data: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile - Alumni Management System</title>
    <style>
        /* Your existing CSS styles */
         body {
            font-family: 'Arial', sans-serif;
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
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
        header h1 {
            margin: 0;
        }
        .profile-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .profile-container h2 {
            text-align: center;
            color: #8a2be2;
        }
        .profile-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .profile-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .profile-form button {
            background: linear-gradient(90deg, #ff6f61, #d6336c);
            color: #fff;
            padding: 10px 20px;
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
        <a href="logout.php" style="color: white; text-decoration: none;">Logout</a>
    </header>

    <div class="profile-container">
        <h2>Update Your Information</h2>
        <form class="profile-form" method="POST" action="update_profile.php">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>

            <label for="bio">Short Bio</label>
            <input type="text" id="bio" name="bio" value="<?= htmlspecialchars($user['bio']) ?>">

            <button type="submit">Save Profile</button>
        </form>
    </div>
</body>
</html>
