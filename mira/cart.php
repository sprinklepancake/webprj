<!--hasan-->
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../hasan/login.php');
    exit;
}

try {
    $conn = getConnection();
    $userId = $_SESSION['user_id'];

    //check if user has a cart, if not create one
    $stmt = $conn->prepare("SELECT cart_id FROM CART WHERE cart_user_id = ?");
    $stmt->execute([$userId]);
    $cart = $stmt->fetch();

    if (!$cart) {
        //create a new cart for the user
        $stmt = $conn->prepare("INSERT INTO CART (cart_user_id, cart_quantity) VALUES (?, 0)");
        $stmt->execute([$userId]);
        $cartId = $conn->lastInsertId();
    } else {
        $cartId = $cart['cart_id'];
    }

    //fetch cart items
    $stmt = $conn->prepare("
        SELECT i.item_id, i.item_name, i.item_price, i.item_image 
        FROM ITEM i 
        JOIN ITEM_IN_CART ic ON i.item_id = ic.item_id 
        WHERE ic.cart_id = ?
    ");
    $stmt->execute([$cartId]);
    $cartItems = $stmt->fetchAll();

    //calculate total price
    $totalPrice = array_sum(array_column($cartItems, 'item_price'));

} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    die('Database error: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Fitness Equipment Store</title>
    <link rel="stylesheet" href="../main.css">
    <style>
        .cart-page {
            padding: 2rem;
        }

        .cart-header {
            background-color: var(--black);
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }

        .cart-header h2 {
            color: var(--primary-dark);
            margin: 0;
        }

        .cart-items {
            display: grid;
            gap: 1.5rem;
        }

        .cart-item {
            background-color: var(--black);
            border-radius: 8px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .cart-item img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
        }

        .item-details {
            flex: 1;
        }

        .item-name {
            color: var(--primary-dark);
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }

        .item-price {
            color: var(--secondary-dark);
            font-weight: bold;
        }

        .remove-btn {
            padding: 0.5rem 1rem;
            background-color: var(--primary-dark);
            color: var(--black);
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 1rem;
        }

        .remove-btn:hover {
            background-color: var(--secondary-dark);
        }

        .cart-total {
            background-color: var(--black);
            padding: 1.5rem;
            border-radius: 8px;
            margin-top: 2rem;
            text-align: right;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-total h3 {
            color: var(--primary-dark);
            margin: 0;
        }

        .empty-cart {
            text-align: center;
            padding: 2rem;
            background-color: var(--black);
            border-radius: 8px;
            color: var(--text);
        }

        .checkout-btn {
            padding: 1rem 2rem;
            background-color: var(--primary-dark);
            color: var(--black);
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
        }

        .checkout-btn:hover {
            background-color: var(--secondary-dark);
        }
    </style>
</head>
<body class="body-backg">
    <div id="header"></div>
    
    <div class="cart-page">
        <div class="cart-header">
            <h2>Shopping Cart</h2>
        </div>

        <?php if (empty($cartItems)): ?>
            <div class="empty-cart">
                <h3>Your cart is empty</h3>
                <p>Browse our products and add some items to your cart!</p>
            </div>
        <?php else: ?>
            <section class="cart-items">
                <?php foreach ($cartItems as $item): ?>
                    <div class="cart-item">
                        <img src="../petra/<?= htmlspecialchars($item['item_image']) ?>" 
                            alt="<?= htmlspecialchars($item['item_name']) ?>">
                        <div class="item-details">
                            <h3 class="item-name"><?= htmlspecialchars($item['item_name']) ?></h3>
                            <p class="item-price">$<?= number_format($item['item_price'], 2) ?></p>
                            <button class="remove-btn" onclick="removeFromCart(<?= $item['item_id'] ?>)">
                                Remove from Cart
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </section>

            <div class="cart-total">
                <h3>Total: $<?= number_format($totalPrice, 2) ?></h3>
                <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
            </div>
        <?php endif; ?>
    </div>

    <div id="footer"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function removeFromCart(itemId) {
            $.ajax({
                url: '../handlers/remove_from_cart.php',
                type: 'POST',
                data: { itemId: itemId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message || 'Failed to remove item from cart');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', xhr.responseText);
                    alert('An error occurred: ' + error);
                }
            });
        }
    </script>
    <script src="../js/main.js"></script>
</body>
</html>