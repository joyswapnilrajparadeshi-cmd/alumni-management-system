<?php
// Initialize a cURL session
$url = "https://www.google.com"; // Example HTTPS URL
$ch = curl_init($url);

// Set options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute the request
$output = curl_exec($ch);

// Check for errors
if ($output === false) {
    echo "cURL Error: " . curl_error($ch);
} else {
    echo "Successfully connected to " . $url;
}

// Close the cURL session
curl_close($ch);
?>
