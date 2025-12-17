<?php

$origin = $_GET['origin'] ?? '';
$id     = $_GET['id'] ?? null;

if ($origin === '' || $id === null) {
    die('Missing origin or id');
}

$product = null;

if ($origin === 'spartanmarket') {
    include 'curl/william_products.php';
} elseif ($origin === 'newleafapiary') {
    include 'curl/sam_products.php';
} elseif ($origin === 'jusobakery') {
    include 'curl/sean_products.php';
} else {
    die('Invalid origin specified');
}

    $product = $products[$id];