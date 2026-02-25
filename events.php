<?php
session_start();
require_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch user role from session
$user_role = $_SESSION['user_role'];

// Pagination setup
$limit = 5; // Events per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - Alumni Management System</title>
    <style>
        body { font-family: Arial, sans-serif; margin:0; padding:0; background:#f4f4f4; }
        header { background:#333; color:#fff; padding:10px 0; text-align:center; }
        nav { display:flex; justify-content:center; background:#444; padding:10px; }
        nav a { color:white; text-decoration:none; margin:0 15px; padding:5px 10px; }
        nav a:hover { background:#555; border-radius:5px; }
        main { padding:20px; text-align:center; }
        .event { background:white; padding:15px; border-radius:5px; box-shadow:0 2px 5px rgba(0,0,0,0.1); margin:20px auto; width:80%; text-align:left; }
        .event h3 { margin:0 0 10px; }
        .event p { margin:5px 0; }
        footer { background:#333; color:#fff; text-align:center; padding:10px 0; margin-top:20px; }
        form { background:white; padding:20px; margin:20px auto; width:80%; border-radius:5px; box-shadow:0 2px 5px rgba(0,0,0,0.1); }
        form input, form textarea, form button { margin:10px 0; padding:10px; width:95%; }
        form button { background:#444; color:white; border:none; cursor:pointer; }
        form button:hover { background:#555; }
    </style>
</head>
<body>
<header>
    <h1>Events - Alumni Management System</h1>
</header>
<nav>
    <a href="home.html">Home</a>
    <a href="events.php">Events</a>
    <a href="jobs.php">Jobs</a>
    <a href="mentorship.php">Mentorship</a>
    <a href="gallery2.php">Gallery</a>
    <a href="contact.php">Contact</a>
</nav>
<main>
    <h2>Upcoming Events</h2>

    <!-- Admin: Add Event Form -->
    <?php if ($user_role === 'admin'): ?>
    <form action="add_event.php" method="POST">
        <h3>Add New Event</h3>
        <input type="text" name="title" placeholder="Event Title" required><br>
        <input type="date" name="date" required><br>
        <input type="text" name="venue" placeholder="Event Venue" required><br>
        <textarea name="description" placeholder="Event Description" required></textarea><br>
        <button type="submit">Add Event</button>
    </form>
    <?php endif; ?>

    <!-- Fetch and display events -->
    <?php
    // Fetch events using MySQLi with LIMIT
    $stmt = $conn->prepare("SELECT * FROM events ORDER BY date ASC LIMIT ?, ?");
    $stmt->bind_param("ii", $offset, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="event">';
            echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
            echo '<p><strong>Date:</strong> ' . htmlspecialchars($row['date']) . '</p>';
            echo '<p><strong>Venue:</strong> ' . htmlspecialchars($row['venue']) . '</p>';
            echo '<p>' . htmlspecialchars($row['description']) . '</p>';
            if ($user_role === 'admin') {
                echo '<form action="delete_event.php" method="POST" style="display:inline;">
                        <input type="hidden" name="event_id" value="' . $row['id'] . '">
                        <button type="submit" style="background-color:red;color:white;border:none;padding:5px 10px;cursor:pointer;">Delete</button>
                      </form>';
            }
            echo '</div>';
        }
    } else {
        echo '<p>No events found.</p>';
    }

    $stmt->close();
    ?>

    <!-- Pagination -->
    <?php
    $total_stmt = $conn->prepare("SELECT COUNT(*) AS total FROM events");
    $total_stmt->execute();
    $total_result = $total_stmt->get_result()->fetch_assoc();
    $total_events = $total_result['total'];
    $total_pages = ceil($total_events / $limit);

    if ($total_pages > 1) {
        echo '<div style="margin-top:20px;">';
        for ($i = 1; $i <= $total_pages; $i++) {
            echo '<a href="events.php?page=' . $i . '" style="margin:0 5px; padding:5px 10px; text-decoration:none; background:#444; color:white; border-radius:5px;">' . $i . '</a>';
        }
        echo '</div>';
    }

    $total_stmt->close();
    $conn->close();
    ?>
</main>
<footer>
    <p>&copy; 2025 Alumni Management System. All rights reserved.</p>
</footer>
</body>
</html>
