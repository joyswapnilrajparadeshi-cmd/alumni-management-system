<?php
$url = "https://www.google.com";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo "cURL Error: " . curl_error($ch);
} else {
    echo "cURL request successful!";
}
curl_close($ch);
?>
