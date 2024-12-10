<?php
session_start();
require_once 'db.php'; // Include the database connection file

// Check if the user is logged in
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    // Redirect to login page if the user is not logged in
    header('Location: login.php');
    exit;
}

// Fetch cart items for the logged-in user
$stmt = $pdo->prepare("
    SELECT p.product_id, p.product_name, p.product_price, p.product_image, c.quantity
    FROM cart_items AS c
    JOIN products AS p ON c.product_id = p.product_id
    WHERE c.user_id = :user_id
");
$stmt->execute(['user_id' => $user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate the total price of items in the cart
$total_price = array_sum(array_map(fn($item) => $item['product_price'] * $item['quantity'], $cart_items));

// Handle the checkout process when the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shipping_address = trim($_POST['shipping_address']); // Get shipping address from the form

    if (empty($shipping_address)) {
        echo "Shipping address cannot be empty.";
        exit;
    }

    try {
        // Start a transaction to ensure data consistency
        $pdo->beginTransaction();

        // Insert the order details into the `orders` table
        $stmt = $pdo->prepare("
            INSERT INTO orders (user_id, total_price, shipping_address, status, created_at)
            VALUES (:user_id, :total_price, :shipping_address, 'Pending', NOW())
        ");
        $stmt->execute([
            'user_id' => $user_id,
            'total_price' => $total_price,
            'shipping_address' => $shipping_address,
        ]);

        // Retrieve the ID of the newly created order
        $order_id = $pdo->lastInsertId();

        // Insert each cart item into the `order_items` table
        foreach ($cart_items as $item) {
            $stmt = $pdo->prepare("
                INSERT INTO order_items (order_id, product_id, quantity, price)
                VALUES (:order_id, :product_id, :quantity, :price)
            ");
            $stmt->execute([
                'order_id' => $order_id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['product_price'],
            ]);
        }

        // Clear the cart for the user after order placement
        $stmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $user_id]);

        // Commit the transaction
        $pdo->commit();

        // Redirect to the order confirmation page
        header('Location: order_confirmation.php?order_id=' . $order_id);
        exit;

    } catch (PDOException $e) {
        // Rollback the transaction in case of an error
        $pdo->rollBack();
        echo "Error processing your order: " . htmlspecialchars($e->getMessage());
        exit;
    }  
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="../main.css">
    <style>
        .cart-page {
            padding: 20px;
            font-family: Arial, sans-serif;
        }

        h2 {
            color: #333; /* Darker color for the header */
        }

        .cart-items {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid #0e0101;
            padding: 10px;
            margin-bottom: 10px;
        }

        .product-image {
            width: 100px;
            height: auto;
        }

        .product-details {
            flex: 1;
            margin-left: 20px;
        }

        .item-name {
            font-size: 1.2em;
            color: #2c2c2c; /* Darker color for the product name */
        }

        .item-price, .item-total {
            font-weight: bold;
            color: #444; /* Darker color for the price */
        }

        .quantity-control {
            display: flex;
            align-items: center;
        }

        .quantity-btn {
            padding: 5px 10px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            cursor: pointer;
        }

        .cart-total {
            margin-top: 30px;
            text-align: right;
        }

        .cart-total .total {
            font-size: 1.5em;
            font-weight: bold;
        }

        .cart-total #total-price {
            font-size: 2em;
            color: #333; /* Darker color for total price */
        }

        .checkout-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }

        .checkout-btn:hover {
            background-color: #45a049;
        }

        .shipping-message {
            display: none;
            font-size: 1.2em;
            color: #de1c87;
            background-color: #e8f5e9;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            margin-top: 20px;
        }

        .cart-icon {
            font-size: 2em;
            margin-right: 10px;
        }
    </style>
</head>
<body class="body-backg">
    <div id="header"></div>
    
    <div class="checkout-page">
        <h2><span class="cart-icon">🛒</span> Checkout</h2>

        <!-- Display cart items -->
        <section class="cart-items">
            <?php foreach ($cart_items as $item): ?>
                <div class="cart-item">
                    <img src="<?= htmlspecialchars($item['product_image']) ?>" alt="Product" class="product-image">
                    <div class="product-details">
                        <p class="item-name"><?= htmlspecialchars($item['product_name']) ?></p>
                        <p class="item-price">$<?= number_format($item['product_price'], 2) ?></p>
                        <p class="item-quantity">Quantity: <?= htmlspecialchars($item['quantity']) ?></p>
                        <p class="item-total">Total: $<?= number_format($item['product_price'] * $item['quantity'], 2) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>

        <!-- Display total price -->
        <section class="cart-total">
            <h3>Total Price: $<?= number_format($total_price, 2) ?></h3>
        </section>

        <!-- Checkout form -->
        <form action="checkout.php" method="POST">
            <h3>Shipping Information</h3>
            <label for="shipping_address">Shipping Address:</label>
            <textarea name="shipping_address" id="shipping_address" rows="4" required></textarea>
            <button type="submit" class="checkout-btn">Place Order</button>
        </form>
    </div>

    <div id="footer"></div>
</body>
</html>