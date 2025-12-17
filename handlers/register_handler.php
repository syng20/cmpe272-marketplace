<?php
session_start();

$file = __DIR__ . '/../data/users.json';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['register_errors'] = ['Invalid request'];
    header('Location: /register.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

$errors = [];


if (!$username || !$email || !$password || !$confirm) {
    $errors[] = 'All fields are required.';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email address.';
}

if ($password !== $confirm) {
    $errors[] = 'Passwords do not match.';
}

if (strlen($password) < 6) {
    $errors[] = 'Password must be at least 6 characters.';
}

$users = [];
if (file_exists($file)) {
    $json = file_get_contents($file);
    $users = json_decode($json, true) ?? [];
}

foreach ($users as $user) {
    if ($user['email'] === $email) {
        $errors[] = 'Email already registered.';
    }
    if ($user['username'] === $username) {
        $errors[] = 'Username already taken.';
    }
}

if (!empty($errors)) {
    $_SESSION['register_errors'] = $errors;
    header('Location: /register.php');
    exit;
}

$newUser = [
    'id' => uniqid('user_', true),
    'username' => $username,
    'email' => $email,
    'password' => password_hash($password, PASSWORD_DEFAULT),
    'created_at' => date('c')
];

$users[] = $newUser;

if (!is_dir(dirname($file))) {
    mkdir(dirname($file), 0777, true);
}

file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));


$_SESSION['register_success'] = true;
header('Location: /register.php');
exit;
