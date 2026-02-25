<?php
$url = "https://www.google.com";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo "SSL is not enabled: " . curl_error($ch);
} else {
    echo "SSL is enabled and working.";
}
curl_close($ch);
?>
