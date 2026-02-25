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
try {
    $sql = "SELECT * FROM jobs";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching jobs: " . $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Listings</title>
    <style>
        /* Your existing CSS styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            color: #333;
        }

        header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
        }

        h1 {
            font-size: 2.5em;
            font-weight: bold;
        }

        main {
            padding: 20px;
        }

        .job-listings {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .job {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .job:hover {
            transform: scale(1.05);
        }

        h2 {
            font-size: 1.8em;
            margin-bottom: 10px;
            color: #4CAF50;
        }

        p {
            font-size: 1em;
            margin-bottom: 8px;
        }

        .apply-form {
            margin-top: 15px;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }

        .apply-form input,
        .apply-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .apply-form button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px;
            font-size: 1em;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        .delete-button {
            margin-top: 10px;
            background-color: #FF4136;
            color: white;
            border: none;
            padding: 10px;
            font-size: 1em;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Job Opportunities</h1>
    </header>
    <main>
        <!-- Job Listings Section -->
        <section id="job-listings" class="job-listings">
            <?php if (count($jobs) > 0): ?>
                <?php foreach ($jobs as $job): ?>
                    <article class="job">
                        <h2><?= htmlspecialchars($job['title']) ?></h2>
                        <p><strong>Company:</strong> <?= htmlspecialchars($job['company']) ?></p>
                        <p><strong>Location:</strong> <?= htmlspecialchars($job['location']) ?></p>
                        <p><strong>Job Description:</strong> <?= htmlspecialchars($job['description']) ?></p>
                        <p><strong>Requirements:</strong> <?= htmlspecialchars($job['requirements']) ?></p>

                        <!-- Application Form -->
                        <div class="apply-form">
                            <h3>Apply for <?= htmlspecialchars($job['title']) ?></h3>
                            <form action="apply_job.php" method="POST">
                                <input type="hidden" name="job_id" value="<?= $job['id'] ?>"> <!-- Hidden field for job_id -->
                                <input type="text" name="name" placeholder="Your Name" required> <!-- Applicant Name -->
                                <input type="email" name="email" placeholder="Your Email" required> <!-- Email -->
                                <textarea name="cover_letter" placeholder="Your Cover Letter" required></textarea> <!-- Cover Letter -->
                                <button type="submit">Apply</button>
                            </form>
                        </div>

                        <!-- Delete Button (Visible to Admins Only) -->
                        <?php if ($user_role === 'admin'): ?>
                            <form action="delete_job.php" method="POST">
                                <input type="hidden" name="job_id" value="<?= $job['id'] ?>"> <!-- Hidden field for job_id -->
                                <button type="submit" class="delete-button">Delete Job</button>
                            </form>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No job listings available at the moment.</p>
            <?php endif; ?>
        </section>

        <!-- Add Job Form (Only for Admins) -->
        <?php if ($user_role === 'admin'): ?>
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
