<?php
//hasan
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../hasan/login.php");
    exit();
}

try {
    $conn = getConnection();
    $userId = $_SESSION['user_id'];

    //get all items
    $stmt = $conn->prepare("SELECT * FROM ITEM ORDER BY item_date_added DESC");
    $stmt->execute();
    $items = $stmt->fetchAll();

    //get user's wishlist items
    $stmt = $conn->prepare("
        SELECT item_id 
        FROM ITEM_IN_WISHLIST iw 
        JOIN WISHLIST w ON iw.wishlist_id = w.wishlist_id 
        WHERE w.wishlist_user_id = ?
    ");
    $stmt->execute([$userId]);
    $wishlistItems = $stmt->fetchAll(PDO::FETCH_COLUMN);

    //get user's cart items
    $stmt = $conn->prepare("
        SELECT item_id 
        FROM ITEM_IN_CART ic 
        JOIN CART c ON ic.cart_id = c.cart_id 
        WHERE c.cart_user_id = ?
    ");
    $stmt->execute([$userId]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_COLUMN);

} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    die('An unexpected error occurred.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Page - Fitness Equipment Store</title>
    <link rel="stylesheet" href="../main.css">
    <style>
        .status-message {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: 5px;
            color: white;
            z-index: 1000;
            display: none;
        }

        .status-message.success {
            background-color: var(--primary-dark);
        }

        .status-message.error {
            background-color: #dc3545;
        }

        body {
            background-color: var(--black);
            color: var(--text);
        }

        .landing-shop-container {
            background-color: var(--black);
            color: var(--text);
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 2rem;
            padding: 2rem;
        }

        .discount-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
        }

        .discount-container img {
            width: 48%;
            height: auto;
            border-radius: 8px;
        }

        .options-pane-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .search-bar-container {
            flex-grow: 1;
            margin-right: 1rem;
            flex: 1 1 200px;
            min-width: 200px;
        }

        #searchbar {
            width: 100%;
            padding: 0.5rem 1rem;
            border: 1px solid var(--greyishblue);
            border-radius: 20px;
            background-color: var(--greyishblue);
            color: var(--text);
        }

        .icon-container {
            display: flex;
            gap: 1rem;
            flex: 0 0 auto;
        }

        .icon-container img {
            width: 24px;
            height: 24px;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .icon-container img:hover {
            transform: scale(1.1);
        }

        .shop-items {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 2rem;
        }

        .shop-item {
            background-color: var(--greyishblue);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }

        .shop-item:hover {
            transform: translateY(-5px);
        }

        .item-img img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .item-details {
            padding: 1rem;
        }

        .item-name {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: var(--text);
        }

        .item-price {
            color: var(--price);
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .item-description {
            font-size: 0.9rem;
            color: var(--text);
            margin-bottom: 1rem;
        }

        .item-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .item-actions img {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .more-items {
            text-align: center;
            margin-top: 2rem;
        }

        #viewMoreButton {
            background-color: var(--primary);
            color: var(--white);
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        #viewMoreButton:hover {
            background-color: var(--primary-dark);
        }
    </style>
</head>

<body class="body-backg">
    <div id="header"></div>
    
    <div id="statusMessage" class="status-message"></div>

    <main class="container">
        <div class="landing-shop-container">
            <div class="discount-container">
                <img src="uploads/discount1.1.png" alt="november discount">
                <img src="uploads/discount2.1.png" alt="order discount">
            </div>

            <div class="options-pane-container">
                <div class="search-bar-container">
                    <input type="text" id="searchbar" placeholder="Search product">
                </div>
                <div class="icon-container">
                    <img id="wishlistFilter" src="uploads/heartfull.png" alt="favorites icon" onclick="filterWishlist()">
                    <a href="../mira/cart.php">
                        <img src="uploads/whitecart.png" alt="cart icon">
                    </a>
                </div>
            </div>

            <div class="shop-items">
                <?php 
                $counter = 0;
                foreach ($items as $item):
                    $isInWishlist = in_array($item['item_id'], $wishlistItems);
                    $isInCart = in_array($item['item_id'], $cartItems);
                    $hiddenClass = $counter >= 6 ? 'hidden' : '';
                ?>
                <div class="shop-item <?= $hiddenClass ?>" data-id="<?= $item['item_id'] ?>" data-name="<?= htmlspecialchars($item['item_name']) ?>">
                    <div class="item-img">
                        <img src="<?= htmlspecialchars($item['item_image']) ?>" alt="<?= htmlspecialchars($item['item_name']) ?>">
                    </div>
                    <div class="item-details">
                        <h3 class="item-name"><?= htmlspecialchars($item['item_name']) ?></h3>
                        <p class="item-price">$<?= number_format($item['item_price'], 2) ?></p>
                        <p class="item-description"><?= htmlspecialchars($item['item_description']) ?></p>
                        <div class="item-actions">
                            <img src="uploads/<?= $isInWishlist ? 'heartfull.png' : 'heartgrey.png' ?>" 
                                 alt="Favorites Icon" 
                                 onclick="toggleWishlist(this, <?= $item['item_id'] ?>)">
                            <img src="uploads/cart.png" 
                                 alt="Cart Icon" 
                                 style="opacity: <?= $isInCart ? '0.6' : '1' ?>;"
                                 onclick="toggleCart(this, <?= $item['item_id'] ?>)">
                        </div>
                    </div>
                </div>
                <?php 
                    $counter++;
                endforeach; 
                ?>
            </div>

            <div class="more-items">
                <button onclick="viewMoreItems()" id="viewMoreButton">View More</button>
            </div>
        </div>
    </main>

    <div id="footer"></div>
    <script src="../js/main.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>

function showMessage(message, isSuccess = true) {
        const messageElement = document.getElementById('statusMessage');
        messageElement.textContent = message;
        messageElement.className = 'status-message ' + (isSuccess ? 'success' : 'error');
        messageElement.style.display = 'block';
        
        setTimeout(() => {
            messageElement.style.display = 'none';
        }, 3000);
    }

    function toggleWishlist(element, itemId) {
        const action = element.src.includes('heartgrey.png') ? 'add' : 'remove';
        
        fetch('../handlers/wishlist_action.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=${action}&item_id=${itemId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                element.src = `uploads/${action === 'add' ? 'heartfull.png' : 'heartgrey.png'}`;
                showMessage(data.message);
            } else {
                showMessage(data.message, false);
            }
        })
        .catch(error => {
            showMessage('Error updating wishlist', false);
        });
    }

    function toggleCart(element, itemId) {
    console.log('Attempting to add item:', itemId); // Debug log
    
    fetch('../handlers/cart_action.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=add&item_id=${itemId}`
    })
    .then(response => {
        console.log('Raw response:', response); // Debug log
        return response.json();
    })
    .then(data => {
        console.log('Parsed response:', data); // Debug log
        
        if (data.success) {
            element.style.opacity = '0.6';
            showMessage(data.message);
        } else {
            showMessage(data.message || 'Error adding to cart', false);
            console.error('Error details:', data); // Debug log
        }
    })
    .catch(error => {
        console.error('Fetch error:', error); // Debug log
        showMessage('Error updating cart: ' + error.message, false);
    });
}

    function filterWishlist() {
        const items = document.querySelectorAll('.shop-item');
        const wishlistFilter = document.getElementById('wishlistFilter');
        const isWishlistOnly = wishlistFilter.src.includes('heartgrey.png');
        
        items.forEach(item => {
            const heartIcon = item.querySelector('.item-fav-icon img');
            if (isWishlistOnly) {
                item.style.display = heartIcon.src.includes('uploads/heartfull.png') ? '' : 'none';
            } else {
                item.style.display = '';
            }
        });
        
        wishlistFilter.src = `${isWishlistOnly ? 'uploads/heartfull.png' : 'uploads/heartgrey.png'}`;
    }

    document.getElementById('searchbar').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        document.querySelectorAll('.shop-item').forEach(item => {
            const name = item.getAttribute('data-name').toLowerCase();
            item.style.display = name.includes(searchTerm) ? '' : 'none';
        });
    });

    function viewMoreItems() {
        const hiddenItems = document.querySelectorAll('.shop-item.hidden');
        hiddenItems.forEach(item => item.classList.remove('hidden'));
        
        if (hiddenItems.length === 0) {
            const button = document.getElementById('viewMoreButton');
            button.textContent = "No More Items";
            button.disabled = true;
        }
    }
        function viewMoreItems() {
            const hiddenItems = document.querySelectorAll('.shop-item.hidden');
            hiddenItems.forEach((item, index) => {
                if (index < 6) {
                    item.classList.remove('hidden');
                }
            });

            if (document.querySelectorAll('.shop-item.hidden').length === 0) {
                const button = document.getElementById('viewMoreButton');
                button.textContent = "No More Items";
                button.disabled = true;
            }
        }

        function initializeView() {
            const allItems = document.querySelectorAll('.shop-item');
            allItems.forEach((item, index) => {
                if (index >= 6) {
                    item.classList.add('hidden');
                }
            });
        }

        document.addEventListener('DOMContentLoaded', initializeView);
        //function to get URL parameters
function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    var results = regex.exec(location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
}

//when the page loads, check for search parameter
document.addEventListener('DOMContentLoaded', function() {
    const searchParam = getUrlParameter('search');
    if (searchParam) {
        //get the search bar and set its value
        const searchbar = document.getElementById('searchbar');
        searchbar.value = searchParam;
        
        //trigger the search
        const searchEvent = new Event('input', {
            bubbles: true,
            cancelable: true,
        });
        searchbar.dispatchEvent(searchEvent);
        
        //scroll to the search results
        searchbar.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});
    </script>
</body>
</html>

