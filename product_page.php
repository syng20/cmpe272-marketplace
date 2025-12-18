<?php
include 'handlers/product_page_handler.php';
include 'navbar.php';

// Review file
$reviewFile = __DIR__ . '/data/reviews.json';
$productKey = $product['origin'] . ':' . $product['id'];
$productReviews = [];

// Load reviews
if (file_exists($reviewFile)) {
    $allReviews = json_decode(file_get_contents($reviewFile), true) ?? [];
    $productReviews = $allReviews[$productKey] ?? [];
}


// Check if current user has reviewed
$userReview = null;
if (!empty($_SESSION['user']) && !empty($productReviews)) {
    $sessionUsername = $_SESSION['user']['username'] ?? '';
    foreach ($productReviews as $r) {
        if ($r['user'] === $sessionUsername) {
            $userReview = $r;
            break;
        }
    }
}

$most_visited = [];
if (isset($_COOKIE['recently_viewed'])) {
    $decoded = json_decode($_COOKIE['recently_viewed'], true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
        $most_visited = $decoded;
    }
}

$current = [
    'visits' => 1
];

foreach ($most_visited as $unit => $unit_array) {
    if ($unit == $product['name']) {
        $current['visits'] = $unit_array['visits'] + 1;
    }
}

$most_visited = [$product['name'] => $current] + $most_visited;
setcookie('recently_viewed', json_encode($most_visited), time() + (60 * 60 * 24 * 7));


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?></title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/product.css">
</head>

<body>
    <main>
        <!-- PRODUCT -->
        <div class="product-page">
            <div class="product-image">
                <img src="<?= htmlspecialchars($product['img']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
            </div>
            <div class="product-info">
                <h2><?= htmlspecialchars($product['name']) ?></h2>
                <p class="product-origin">Sold by: <?= htmlspecialchars($product['origin']) ?></p>
                <p class="price">$<?= number_format($product['price'], 2) ?></p>
                <?php if (!empty($product['description'])): ?>
                    <p><?= htmlspecialchars($product['description']) ?></p>
                <?php endif; ?>
                <form method="POST" action="/handlers/cart_handler.php">
                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="name" value="<?php echo $product['name']; ?>">
                    <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                    <input type="hidden" name="img" value="<?php echo $product['img']; ?>">
                    <input type="hidden" name="origin" value="<?php echo $product['origin']; ?>">
                    <label>Quantity: <input type="number" name="quantity" value="1" min="1"></label>
                    <button type="submit">Add to Cart</button>
                </form>

                <?php if (!empty($_SESSION['cart_message'])): ?>
                    <p style="color:green" class="cart-alert"><?= htmlspecialchars($_SESSION['cart_message']) ?></p>
                    <?php unset($_SESSION['cart_message']);
                    ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- REVIEWS -->
        <div class="reviews-section">
            <h3>Reviews</h3>

            <?php if ($productReviews): ?>
                <?php foreach ($productReviews as $review): ?>
                    <div class="review-box">
                        <strong><?= htmlspecialchars($review['user']) ?></strong>
                        <span>
                            <?php
                            $rating = (int)$review['rating'];
                            for ($i = 1; $i <= 5; $i++) {
                                echo $i <= $rating ? '⭐' : '☆';
                            }
                            ?>
                        </span>
                        <p><?= htmlspecialchars($review['comment']) ?></p>
                        <small><?= date('M j, Y', strtotime($review['created_at'])) ?></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No reviews yet.</p>
            <?php endif; ?>

            <!-- ADD / EDIT REVIEW -->
            <?php if (!empty($_SESSION['user'])): ?>
                <section class="add-review">
                    <h3><?= $userReview ? "Edit Your Review" : "Leave a Review" ?></h3>

                    <?php if (!empty($_SESSION['review_error'])): ?>
                        <p style="color:red"><?= $_SESSION['review_error'] ?></p>
                        <?php unset($_SESSION['review_error']); ?>
                    <?php endif; ?>

                    <?php if (!empty($_SESSION['review_success'])): ?>
                        <p style="color:green">Review updated!</p>
                        <?php unset($_SESSION['review_success']); ?>
                    <?php endif; ?>

                    <!-- Add/Edit Review Form -->
                    <form method="POST" action="handlers/review_handler.php" class="review-form">
                        <input type="hidden" name="origin" value="<?= htmlspecialchars($product['origin']) ?>">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
                        <input type="hidden" name="action" value="<?= $userReview ? 'edit' : 'add' ?>">

                        <label>Rating</label>
                        <select name="rating" required>
                            <option value="">Select</option>
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <option value="<?= $i ?>" <?= $userReview && $userReview['rating'] == $i ? 'selected' : '' ?>><?= $i ?></option>
                            <?php endfor; ?>
                        </select>

                        <label>Comment</label>
                        <textarea name="comment" required><?= $userReview ? htmlspecialchars($userReview['comment']) : '' ?></textarea>

                        <button type="submit"><?= $userReview ? "Update Review" : "Submit Review" ?></button>
                    </form>

                    <!-- Delete Review -->
                    <?php if ($userReview): ?>
                        <form method="POST" action="handlers/review_handler.php" style="margin-top:10px;">
                            <input type="hidden" name="origin" value="<?= htmlspecialchars($product['origin']) ?>">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
                            <input type="hidden" name="action" value="delete">
                            <button type="submit" style="background:red;color:white;">Delete Review</button>
                        </form>
                    <?php endif; ?>
                </section>
            <?php else: ?>
                <p><a href="login.php">Log in</a> to leave a review.</p>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>Ver 1.1.0</p>
    </footer>
</body>

</html>