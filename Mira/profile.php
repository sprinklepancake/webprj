<?php
include('db.php');
session_start(); // assuming you have user login handling

// Get user details from the session
$userId = $_SESSION['user_id']; // Assuming user_id is stored in the session

// Query user data
$userQuery = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
$userQuery->execute(['user_id' => $userId]);
$user = $userQuery->fetch(PDO::FETCH_ASSOC);

// Query orders
$ordersQuery = $pdo->prepare("SELECT * FROM orders WHERE order_user_id = :user_id");
$ordersQuery->execute(['user_id' => $userId]);
$orders = $ordersQuery->fetchAll(PDO::FETCH_ASSOC);

// Query wishlist items
$wishlistQuery = $pdo->prepare("
    SELECT w.wishlist_id, i.item_name, i.item_description, i.item_price, i.item_image
    FROM wishlist AS w
    JOIN item_in_wishlist AS iw ON w.wishlist_id = iw.wishlist_id
    JOIN item AS i ON iw.item_id = i.item_id
    WHERE w.wishlist_user_id = :user_id
");
$wishlistQuery->execute(['user_id' => $userId]);
$wishlistItems = $wishlistQuery->fetchAll(PDO::FETCH_ASSOC);
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page - Gym Equipment Store</title>
    <link rel="stylesheet" href="../main.css">
    <style>
                        .transaction-button {
    display: inline-block;
    padding: 10px 20px;
    background-color: var(--primary-dark); /* Change this to your preferred color */
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.transaction-button:hover {
    background-color: var(--secondary-dark); /* Hover color */
}

        /* Profile Photo Styling */
        .profile-photo img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 2px solid #333; /* Change to match your theme */
        }

/* Account Table Styling */
        .account-table, .orders-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        .account-table th, .account-table td, 
        .orders-table th, .orders-table td {
            border: 3px solid #333; /* Border color */
            padding: 10px;
            text-align: left;
        }

        .account-table th, .orders-table th {
            background-color: #f0f0f000; /* Header background color */
            font-weight: bold;
        }
        .wishlist-section {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: space-between;
            margin-top: 2rem;
        }

        .wishlist-item {
            text-align: center;
            width: 120px;
        }

        .wishlist-item img {
            max-width: 60px !important;
            max-height: 60px !important;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: width 0.2s ease, height 0.2s ease;
        }

        .wishlist-item img:hover {
            width: 70px;
            height: 70px;
        }

        .item-description {
            color: var(--text);
            margin-bottom: 10px;
        }

        .item-price {
            color: #0c0801; /* Orange color */
            font-weight: bold;
        }
            
        

        .item-info {
            color: var(--text-light);
        }

        .item-info-news {
            color: var(--price);
            text-decoration: underline;
        }
        .remove-button {
            padding: 5px 10px;
            background-color: #d2150e;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .remove-button:hover {
            background-color: #d2150e;
        }
    </style>
</head>
<body>
    <div id="header"></div>
    <div class="container">
        <!-- Profile Section -->
        <section class="profile-section">
            <div class="profile-info">
                <div class="profile-photo">
                    <img src="<?php echo htmlspecialchars($user['user_image']); ?>" alt="Profile Photo">
                </div>
                <h2 id="username">Username: <?php echo htmlspecialchars($user['user_username']); ?></h2>
            </div>

            <!-- Account Info Section -->
            <section class="account-info">
                <h3>Account Information</h3>
                <table class="account-table">
                    <tr>
                        <th>Email</th>
                        <td><?php echo htmlspecialchars($user['user_email']); ?></td>
                    </tr>
                    <tr>
                        <th>Country</th>
                        <td><?php echo htmlspecialchars($user['user_country']); ?></td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td><?php echo htmlspecialchars($user['user_phone_number']); ?></td>
                    </tr>
                </table>
            </section>

            <!-- Orders Section -->
            <section class="orders">
                <h3>Orders</h3>
                <table class="orders-table">
                    <tr>
                        <th>Order ID</th>
                        <th>Status</th>
                        <th>Total Price</th>
                        <th>Date</th>
                    </tr>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>#<?php echo htmlspecialchars($order['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($order['order_status']); ?></td>
                        <td>$<?php echo number_format($order['total_price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </section>

            <!-- Wishlist Section -->
            <div class="wishlist-section">
                <h3>My Wishlist</h3>
                <ul>
                    <?php foreach ($wishlistItems as $item): ?>
                    <div class="wishlist-item">
                        <figure>
                            <img src="<?php echo htmlspecialchars($item['item_image']); ?>" alt="<?php echo htmlspecialchars($item['item_name']); ?>">
                        </figure>
                        <div class="item-description">
                            <h3><?php echo htmlspecialchars($item['item_name']); ?></h3>
                            <p class="item-price">$<?php echo number_format($item['item_price'], 2); ?></p>
                            <p><?php echo htmlspecialchars($item['item_description']); ?></p>
                            <button class="remove-button" onclick="removeItem(this)">Remove from Wishlist</button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>
    </div>
    <div id="footer"></div>
    <script>
        function removeItem(button) {
            const item = button.closest('.wishlist-item');
            item.remove();
        }
    </script>
    <script src="../js/main.js"></script>
</body>
</html>
