<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://repo.packagist.org");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
$output = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'cURL Error: ' . curl_error($ch);
} else {
    echo 'cURL Success: ' . $output;
}
curl_close($ch);
?>
