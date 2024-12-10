<!--hasan-->
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../hasan/login.php');
    exit;
}

require_once __DIR__ . '/../config/database.php';

try {
    $conn = getConnection();
    error_log("Database connection successful"); // Debug log
    
    //get user details from session
    $userId = $_SESSION['user_id'];
    error_log("User ID: " . $userId); // Debug log

    $stmt = $conn->prepare("SELECT * FROM USERS WHERE user_id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if (!$user) {
        error_log("No user found for ID: " . $userId); //debugging while developing
        session_destroy();
        header('Location: ../hasan/login.php');
        exit;
    }

    error_log("User data retrieved successfully"); //debugging while developing

    //query orders (check if the table exists first)
    if($conn->query("SHOW TABLES LIKE 'ORDERS'")->rowCount() > 0) {
        $stmt = $conn->prepare("SELECT * FROM ORDERS WHERE order_user_id = ? ORDER BY order_date DESC");
        $stmt->execute([$userId]);
        $orders = $stmt->fetchAll();
    } else {
        error_log("ORDERS table does not exist"); //debugging while developing
        $orders = [];
    }

    //query wishlist (check if the table exists first)
    if($conn->query("SHOW TABLES LIKE 'WISHLIST'")->rowCount() > 0 && 
       $conn->query("SHOW TABLES LIKE 'ITEM_IN_WISHLIST'")->rowCount() > 0 && 
       $conn->query("SHOW TABLES LIKE 'ITEM'")->rowCount() > 0) {
        
        $stmt = $conn->prepare("
            SELECT i.item_id, i.item_name, i.item_description, i.item_price, i.item_image
            FROM ITEM i
            JOIN ITEM_IN_WISHLIST iw ON i.item_id = iw.item_id
            JOIN WISHLIST w ON iw.wishlist_id = w.wishlist_id
            WHERE w.wishlist_user_id = ?
        ");
        $stmt->execute([$userId]);
        $wishlistItems = $stmt->fetchAll();
    } else {
        error_log("One or more required tables do not exist"); //debugging while developing
        $wishlistItems = [];
    }

} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    error_log('Stack trace: ' . $e->getTraceAsString());
    die('Database error: ' . $e->getMessage()); //show error
}
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page - Gym Equipment Store</title>
    <link rel="stylesheet" href="../main.css">
    <style>
        .container {
            padding: 2rem;
        }

        .profile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1rem;
            background-color: var(--black);
            border-radius: 8px;
        }

        .profile-header h2 {
            color: var(--primary-dark);
            margin: 0;
        }

        .logout-btn {
            padding: 10px 20px;
            background-color: var(--primary-dark);
            color: var(--black);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            font-weight: bold;
        }

        .logout-btn:hover {
            background-color: var(--secondary-dark);
        }

        .profile-section {
            display: grid;
            gap: 2rem;
        }

        .account-info, .orders, .wishlist-section {
            background-color: var(--black);
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            color: var(--primary-dark);
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }

        .account-table, .orders-table {
            width: 100%;
            border-collapse: collapse;
        }

        .account-table th, .account-table td,
        .orders-table th, .orders-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid var(--secondary-dark);
        }

        .account-table th, .orders-table th {
            color: var(--primary-dark);
            font-weight: bold;
        }

        .account-table td, .orders-table td {
            color: var(--text);
        }

        .wishlist-items {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .wishlist-item {
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 1rem;
            transition: transform 0.2s;
        }

        .wishlist-item:hover {
            transform: translateY(-5px);
        }

        .wishlist-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .item-description h3 {
            color: var(--primary-dark);
            margin: 0.5rem 0;
            font-size: 1.2rem;
        }

        .item-price {
            color: var(--secondary-dark);
            font-weight: bold;
            font-size: 1.1rem;
            margin: 0.5rem 0;
        }

        .item-info {
            color: var(--text);
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .remove-button {
            width: 100%;
            padding: 8px;
            background-color: var(--primary-dark);
            color: var(--black);
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-weight: bold;
        }

        .remove-button:hover {
            background-color: var(--secondary-dark);
        }

        .empty-message {
            color: var(--text);
            text-align: center;
            padding: 1rem;
        }

        @media (max-width: 768px) {
            .wishlist-items {
                grid-template-columns: 1fr;
            }
            
            .profile-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
        }
    </style>
</head>
<body class="body-backg">
    <div id="header"></div>
    
    <div class="container">
        <section class="profile-section">
            <div class="profile-header">
                <h2>Welcome, <?= htmlspecialchars($user['user_username']) ?></h2>
                <a href="../handlers/logout.php" class="logout-btn">Logout</a>
            </div>

            <div class="account-info">
                <h3 class="section-title">Account Information</h3>
                <table class="account-table">
                    <tr>
                        <th>Email</th>
                        <td><?= htmlspecialchars($user['user_email']) ?></td>
                    </tr>
                    <tr>
                        <th>First Name</th>
                        <td><?= htmlspecialchars($user['user_first_name'] ?? 'Not specified') ?></td>
                    </tr>
                    <tr>
                        <th>Last Name</th>
                        <td><?= htmlspecialchars($user['user_last_name'] ?? 'Not specified') ?></td>
                    </tr>
                    <tr>
                        <th>Country</th>
                        <td><?= htmlspecialchars($user['user_country'] ?? 'Not specified') ?></td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td><?= htmlspecialchars($user['user_phone_number'] ?? 'Not specified') ?></td>
                    </tr>
                </table>
            </div>

            <div class="orders">
                <h3 class="section-title">Orders</h3>
                <?php if (empty($orders)): ?>
                    <p class="empty-message">No orders yet.</p>
                <?php else: ?>
                <table class="orders-table">
                    <tr>
                        <th>Order ID</th>
                        <th>Status</th>
                        <th>Total Price</th>
                        <th>Date</th>
                    </tr>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>#<?= htmlspecialchars($order['order_id']) ?></td>
                        <td><?= htmlspecialchars($order['order_status'] ?? 'Processing') ?></td>
                        <td>$<?= number_format($order['total_price'], 2) ?></td>
                        <td><?= htmlspecialchars($order['order_date']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <?php endif; ?>
            </div>

            <div class="wishlist-section">
                <h3 class="section-title">My Wishlist</h3>
                <?php if (empty($wishlistItems)): ?>
                    <p class="empty-message">Your wishlist is empty.</p>
                <?php else: ?>
                <div class="wishlist-items">
                    <?php foreach ($wishlistItems as $item): ?>
                    <div class="wishlist-item" data-item-id="<?= htmlspecialchars($item['item_id']) ?>">
                        <img src="<?= htmlspecialchars($item['item_image']) ?>" 
                             alt="<?= htmlspecialchars($item['item_name']) ?>">
                        <div class="item-description">
                            <h3><?= htmlspecialchars($item['item_name']) ?></h3>
                            <p class="item-price">$<?= number_format($item['item_price'], 2) ?></p>
                            <p class="item-info"><?= htmlspecialchars($item['item_description']) ?></p>
                            <button class="remove-button" onclick="removeFromWishlist(<?= $item['item_id'] ?>)">
                                Remove from Wishlist
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <div id="footer"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function removeFromWishlist(itemId) {
            $.ajax({
                url: '../handlers/remove_from_favorites.php',
                type: 'POST',
                data: { itemId: itemId },
                success: function(response) {
                    if (response.success) {
                        $(`.wishlist-item[data-item-id="${itemId}"]`).fadeOut(300, function() {
                            $(this).remove();
                            if ($('.wishlist-item').length === 0) {
                                $('.wishlist-items').html('<p class="empty-message">Your wishlist is empty.</p>');
                            }
                        });
                    } else {
                        alert('Failed to remove item from wishlist');
                    }
                },
                error: function() {
                    alert('An error occurred while removing the item');
                }
            });
        }
    </script>
    <script src="../js/main.js"></script>
</body>
</html>