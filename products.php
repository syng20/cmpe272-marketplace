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

// initial set-up for visit-tracking cookies
if (!isset($_COOKIE["first_visit"])) {
  setcookie("first_visit", 1, time() + 365); 
  // most visited 
  // increase, sort by value 
  $mostvisits = array();

  $temp_name = ""; 
  $temp = array('origin' => 0, 'id' => 0, 'price' => 0, 'img' => 0, 'description' => 0, 'visits' => 0);
  foreach ($allProducts as $product) {
    $temp['origin'] = $product['origin']; 
    $temp['id'] = $product['id']; 
    $temp['price'] = $product['price']; 
    $temp['img'] = $product['img']; 
    $temp['description'] = $product['description']; 
    $mostvisits[$product['name']] = $temp; 
  }

  $mostvisitsJson = json_encode($mostvisits); 
  setcookie('mostvisits_array', $mostvisitsJson, time() + 365); 
  // most recently visited 
  // set current page to 1, increase all non-zero values 
  // if number of non-zero values > 5, set largest value to 0
  $recently = array(); 
  $recentlyJson = json_encode($recently); 
  setcookie('recently_array', $recentlyJson, time() + 365); 
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
                    <li><a href="?category=righttwice<?= $sortOrder ? "&sort=$sortOrder" : '' ?>" <?= $selectedCategory === 'righttwice' ? 'class="active"' : '' ?>>Right Twice Market</a></li>
                </ul>




            </aside>

            <!-- Product Grid -->
            <div id="productbuttoncontainer">
                <?php if (!empty($displayProducts)) : ?>
                    <?php foreach ($displayProducts as $product) : ?>
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
</body>

</html>