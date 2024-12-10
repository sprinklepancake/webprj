<?php
session_start();
require_once 'db.php'; 


$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    
    header('Location: ../hasan/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $country = $_POST['country'];
    $region = $_POST['region'];
    $street = $_POST['street'];
    $building = $_POST['building'];
    $payment_method = $_POST['payment_method'];

    
    if (!$country || !$region || !$street || !$building || !$payment_method) {
        echo "Please fill in all the required fields.";
        exit;
    }

    
    $stmt = $pdo->prepare("SELECT p.id, p.price, c.quantity
                           FROM cart_items c
                           JOIN products p ON c.product_id = p.id
                           WHERE c.user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $cart_items = $stmt->fetchAll();

    $total_price = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart_items));

   
    try {
        
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price, shipping_address, status, created_at)
                               VALUES (:user_id, :total_price, :shipping_address, 'Pending', NOW())");
        $shipping_address = $country . ', ' . $region . ', ' . $street . ' ' . $building;
        $stmt->execute([
            'user_id' => $user_id,
            'total_price' => $total_price,
            'shipping_address' => $shipping_address,
        ]);

        
        $order_id = $pdo->lastInsertId();

        
        foreach ($cart_items as $item) {
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price)
                                   VALUES (:order_id, :product_id, :quantity, :price)");
            $stmt->execute([
                'order_id' => $order_id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        
        $stmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $user_id]);

        
        header('Location: order_confirmation.php?order_id=' . $order_id);
        exit;

    } catch (PDOException $e) {
        
        echo "Error: " . $e->getMessage();
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
        .div-body {
            font-family: Arial, sans-serif;
            padding: 20px;
            color: #333;
        }

        h2, h3 {
            color: #2c2c2c;
        }

        .checkout-section {
            margin-top: 20px;
        }

        .checkout-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            cursor: pointer;
            border: none;
        }

        .checkout-btn:hover {
            background-color: #45a049;
        }

        .message {
            display: none;
            font-size: 1.2em;
            color: #333;
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

        .location-input {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            color: #333;
        }

        .location-section {
            display: flex;
            flex-wrap: wrap;
        }

        .location-section div {
            flex: 1;
            margin: 5px;
        }

        label {
            color: #2c2c2c;
            font-weight: bold;
        }
    </style>
</head>

<body class="body-backg">
    <div id="header"></div>
    <div class="div-body">

    <h2>Checkout</h2>

    
    <form action="checkout.php" method="POST">
        <section class="checkout-section">
            <h3>Your Location</h3>
            <div class="location-section">
                <div>
                    <label for="country">Country:</label>
                    <input type="text" id="country" name="country" class="location-input" placeholder="Enter your country" required>
                </div>
                <div>
                    <label for="region">Region/State:</label>
                    <input type="text" id="region" name="region" class="location-input" placeholder="Enter your region/state" required>
                </div>
            </div>
            <div class="location-section">
                <div>
                    <label for="street">Street Address:</label>
                    <input type="text" id="street" name="street" class="location-input" placeholder="Enter your street address" required>
                </div>
                <div>
                    <label for="building">Building/Apartment:</label>
                    <input type="text" id="building" name="building" class="location-input" placeholder="Building or apartment number" required>
                </div>
            </div>
        </section>

        
        <section class="checkout-section">
            <h3>Payment Method</h3>
            <label for="payment-method">Choose a payment method:</label>
            <select id="payment-method" name="payment_method" class="location-input" required>
                <option value="credit-card">Credit Card</option>
                <option value="paypal">PayPal</option>
                <option value="bank-transfer">Bank Transfer</option>
                <option value="payment-on-delivery">Payment on Delivery</option> 
            </select>
        </section>

      
        <section class="checkout-section">
            <button class="checkout-btn" type="submit">Proceed with Shipping</button>
        </section>
    </form>

    
    <section id="shipping-message" class="message">
        <p><span class="cart-icon">🛒</span> Shipping on the way!</p>
    </section>
    </div>
    
    <div id="footer"></div>
</body>
</html>