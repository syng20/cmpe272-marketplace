<?php
// Filter States
$selectedCategory = $_GET['category'] ?? 'all';

// Initialize product arrays
$allProducts = [];
$williamProducts = [];
$seanProducts = [];
$samProducts = [];
$tommyProducts = [];

// Include product sources
include 'curl/william_products.php';
$williamProducts = $products;

// sam's include 
include 'curl/sam_products.php';
$samProducts = $products;

// Sean's include
include 'curl/sean_products.php';
$seanProducts = $products;

// Tommy's include
include 'curl/tommy_products.php';
$tommyProducts = $products;

// Merge after all product calls (please implement curl to your company website)
$allProducts = array_merge($williamProducts, $samProducts, $seanProducts, $tommyProducts);

// Choose products to display
if ($selectedCategory === 'spartan') {
    $displayProducts = $williamProducts;
} else if ($selectedCategory == 'newleaf') {
    $displayProducts = $samProducts;
} else if ($selectedCategory == 'bakery') {
    $displayProducts = $seanProducts;
} else if ($selectedCategory == 'righttwice') {
    $displayProducts = $tommyProducts;
} else {
    $displayProducts = $allProducts;
}

// Cookies
if (isset($_COOKIE['recently_viewed'])) {
    $v = stripslashes($_COOKIE['recently_viewed']);
    $most_visited = json_decode($v, true);
} else {
    $most_visited = array();
}

// Sort most visited by visits descending
arsort($most_visited);

$top5 = [];
foreach ($most_visited as $unit => $unit_array) {
    foreach ($displayProducts as $product) {
        if ($product['name'] == $unit) {
            $top5[] = [
                "id" => $product['id'],
                "origin" => $product['origin'],
                "name" => $product['name'],
                "price" => $product['price'],
                "img" => $product['img'],
                "origin" => $product['origin'],
            ];
            break;
        }
    }
    if (count($top5) >= 5) {
        break;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace Listings</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/products.css">
</head>

<body>
    <header>
        <?php include 'navbar.php'; ?>
    </header>

    <main class="container">
        <h2 class="page-title">Top 5 Most Viewed Items</h2>

        <div class="market-layout">
            <!-- Sidebar Filters -->
            <aside id="filter-sidebar">

                <!-- Clear Filters -->
                <?php if ($selectedCategory !== 'all') : ?>
                    <a href="?category=all" class="clear-filters">Clear Filters</a>
                <?php endif; ?>

                <!-- Categories -->
                <h3>Filters</h3>
                <ul class="filter-list">
                    <li><a href="?category=spartan" <?= $selectedCategory === 'spartan' ? 'class="active"' : '' ?>>Spartan Market</a></li>
                    <li><a href="?category=bakery" <?= $selectedCategory === 'all' ? 'class="active"' : '' ?>>Bakery Market</a></li>
                    <li><a href="?category=newleaf" <?= $selectedCategory === 'newleaf' ? 'class="active"' : '' ?>>New Leaf Apiary</a></li>
                    <li><a href="?category=righttwice" <?= $selectedCategory === 'righttwice' ? 'class="active"' : '' ?>>Right Twice Market</a></li>
                </ul>
            </aside>

            <!-- Product Grid -->
            <div id="productbuttoncontainer">
                <?php if (!empty($top5)) : ?>
                    <?php foreach ($top5 as $product) : ?>
                        <a
                            href="product_page.php?origin=<?= urlencode($product['origin']) ?>&id=<?= urlencode($product['id']) ?>">
                            <div class="imagebuttonholder">
                                <div class="imagebutton_i">
                                    <img
                                        src="<?= htmlspecialchars($product['img']) ?>"
                                        alt="<?= htmlspecialchars($product['name']) ?>">
                                </div>

                                <div class="imagebutton_b">
                                    <h3 class="product-name"><?= htmlspecialchars($product['name']) ?></h3>
                                    <p class="price">$<?= number_format($product['price'], 2) ?></p>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>No products found.</p>
                <?php endif; ?>
            </div>

        </div>
    </main>

    <?php
    foreach ($most_visited as $unit => $unit_array) {
        echo "<script>console.log('" . $unit . " " . $unit_array['visits'] . "');</script>";
    }
    ?>
</body>

</html>