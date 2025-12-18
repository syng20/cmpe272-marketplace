<?php
session_start();

$id = $_POST['id'] ?? null;
if ($id === null) die("Product ID missing.");

$user = $_SESSION['user']['username'] ?? null;
$cart_file = __DIR__ . '/../data/carts.json';

// Logged-in user cart
if ($user) {
    $carts = file_exists($cart_file) ? json_decode(file_get_contents($cart_file), true) : [];
    if (isset($carts[$user][$id])) {
        unset($carts[$user][$id]);
        file_put_contents($cart_file, json_encode($carts, JSON_PRETTY_PRINT));
    }
} else { // Guest session cart
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }
}

header("Location: /../cart.php");
exit;
