<?php

$url = "https://syng20.me/productlist.php";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo "cURL Error: " . curl_error($ch);
    exit;
}

curl_close($ch);


// Decode JSON to array
$data = json_decode($response, true);

if ($data === null) {
    die('Error decoding the JSON file');
}

$products = $data['products'] ?? [];

?>
