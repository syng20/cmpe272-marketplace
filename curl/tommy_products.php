<?php

$url = "https://plb.bfm.mybluehost.me/righttwice/products.json";

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

// $products = $data['result'] ?? [];

/**
 * Convert from:
 *   slug => { title/cost/image/... }
 * to:
 *   [ { name, price, img, id }, ... ]
 */
$products = [];

$base = "https://plb.bfm.mybluehost.me/righttwice/";

foreach ($data as $slug => $p) {
    $img = $p["img"] ?? $p["image"] ?? "";

    // If it's a relative path like "assets/img/coffee cake.png", make it absolute
    if ($img !== "" && !preg_match('#^https?://#i', $img)) {
        $img = rtrim($base, "/") . "/" . ltrim($img, "/");
    }

    $products[] = [
        "id"    => $p["id"],
        "name"  => $p["model"] ?? $p["title"] ?? $slug,
        "price" => (float)($p["price"] ?? $p["cost"] ?? 0),
        "img"   => $img,
    ];
}
