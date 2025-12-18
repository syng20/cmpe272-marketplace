<?php
if (isset($_COOKIE['mostvisits_array'])) {
    $v = stripslashes($_COOKIE['mostvisits_array']);
    $most_visited = json_decode($v, true);
} else {
    $most_visited = array();
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
        <?php
        foreach ($most_visited as $unit => $unit_array) {
            // if ($unit_array['visits'] > 0) {
            //     $origin = substr($unit, 0, strpos($unit, preg_replace('/[0-9]/', '', strrev($unit))));
            //     $id = substr($unit, strlen($origin));
            //     include 'handlers/product_page_handler.php';
            // }
            echo "<p>" . $unit . ": " . $unit_array['visits'] . "</p>";
        }
        ?>
    </main>
</body>

</html>