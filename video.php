<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Check if the user is an admin
$isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';

// Include the database connection file
include 'db_connection.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Videos - Alumni Management System</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #feb47b, #ff7e5f);
            margin: 0;
            padding: 0;
            color: #333;
        }
        header {
            padding: 20px;
            text-align: center;
            background: linear-gradient(90deg, #8a2be2, #d6336c, #ff6f61);
            color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
        header h1 {
            margin: 0;
        }
        .video-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .video-container h2 {
            text-align: center;
            color: #ff6f61;
        }
        .video-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 15px;
        }
        .video-item {
            position: relative;
        }
        .video-item iframe {
            width: 100%;
            height: 200px;
            border: none;
            border-radius: 10px;
        }
        .delete-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #ff6f61;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .add-video {
            text-align: center;
            margin: 20px 0;
        }
        .add-video input {
            padding: 10px;
            width: 60%;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .add-video button {
            background: linear-gradient(90deg, #56ccf2, #6a11cb);
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }
        .add-video button:hover {
            background: #d6336c;
        }
    </style>
</head>
<body>
    <header>
        <h1>Videos</h1>
    </header>

    <div class="video-container">
        <h2>Our Alumni Videos</h2>

        <div class="video-list">
            <?php
            $sql = "SELECT id, url FROM videos";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="video-item">';
                    echo '<iframe src="' . htmlspecialchars($row['url']) . '" allowfullscreen></iframe>';

                    if ($isAdmin) {
                        echo '<button class="delete-btn" onclick="deleteVideo(' . $row['id'] . ')">Delete</button>';
                    }

                    echo '</div>';
                }
            } else {
                echo "<p style='text-align:center;'>No videos available.</p>";
            }
            ?>
        </div>

        <?php if ($isAdmin): ?>
        <div class="add-video">
            <input type="text" id="videoUrl" placeholder="Enter YouTube embed URL">
            <button onclick="addVideo()">Add Video</button>
        </div>
        <?php endif; ?>
    </div>

    <script>
        function addVideo() {
            const videoUrl = document.getElementById('videoUrl').value.trim();
            const youtubeRegex = /^https:\/\/www\.youtube\.com\/embed\/[a-zA-Z0-9_-]+$/;

            if (!youtubeRegex.test(videoUrl)) {
                alert('Please enter a valid YouTube embed URL.');
                return;
            }

            fetch('add_video.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'videoUrl=' + encodeURIComponent(videoUrl)
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.success) location.reload();
            })
            .catch(err => console.error(err));
        }

        function deleteVideo(id) {
            if (!confirm("Delete this video?")) return;

            fetch('delete_video.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'videoId=' + id
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.success) location.reload();
            })
            .catch(err => console.error(err));
        }
    </script>
</body>
</html>