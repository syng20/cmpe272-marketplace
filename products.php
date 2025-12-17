<?php
// Filter States
$selectedCategory = $_GET['category'] ?? 'all';
$sortOrder = $_GET['sort'] ?? '';
$searchTerm = $_GET['search'] ?? '';

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

// TEMP: replace with your other products //this is how i included my products
include 'curl/william_products.php';

// Merge after all product calls (please implement curl to your company website)
$allProducts = array_merge($williamProducts, $samProducts, $seanProducts, $products);

// Choose products to display
if ($selectedCategory === 'spartan') {
    $displayProducts = $williamProducts;
} 
else if ($selectedCategory == 'newleaf') {
    $displayProducts = $samProducts;
}else if($selectedCategory == 'bakery') {
    $displayProducts = $seanProducts;
}
else {
    $displayProducts = $allProducts;
}

// Sort products if requested
if ($sortOrder === 'lowhigh') {
    usort($displayProducts, fn($a, $b) => $a['price'] <=> $b['price']);
} elseif ($sortOrder === 'highlow') {
    usort($displayProducts, fn($a, $b) => $b['price'] <=> $a['price']);
}

// Filter products by search term using regex
if (!empty($searchTerm)) {
    $displayProducts = array_filter($displayProducts, function ($product) use ($searchTerm) {
        return preg_match("/" . preg_quote($searchTerm, '/') . "/i", $product['name']);
    });
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
        <h2 class="page-title">Marketplace Listings</h2>

        <div class="market-layout">
            <!-- Sidebar Filters -->
            <aside id="filter-sidebar">
                <!-- Search Form -->
                <h3>Search Products</h3>
                <form method="GET" action="">
                    <input type="hidden" name="category" value="<?= htmlspecialchars($selectedCategory) ?>">
                    <input type="hidden" name="sort" value="<?= htmlspecialchars($sortOrder) ?>">
                    <input type="text" name="search" value="<?= htmlspecialchars($searchTerm) ?>" placeholder="Product name">
                    <button type="submit">Search</button>
                </form>


                <!-- Clear Filters -->
                <?php if ($selectedCategory !== 'all' || $sortOrder || !empty($searchTerm)) : ?>
                    <a href="?category=all" class="clear-filters">Clear Filters</a>
                <?php endif; ?>

                <!-- Sort Buttons -->
                <h3>Sort By Price</h3>
                <ul class="filter-list">
                    <li><a href="?sort=lowhigh<?= $selectedCategory !== 'all' ? "&category=$selectedCategory" : '' ?>" <?= $sortOrder === 'lowhigh' ? 'class="active"' : '' ?>>Low → High</a></li>
                    <li><a href="?sort=highlow<?= $selectedCategory !== 'all' ? "&category=$selectedCategory" : '' ?>" <?= $sortOrder === 'highlow' ? 'class="active"' : '' ?>>High → Low</a></li>
                </ul>

                <!-- Categories -->
                <h3>Filters</h3>
                <ul class="filter-list">
                    <li><a href="?category=spartan<?= $sortOrder ? "&sort=$sortOrder" : '' ?>" <?= $selectedCategory === 'spartan' ? 'class="active"' : '' ?>>Spartan Market</a></li>
                    <li><a href="?category=bakery<?= $sortOrder ? "&sort=$sortOrder" : '' ?>" <?= $selectedCategory === 'all' ? 'class="active"' : '' ?>>Bakery Market</a></li>
                    <li><a href="?category=newleaf<?= $sortOrder ? "&sort=$sortOrder" : '' ?>" <?= $selectedCategory === 'newleaf' ? 'class="active"' : '' ?>>New Leaf Apiary</a></li>
                    <li><a href="?category=cat3<?= $sortOrder ? "&sort=$sortOrder" : '' ?>" <?= $selectedCategory === 'cat3' ? 'class="active"' : '' ?>>Tommy Market</a></li>
                </ul>




            </aside>

            <!-- Product Grid -->
            <div id="productbuttoncontainer">
                <?php if (!empty($displayProducts)) : ?>
                    <?php foreach ($displayProducts as $product) : ?>
                        <div class="imagebuttonholder">
                            <div class="imagebutton_i">
                                <img src="<?= $product['img'] ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                            </div>
                            <div class="imagebutton_b">
                                <h3 class="product-name"><?= htmlspecialchars($product['name']) ?></h3>
                                <p class="price">$<?= number_format($product['price'], 2) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No products found.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>

</html>