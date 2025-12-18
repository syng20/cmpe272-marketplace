<?php
include("navbar.php");


$cart_file = __DIR__ . '/data/carts.json';
$carts = file_exists($cart_file)
    ? json_decode(file_get_contents($cart_file), true)
    : [];


$user = $_SESSION['user']['username'] ?? null;


if ($user) {
    $cart_items = $carts[$user] ?? [];
    $can_checkout = true;
} else {
    $cart_items = $_SESSION['cart'] ?? [];
    $can_checkout = false;
}


$total = 0;
foreach ($cart_items as $item) {
    $total += (float)$item['product']['price'] * (int)$item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>

    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/cart.css">
</head>

<body>

    <div class="cart-container">

        <?php if (empty($cart_items)): ?>
            <div class="cart-items">
                <p class="empty-cart">Your cart is empty.</p>
            </div>


        <?php else: ?>

            <div class="cart-items">
                <?php foreach ($cart_items as $item_id => $item): ?>
                    <div class="cart-item">
                        <div class="cart-item-img">
                            <img src="<?= htmlspecialchars($item['product']['img']) ?>"
                                alt="<?= htmlspecialchars($item['product']['name']) ?>">
                        </div>

                        <div class="cart-item-details">
                            <h3><?= htmlspecialchars($item['product']['name']) ?></h3>
                            <p>Price: $<?= number_format((float)$item['product']['price'], 2) ?></p>
                            <p>Quantity: <?= (int)$item['quantity'] ?></p>
                            <p>
                                Subtotal:
                                $<?= number_format(
                                        (float)$item['product']['price'] * (int)$item['quantity'],
                                        2
                                    ) ?>
                            </p>

                            <form method="POST"
                                action="/handlers/cart_removal_handler.php"
                                class="remove-form">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($item_id) ?>">
                                <button type="submit" class="remove-btn">Remove</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>

        <!-- Cart Summary -->
        <div class="cart-summary">
            <h2>Total: $<?= number_format($total, 2) ?></h2>

            <?php if (!$user): ?>
                <p class="guest-warning">
                    Guests cannot checkout. Please log in.
                </p>

            <?php elseif (empty($cart_items)): ?>
                <p class="guest-warning">
                    Add items to your cart to checkout.
                </p>

            <?php else: ?>
                <div id="paypal-button-container"></div>
            <?php endif; ?>
        </div>

    </div>

    <!-- PayPal SDK -->
    <script src="https://www.paypal.com/sdk/js?client-id=test&currency=USD"></script>

    <?php if ($user && !empty($cart_items)): ?>
        <script>
            paypal.Buttons({
                style: {
                    layout: 'horizontal',
                    shape: 'rect',
                    color: 'blue'
                },

                createOrder() {
                    return fetch('/handlers/paypal_create_order.php', {
                            method: 'POST'
                        })
                        .then(res => res.json())
                        .then(orderData => orderData.id);
                },

                onApprove(data) {
                    return fetch('/handlers/paypal_capture_order.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                orderID: data.orderID
                            })
                        })
                        .then(res => res.json())
                        .then(orderData => {
                            alert(
                                'Payment successful! Transaction ID: ' +
                                orderData.purchase_units[0].payments.captures[0].id
                            );
                        });
                }
            }).render('#paypal-button-container');
        </script>
    <?php endif; ?>

</body>

</html>