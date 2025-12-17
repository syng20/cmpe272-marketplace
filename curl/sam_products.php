<?php

$url = "syng20.me/productlist.php";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo "cURL Error: " . curl_error($ch);
    exit;
}

curl_close($ch);

var_dump($response); 
echo "<pre>" . htmlspecialchars($response) . "</pre>";

// Decode JSON to array
$data = json_decode($response, true);

if ($data === null) {
    die('Error decoding the JSON file');
}
echo "<ul>\n";
$products = $data['products'] ?? [];
foreach ($products as $user) {
    echo "<li>" . $user['name'] . " " . $user['price'] . "</li>"; 
}
echo "</ul>";

?>
