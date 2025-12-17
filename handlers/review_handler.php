<?php
session_start();

if (empty($_SESSION['user'])) {
    $_SESSION['review_error'] = "You must be logged in to submit a review.";
    header('Location: ../product_page.php?id=' . ($_POST['id'] ?? '') . '&origin=' . ($_POST['origin'] ?? ''));
    exit;
}

$reviewFile = __DIR__ . '/../data/reviews.json';
$productKey = ($_POST['origin'] ?? '') . ':' . ($_POST['id'] ?? '');
$currentUser = $_SESSION['user']['username'] ?? '';

$allReviews = [];
if (file_exists($reviewFile)) {
    $allReviews = json_decode(file_get_contents($reviewFile), true) ?? [];
}

// Ensure array exists for this product
if (!isset($allReviews[$productKey])) {
    $allReviews[$productKey] = [];
}

$action = $_POST['action'] ?? 'add';

// Find index of existing review by this user
$existingIndex = null;
foreach ($allReviews[$productKey] as $idx => $review) {
    if ($review['user'] === $currentUser) {
        $existingIndex = $idx;
        break;
    }
}

switch ($action) {
    case 'add':
        if ($existingIndex !== null) {
            $_SESSION['review_error'] = "You have already reviewed this product.";
            break;
        }

        $rating = intval($_POST['rating'] ?? 0);
        $comment = trim($_POST['comment'] ?? '');

        if ($rating < 1 || $rating > 5 || !$comment) {
            $_SESSION['review_error'] = "Please provide a valid rating and comment.";
            break;
        }

        $newReview = [
            'user' => $currentUser,
            'rating' => $rating,
            'comment' => $comment,
            'created_at' => date('c')
        ];

        $allReviews[$productKey][] = $newReview;
        $_SESSION['review_success'] = true;
        break;

    case 'edit':
        if ($existingIndex === null) {
            $_SESSION['review_error'] = "No existing review to edit.";
            break;
        }

        $rating = intval($_POST['rating'] ?? 0);
        $comment = trim($_POST['comment'] ?? '');

        if ($rating < 1 || $rating > 5 || !$comment) {
            $_SESSION['review_error'] = "Please provide a valid rating and comment.";
            break;
        }

        $allReviews[$productKey][$existingIndex]['rating'] = $rating;
        $allReviews[$productKey][$existingIndex]['comment'] = $comment;
        $allReviews[$productKey][$existingIndex]['created_at'] = date('c');
        $_SESSION['review_success'] = true;
        break;

    case 'delete':
        if ($existingIndex === null) {
            $_SESSION['review_error'] = "No existing review to delete.";
            break;
        }

        array_splice($allReviews[$productKey], $existingIndex, 1);
        $_SESSION['review_success'] = true;
        break;

    default:
        $_SESSION['review_error'] = "Invalid action.";
        break;
}

// Save reviews
if (file_put_contents($reviewFile, json_encode($allReviews, JSON_PRETTY_PRINT)) === false) {
    $_SESSION['review_error'] = "Failed to save review. Please try again.";
}

// Redirect back to product page
$origin = urlencode($_POST['origin'] ?? '');
$id = urlencode($_POST['id'] ?? '');
header("Location: ../product_page.php?origin={$origin}&id={$id}");
exit;
