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
    <title>Interactive Image Slider</title>
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

        /* Carousel Container */
        .carousel {
            position: relative;
            width: 80%;
            max-width: 800px;
            margin: 0 auto;
            overflow: hidden;
            border-radius: 8px;
        }

        .carousel-images {
            display: flex;
            transition: transform 0.5s ease;
        }

        .carousel-images img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .carousel-button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            z-index: 10;
            font-size: 2em;
            border-radius: 50%;
        }

        .prev {
            left: 10px;
        }

        .next {
            right: 10px;
        }

        .carousel-indicators {
            text-align: center;
            margin-top: 10px;
        }

        .carousel-indicators button {
            width: 10px;
            height: 10px;
            margin: 0 5px;
            background-color: #ddd;
            border: none;
            border-radius: 50%;
            display: inline-block;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .carousel-indicators button.active {
            background-color: #4CAF50;
        }

        .upload-form {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .upload-form input[type="file"] {
            margin-bottom: 10px;
        }

        .upload-form button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 1em;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .upload-form button:hover {
            background-color: #45a049;
        }

        .image-gallery img {
            width: 150px;
            height: auto;
            margin: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            .carousel {
                width: 100%;
            }

            .carousel-button {
                font-size: 1.5em;
                padding: 5px;
            }

            header, footer {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Alumni Gallery</h1>
    </header>

    <main>
        <!-- Image Carousel -->
        <section class="carousel">
            <div class="carousel-images" id="carouselImages">
                <!-- Preloaded images -->
                <?php
                $imageDir = 'uploads/gallery/';
                if (is_dir($imageDir)) {
                    $images = array_diff(scandir($imageDir), array('.', '..'));
                    foreach ($images as $image) {
                        echo '<img src="' . $imageDir . $image . '" alt="Gallery Image">';
                    }
                }
                ?>
            </div>
            <button class="carousel-button prev" aria-label="Previous slide" onclick="moveSlide(-1)">&#10094;</button>
            <button class="carousel-button next" aria-label="Next slide" onclick="moveSlide(1)">&#10095;</button>
            <div class="carousel-indicators" id="carouselIndicators"></div>
        </section>

        <!-- Image Gallery -->
        <section class="image-gallery">
            <h3>Gallery</h3>
            <div id="gallery">
                <?php
                if (is_dir($imageDir)) {
                    foreach ($images as $image) {
                        echo '<img src="' . $imageDir . $image . '" alt="Gallery Image">';
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
        const carouselImages = document.getElementById('carouselImages');
        const indicators = document.getElementById('carouselIndicators');
        const images = carouselImages.getElementsByTagName('img');
        let currentIndex = 0;

        // Create indicators dynamically
        function updateIndicators() {
            indicators.innerHTML = '';
            for (let i = 0; i < images.length; i++) {
                const button = document.createElement('button');
                button.classList.add(i === currentIndex ? 'active' : '');
                button.setAttribute('aria-label', `Slide ${i + 1}`);
                button.onclick = () => goToSlide(i);
                indicators.appendChild(button);
            }
        }

        function moveSlide(step) {
            currentIndex += step;
            if (currentIndex >= images.length) currentIndex = 0;
            if (currentIndex < 0) currentIndex = images.length - 1;
            updateSlide();
        }

        function goToSlide(index) {
            currentIndex = index;
            updateSlide();
        }

        function updateSlide() {
            carouselImages.style.transform = `translateX(-${currentIndex * 100}%)`;
            updateIndicators();
        }

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

        // Initialize indicators
        updateIndicators();
    </script>
</body>
</html>
