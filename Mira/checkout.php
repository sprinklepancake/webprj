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

        <!-- Location Section -->
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

            <!-- Payment Method Section -->
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

            <!-- Submit Button -->
            <section class="checkout-section">
                <button class="checkout-btn" type="submit">Proceed with Shipping</button>
            </section>
        </form>
    </div>

    <div id="footer"></div>
</body>
</html>
