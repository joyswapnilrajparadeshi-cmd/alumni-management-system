<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Check if user is an admin
$isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';

// Include database connection
require_once 'db_connection.php';

// Fetch mentors from the database using MySQLi
$mentors = [];
$sql = "SELECT id, name, description, image_path FROM mentors";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    $mentors = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    die("Error preparing statement: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentorship Program</title>
    <style>
        /* General Reset */
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: Arial, sans-serif; background-color:#f4f7fc; color:#333; }
        header { background-color: #4CAF50; color:white; padding:20px; text-align:center; }
        h1 { font-size:2.5em; font-weight:bold; }
        main { padding:20px; }

        /* Mentor Cards */
        .mentor-carousel { position: relative; width: 80%; max-width:900px; margin:0 auto; overflow:hidden; border-radius:8px; }
        .mentor-cards { display:flex; transition: transform 0.5s ease; }
        .mentor-card { min-width:100%; max-width:100%; padding:20px; text-align:center; background-color:white; border-radius:8px; box-shadow:0 4px 8px rgba(0,0,0,0.1); position:relative; }
        .mentor-card img { width:150px; height:150px; border-radius:50%; margin-bottom:15px; }
        .mentor-card h3 { font-size:1.5em; margin-bottom:10px; }
        .mentor-card p { font-size:1em; color:#555; }

        .delete-button { position:absolute; top:10px; right:10px; background-color:#FF5733; color:white; border:none; padding:5px 10px; border-radius:5px; cursor:pointer; }
        .delete-button:hover { background-color:#E84118; }

        .carousel-button { position:absolute; top:50%; transform:translateY(-50%); background-color:rgba(0,0,0,0.5); color:white; border:none; padding:10px; cursor:pointer; z-index:10; font-size:2em; border-radius:50%; }
        .prev { left:10px; }
        .next { right:10px; }

        .upload-form { background-color:white; padding:20px; border-radius:8px; border:1px solid #ddd; box-shadow:0 4px 8px rgba(0,0,0,0.1); margin-top:20px; }
        .upload-form input, .upload-form textarea { width:calc(100% - 20px); margin-bottom:10px; padding:10px; border:1px solid #ddd; border-radius:5px; }
        .upload-form button { background-color:#4CAF50; color:white; border:none; padding:10px 20px; font-size:1em; border-radius:5px; cursor:pointer; transition: background-color 0.3s; }
        .upload-form button:hover { background-color:#45a049; }

        footer { background-color:#333; color:white; text-align:center; padding:10px; margin-top:20px; }
    </style>
</head>
<body>
    <header>
        <h1>Mentorship Program</h1>
    </header>

    <main>
        <!-- Mentor Carousel -->
        <section class="mentor-carousel">
            <div class="mentor-cards" id="mentorCards">
                <?php foreach ($mentors as $mentor): ?>
                    <div class="mentor-card" data-id="<?php echo $mentor['id']; ?>">
                        <img src="<?php echo htmlspecialchars($mentor['image_path']); ?>" alt="<?php echo htmlspecialchars($mentor['name']); ?>">
                        <h3><?php echo htmlspecialchars($mentor['name']); ?></h3>
                        <p><?php echo htmlspecialchars($mentor['description']); ?></p>
                        <?php if ($isAdmin): ?>
                            <button class="delete-button" onclick="deleteMentor(<?php echo $mentor['id']; ?>)">Delete</button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- Carousel Navigation -->
            <button class="carousel-button prev" onclick="moveSlide(-1)">&#10094;</button>
            <button class="carousel-button next" onclick="moveSlide(1)">&#10095;</button>
        </section>

        <!-- Upload Mentor Form (Admins only) -->
        <?php if ($isAdmin): ?>
            <section class="upload-form">
                <h3>Add a Mentor</h3>
                <form id="addMentorForm" enctype="multipart/form-data">
                    <input type="text" name="name" placeholder="Mentor Name" required />
                    <textarea name="description" placeholder="Mentor Description" required></textarea>
                    <input type="file" name="image" accept="image/*" required />
                    <button type="submit">Add Mentor</button>
                </form>
            </section>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2025 Mentorship Program | All rights reserved.</p>
    </footer>

    <script>
        const mentorCards = document.getElementById('mentorCards');
        const mentorSlides = mentorCards.children;
        let currentIndex = 0;

        function moveSlide(step) {
            currentIndex += step;
            if (currentIndex >= mentorSlides.length) currentIndex = 0;
            if (currentIndex < 0) currentIndex = mentorSlides.length - 1;
            mentorCards.style.transform = `translateX(-${currentIndex * 100}%)`;
        }

        const addMentorForm = document.getElementById('addMentorForm');
        if (addMentorForm) {
            addMentorForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                fetch('add_mentor.php', {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.success) location.reload();
                })
                .catch(err => alert('Error adding mentor.'));
            });
        }

        function deleteMentor(mentorId) {
            if (!confirm('Are you sure you want to delete this mentor?')) return;
            fetch('delete_mentor.php', {
                method: 'POST',
                headers: { 'Content-Type':'application/x-www-form-urlencoded' },
                body: `id=${mentorId}`
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) location.reload();
            })
            .catch(err => alert('Error deleting mentor.'));
        }
    </script>
</body>
</html>
