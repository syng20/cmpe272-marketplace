<?php
session_start();

$user = $_SESSION['user']['username'] ?? null;

// Get product id safely
$id = $_POST['id'] ?? null;
if (is_array($id)) $id = $id[0];
$id = (string)$id;

if ($id === null || $id === '') die("Product ID missing.");

$product = [
    'id' => $id,
    'name' => $_POST['name'] ?? '',
    'price' => $_POST['price'] ?? 0,
    'img' => $_POST['img'] ?? '',
    'origin' => $_POST['origin'] ?? ''
];

$quantity = (int)($_POST['quantity'] ?? 1);

// Load carts JSON
$cart_file = __DIR__ . '/../data/carts.json';
$carts = file_exists($cart_file) ? json_decode(file_get_contents($cart_file), true) : [];

// ---------- Logged-in user ----------
if ($user) {
    if (!isset($carts[$user])) $carts[$user] = [];

    // Merge session cart if exists
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $sess_id => $sess_item) {
            if (isset($carts[$user][$sess_id])) {
                $carts[$user][$sess_id]['quantity'] += $sess_item['quantity'];
            } else {
                $carts[$user][$sess_id] = $sess_item;
            }
        }
        unset($_SESSION['cart']);
    }

    // Add current product
    if (isset($carts[$user][$id])) {
        $carts[$user][$id]['quantity'] += $quantity;
    } else {
        $carts[$user][$id] = ['product' => $product, 'quantity' => $quantity];
    }

    file_put_contents($cart_file, json_encode($carts, JSON_PRETTY_PRINT));

    // Use session variable for message
    $_SESSION['cart_message'] = "Added {$product['name']} to your cart!";

    header("Location: ../product_page.php?origin={$product['origin']}&id={$product['id']}");
    exit;
}

// ---------- Guest session cart ----------
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['quantity'] += $quantity;
} else {
    $_SESSION['cart'][$id] = ['product' => $product, 'quantity' => $quantity];
}

// Session message for guest
$_SESSION['cart_message'] = "Added {$product['name']} to your cart!";

header("Location: ../product_page.php?origin={$product['origin']}&id={$product['id']}");
exit;
