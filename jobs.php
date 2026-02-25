<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Include database connection
require_once 'db_connection.php';

// Fetch user role from session
$user_role = $_SESSION['user_role'];

// Fetch jobs from the database
$jobs = [];
$sql = "SELECT * FROM jobs";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result(); // MySQLi way
    $jobs = $result->fetch_all(MYSQLI_ASSOC); // fetch_all for associative array
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
    <title>Job Listings</title>
    <style>
        /* Your existing CSS */
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:Arial,sans-serif; background-color:#f4f7fc; color:#333; }
        header { background-color:#4CAF50; color:white; padding:20px; text-align:center; }
        h1 { font-size:2.5em; font-weight:bold; }
        main { padding:20px; }
        .job-listings { display:flex; flex-direction:column; gap:20px; }
        .job { background-color:white; border:1px solid #ddd; border-radius:8px; padding:20px; box-shadow:0 4px 8px rgba(0,0,0,0.1); transition: transform 0.3s ease; }
        .job:hover { transform: scale(1.05); }
        h2 { font-size:1.8em; margin-bottom:10px; color:#4CAF50; }
        p { font-size:1em; margin-bottom:8px; }
        form { margin-top:10px; }
        form button { background-color:red; color:white; padding:8px 16px; border:none; border-radius:5px; cursor:pointer; }
        footer { background-color:#333; color:white; text-align:center; padding:10px; margin-top:20px; }
    </style>
</head>
<body>
    <header>
        <h1>Job Opportunities</h1>
    </header>
    <main>
        <section id="job-listings" class="job-listings">
            <?php if (count($jobs) > 0): ?>
                <?php foreach ($jobs as $job): ?>
                    <article class="job">
                        <h2><?= htmlspecialchars($job['title']) ?></h2>
                        <p><strong>Company:</strong> <?= htmlspecialchars($job['company']) ?></p>
                        <p><strong>Location:</strong> <?= htmlspecialchars($job['location']) ?></p>
                        <p><strong>Job Description:</strong> <?= htmlspecialchars($job['description']) ?></p>
                        <p><strong>Requirements:</strong> <?= htmlspecialchars($job['requirements']) ?></p>

                        <?php if ($user_role === 'admin'): ?>
                            <form action="delete_job.php" method="POST">
                                <input type="hidden" name="job_id" value="<?= $job['id'] ?>">
                                <button type="submit">Delete Job</button>
                            </form>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No job listings available at the moment.</p>
            <?php endif; ?>
        </section>

        <?php if ($user_role === 'admin' || $user_role === 'mentor'): ?>
            <section id="add-job">
                <h2>Add New Job</h2>
                <form action="add_job.php" method="POST">
                    <input type="text" name="title" placeholder="Job Title" required><br>
                    <input type="text" name="company" placeholder="Company Name" required><br>
                    <input type="text" name="location" placeholder="Location" required><br>
                    <textarea name="description" placeholder="Job Description" required></textarea><br>
                    <textarea name="requirements" placeholder="Job Requirements" required></textarea><br>
                    <button type="submit">Add Job</button>
                </form>
            </section>
        <?php endif; ?>
    </main>
    <footer>
        <p>&copy; 2025 Job Listings | All rights reserved.</p>
    </footer>
</body>
</html>
