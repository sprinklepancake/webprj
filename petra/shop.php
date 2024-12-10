<!--petra-->
<?php
session_start();
include 'includes/db_connect.php';
 if (!isset($_SESSION['user_id'])) {
    header("Location: ../hasan/login.php");
    exit();
} 
 $userId = $_SESSION['user_id'];
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Page - Fitness Equipment Store</title>
    <link rel="stylesheet" href="../main.css">
    <style>
        .landing-shop-container {
            background-color: var(--white);
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 2rem;
            height: fit-content;
            display: flex;
            justify-content: center;
            flex-direction: column;
        }

        .discount-container {
            display: flex;
            width: 95%;
            height: fit-content;
            justify-content: center;
            align-items: center;
            flex-wrap: nowrap;
            margin: 0 auto;
        }

        .discount-container figure {
            display: flex;
            width: 100%;
            justify-content: space-between;
        }

        .discount-container img {
            margin: 10px;
            width: 48%;
            height: auto;
        }

        #discount1image {
            float: left;
        }

        #discount2image {
            float: right;
        }

        .options-pane-container {
            display: flex;
            flex-direction: row;
            width: 100%;
            height: fit-content;
            padding: 20px;
            align-items: center;
            justify-content: flex-start;
            gap: 20px;
        }

        .search-bar-container {
            float: left;
            width: 20%;
        }

        #searchbar {
            border: 1px solid #ffffff;
            border-radius: 20px;
            cursor: pointer;
            background-color: var(--greyishblue);
            padding: 10px;
            margin-left: 2vmax;
            color: var(--text);
            width: 100%;
            height: fit-content;
        }

        #searchbar::placeholder {
            font-size: 120%;
            color: var(--text-light);
        }

        #searchbar:hover, #searchbar:focus {
            border: 1px solid #999999;
            outline: none;
        }

        .favorites-icon-container {
            width: 2vmax;
            height: 2vmax;
            float: right;
            margin-left: 4vmax;
        }

        .favorites-icon-container img {
            width: 100%;
            height: 100%;
            transition: width 0.2s ease, height 0.2s ease;
        }

        .favorites-icon-container img:hover {
            width: 130%;
            height: 130%;
        }

        .cart-icon-container {
            width: 2vmax;
            height: 2vmax;
            float: right;
        }

        .cart-icon-container img {
            height: 100%;
            width: 100%;
            transition: width 0.2s ease, height 0.2s ease;
        }

        .cart-icon-container img:hover {
            width: 130%;
            height: 130%;
        }

        .shop-items {
            display: flex;
            justify-content:baseline;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        .shop-item {
            text-align: center;
            width: 300px;
            height: fit-content;
            box-sizing: border-box;
            justify-content: center;
            padding-bottom: 20px;
        }

        .shop-item .item-img img {
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 15vmax;
            height: 15vmax;
            transition: width 0.2s ease, height 0.2s ease;
        }

        .shop-item .item-img img:hover {
            width: 17vmax;
            height: 17vmax;
        }

        .hidden {
            display: none;
        }

        .item-desc-fav-container {
            display: flex;
            flex-direction: row;
            justify-content: center;
            width: fit-content;
            height: fit-content;
            padding: 0;
            position: relative;
        }

        .item-fav-icon {
            float: left;
            width: 10%;
            height: 100%;
            padding-right: 10px;
        }

        .item-fav-icon img {
            width: 20px;
            height: 20px;
            padding-bottom: 20px;
            transition: width 0.1s ease, height 0.1s ease;
            position: absolute;
        }

        .item-fav-icon img:hover {
            width: 25px;
            height: 25px;
        }

        .item-description {
            color: var(--black);
            margin-right: 0;
            padding-left: 60px;
            padding-right: 0;
            float: right;
            width: 90%;
            flex-wrap: wrap;
        }

        .item-description h3, .item-description p {
            margin: 4px;
        }

        .item-price {
            color: var(--price);
        }

        .item-info {
            color: var(--black);
        }

        .item-info-news {
            color: var(--price);
            text-decoration: underline;
        }

        .more-items {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
            margin-top: 30px;
        }

        .more-items button {
            border-radius: 20px;
            background-color: var(--primary);
            color: var(--black);
            width: fit-content;
            height: fit-content;
        }

        .more-items button:hover, button:active {
            background-color: #f8480d;
        }
    </style>
</head>

<body class="body-backg">

<div id="header"></div>

<main class="container">
    <div class="landing-shop-container">
        <div class="discount-container">
            <figure>
                <img id="discount1image" src="uploads/discount1.1.png" alt="november discount">
                <img id="discount2image" src="uploads/discount2.1.png" alt="order discount">
            </figure>
        </div>

        <div class="options-pane-container">
            <div class="search-bar-container">
                <input type="text" id="searchbar" placeholder="Search product">
            </div>
            <div class="favorites-icon-container">
                <img src="uploads/heartfull.png" alt="favorites icon" onclick="filterWishlist()">
            </div>
            <div class="cart-icon-container">
                <a href="../mira/cart.html" target="_blank">
                    <img src="uploads/cart.png" alt="cart icon">
                </a>
            </div>
        </div>

        <div class="shop-items">
            <?php
            $sql = "SELECT * FROM item LIMIT 8";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $counter = 0;
                while ($row = $result->fetch_assoc()) {
                    // Check if item is in the user's wishlist
                    $itemId = $row['item_id'];
                    $wishlistSql = "SELECT * FROM ITEM_IN_WISHLIST iw
                                    INNER JOIN WISHLIST w ON iw.wishlist_id = w.wishlist_id
                                    WHERE iw.item_id = ? AND w.wishlist_user_id = ?";
                    $stmt = $conn->prepare($wishlistSql);
                    $stmt->bind_param('ii', $itemId, $userId);
                    $stmt->execute();
                    $wishlistResult = $stmt->get_result();
                    $isInWishlist = $wishlistResult->num_rows > 0;
                    $favoriteIcon = $isInWishlist ? "heartfull.png" : "heartgrey.png";
                    $hiddenClass = $counter >= 6 ? 'hidden' : '';
                    echo '<div class="shop-item ' . $hiddenClass . '" data-id="' . $row['item_id'] . '"data-name="' . htmlspecialchars($row['item_name']) . '">';
                    echo '    <figure class="item-img">';
                    echo '        <img src="' . htmlspecialchars($row['item_image']) . '" alt="' . htmlspecialchars($row['item_name']) . '" />'; 
                    echo '    </figure>';
                    echo '    <div class="item-desc-fav-container">';
                    echo '        <div class="item-description">';
                    echo '            <h3>' . htmlspecialchars($row['item_name']) . '</h3>';
                    echo '            <p class="item-price">$' . htmlspecialchars($row['item_price']) . '</p>';
                    echo '            <p class="item-info-news">' . htmlspecialchars($row['item_description']) . '</p>';
                    echo '        </div>';
                    echo '        <figure class="item-fav-icon">';
                    echo '            <img src="uploads/heartgrey.png" alt="Favorites Icon" onclick="toggleImage(this)">';
                    echo '        </figure>';
                    echo '    </div>';
                    echo '</div>';
                    $counter++;
                }
            } else {
                echo '<p>No products available</p>';
            }
            ?>
            <div class="more-items">
                <button onclick="viewMoreItems()" id="viewMoreButton">View More</button>
            </div>
        </div>
    </div>
