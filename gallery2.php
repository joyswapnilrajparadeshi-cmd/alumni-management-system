<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Check if the user is an admin
$isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive Image Gallery</title>
    <style>
        /* General Reset */
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

        .gallery-item {
            display: inline-block;
            margin: 10px;
            position: relative;
        }

        .gallery-item img {
            width: 150px;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .delete-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: red;
            color: white;
            border: none;
            padding: 5px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 0.9em;
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
        <h1>Alumni Gallery</h1>
    </header>

    <main>
        <section class="image-gallery">
            <h3>Gallery</h3>
            <div id="gallery">
                <?php
                $imageDir = 'uploads/gallery/';
                if (is_dir($imageDir)) {
                    $images = array_diff(scandir($imageDir), array('.', '..')); // Get all images in the directory
                    foreach ($images as $image) {
                        echo '<div class="gallery-item">';
                        echo '<img src="' . $imageDir . $image . '" alt="Gallery Image">';
                        if ($isAdmin) {
                            // Add delete button for admins
                            echo '<button class="delete-btn" data-image="' . $image . '">Delete</button>';
                        }
                        echo '</div>';
                    }
                } else {
                    echo '<p>No images to display.</p>';
                }
                ?>
            </div>
        </section>

        <?php if ($isAdmin): ?>
        <!-- Upload Image Form (Visible only to admin) -->
        <section class="upload-form">
            <h3>Upload an Image</h3>
            <form id="imageUploadForm">
                <input type="file" id="imageFile" name="imageFile" accept="image/*" required>
                <button type="submit">Upload Image</button>
            </form>
        </section>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2025 Interactive Image Gallery | All rights reserved.</p>
    </footer>

    <script>
        // Handle delete button clicks
        const deleteButtons = document.querySelectorAll('.delete-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', () => {
                const imageName = button.getAttribute('data-image');

                if (confirm('Are you sure you want to delete this image?')) {
                    // Send a POST request to delete the image
                    fetch('delete_image.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `image=${encodeURIComponent(imageName)}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            button.parentElement.remove(); // Remove the image container
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the image.');
                    });
                }
            });
        });

        <?php if ($isAdmin): ?>
        document.getElementById('imageUploadForm').addEventListener('submit', function (event) {
            event.preventDefault();
            const formData = new FormData(this);

            fetch('upload.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload(); // Reload to display new images
                } else {
                    alert('Image upload failed: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
        <?php endif; ?>
    </script>
</body>
</html>
