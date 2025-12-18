<?php
session_start();

$file = __DIR__ . '/../data/users.json';

$_SESSION['login_errors'] = [];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['login_errors'][] = "Invalid email or password.";
    header('Location: ../login.php');
    exit;
}


$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!$email || !$password) {
    $_SESSION['login_errors'][] = "Invalid email or password.";
    header('Location: ../login.php');
    exit;
}

if (!file_exists($file)) {
    $_SESSION['login_errors'][] = "Invalid email or password.s";
    header('Location: ../login.php');
    exit;
}

$json = file_get_contents($file);
$users = json_decode($json, true) ?? [];

$userFound = null;
foreach ($users as $user) {
    if (strtolower($user['email']) === strtolower($email)) {
        $userFound = $user;
        break;
    }
}

if (!$userFound || !password_verify($password, $userFound['password'])) {
    $_SESSION['login_errors'][] = "Invalid email or password.";
    header('Location: ../login.php');
    exit;
}

$_SESSION['user'] = [
    'id' => $userFound['id'],
    'username' => $userFound['username'],
    'email' => $userFound['email']
];


// Merge Session Cart To User
$user = $_SESSION['user']['username'] ?? null;

if ($user && isset($_SESSION['cart'])) {
    $cart_file = __DIR__ . '/../data/carts.json';
    $carts = file_exists($cart_file) ? json_decode(file_get_contents($cart_file), true) : [];

    if (!isset($carts[$user])) $carts[$user] = [];

    foreach ($_SESSION['cart'] as $sess_id => $sess_item) {
        if (isset($carts[$user][$sess_id])) {
            $carts[$user][$sess_id]['quantity'] += $sess_item['quantity'];
        } else {
            $carts[$user][$sess_id] = $sess_item;
        }
    }

    unset($_SESSION['cart']);
    file_put_contents($cart_file, json_encode($carts, JSON_PRETTY_PRINT));
}


header('Location: ../index.php');
exit;