</main>

<div id="footer"></div>

<script>
    function toggleImage(element) {
        const itemElement = element.closest('.shop-item');
        const itemId = itemElement.getAttribute('data-id');

        if (element.src.includes("heartgrey.png")) {
            element.src = "uploads/heartfull.png";
            addToWishlist(itemId);
        } else {
            element.src = "uploads/heartgrey.png";
            removeFromWishlist(itemId);
        }
    }

    function addToWishlist(itemId) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'wishlist_action.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('action=add&item_id=' + itemId);

        xhr.onload = function() {
            if (xhr.status == 200) {
                console.log('Item added to wishlist');
            } else {
                console.error('Error adding to wishlist');
            }
        };
    }

    function removeFromWishlist(itemId) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'wishlist_action.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('action=remove&item_id=' + itemId);

        xhr.onload = function() {
            if (xhr.status == 200) {
                console.log('Item removed from wishlist');
            } else {
                console.error('Error removing from wishlist');
            }
        };
    }

    function filterWishlist() {
        const items = document.querySelectorAll('.shop-item');
        const isWishlistOnly = document.getElementById('wishlistFilter').classList.contains('active');
        
        items.forEach(item => {
            const itemId = item.getAttribute('data-id');
            const heartIcon = item.querySelector('.item-fav-icon img');
            
            if (isWishlistOnly) {
                if (heartIcon.src.includes('heartfull.png')) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            } else {
                item.style.display = '';
            }
        });
        document.getElementById('wishlistFilter').classList.toggle('active');
    }

    document.getElementById('searchbar').addEventListener('input', function() {
        let searchTerm = this.value.toLowerCase();
        let items = document.querySelectorAll('.shop-item');

        items.forEach(item => {
            let itemName = item.getAttribute('data-name').toLowerCase();

            if (itemName.includes(searchTerm)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });

    function viewMoreItems() {
        const items = document.querySelectorAll('.shop-item');
        const hiddenItems = Array.from(items).slice(-2);

        hiddenItems.forEach(item => {
            item.classList.toggle('hidden');
        });

        if (hiddenItems.every(item => !item.classList.contains('hidden'))) {
            document.getElementById('viewMoreButton').innerText = "No More Items";
            document.getElementById('viewMoreButton').disabled = true;
        }
    }
</script>
</body>
</html>