<?php
$url = "https://seanhtran.com/data/products.json";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    die("cURL Error: " . curl_error($ch));
}
curl_close($ch);

$data = json_decode($response, true) ?? [];

/**
 * Convert from:
 *   slug => { title/cost/image/... }
 * to:
 *   [ { name, price, img, id }, ... ]
 */
$products = [];

$base = "https://seanhtran.com/";

$index = 0;

foreach ($data as $slug => $p) {
    $img = $p["img"] ?? $p["image"] ?? "";

    if ($img !== "" && !preg_match('#^https?://#i', $img)) {
        $img = rtrim($base, "/") . "/" . ltrim($img, "/");
    }

    $products[] = [
        "id"     => $index,
        "name"   => $p["name"] ?? $p["title"] ?? $slug,
        "price"  => (float)($p["price"] ?? $p["cost"] ?? 0),
        "img"    => $img,
        "origin" => "jusobakery"
    ];

    $index++;
}