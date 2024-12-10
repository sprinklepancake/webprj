<!--petra-->
<?php
include 'includes/db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page - Fitness Equipment Store</title>
    <link rel="stylesheet" href="../main.css">
    <link target="_blank">
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

        .sliding-image-container {
            position: relative;
            margin: 0 auto;
            width: 70%;
            overflow: hidden;
            justify-content: center;
            align-items: center;
            display: flex;
        }

        .image-container {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }

        .image-container img {
            border-radius: 8px;
            height: 29vmax;
            width: 50vmax;
        }

        .arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 30px;
            color: var(--black);
            background-color: var(--white);
            padding: 10px;
            cursor: pointer;
        }

        .left {
            left: 0;
        }

        .right {
            right: 0;
        }

        .arrow:hover {
            background-color: #d6d6d6;
        }

        .header-text {
            text-align: center;
            font-size: 1.5rem;
            margin-bottom: 2rem;
            padding-left: 10px;
            padding-right: 10px;
            color: var(--black);
        }

        .shop-items {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        .shop-item {
            text-align: center;
            width: 25%;
            height:fit-content;
        }

        .shop-item img {
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 15vmax;
            height: 15vmax;
            transition: width 0.2s ease, height 0.2s ease;
        }

        .shop-item img:hover {
            width: 17vmax;
            height: 17vmax;
        }

        .item-description {
            color: var(--black);
            margin-bottom: 20px;
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
            color: var(--greyishblue);
            margin-bottom: 30px;
        }

        .more-items a {
            text-decoration: none;
        }

        .more-items a:link, a:active, a:visited {
            color: #7f8186;
            text-decoration: none;
        }

        .more-items a:hover {
            color: var(--black);
            cursor: pointer;
            text-decoration: none;
        }
    </style>
</head>

<body class="body-backg">
    <div id="header"></div>

    <main class="container">
        <div class="landing-shop-container">
            <div class="sliding-image-container">
                <button class="arrow left" onclick="changeImage(-1)"><</button>
                <div class="image-container">
                    <img src="uploads/sliding1.png" id="slideImage" alt="sliding images">
                </div>
                <button class="arrow right" onclick="changeImage(1)">></button>
            </div>

            <div class="header-text">
                <h1>Welcome to Fitness Equipment Store</h1>
                <p>Your one-stop shop for high-quality gym equipment to get you in shape. Browse our selection of the best equipment to enhance your workout routine and achieve your fitness goals!</p>
            </div>

            <div class="shop-items">
                <?php
                $sql = "SELECT * FROM item LIMIT 4";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="shop-item">';
                        echo '    <figure>';
                        echo '        <img src="' . htmlspecialchars($row['item_image']) . '" alt="' . htmlspecialchars($row['item_name']) . '" />';
                        echo '    </figure>';
                        echo '    <div class="item-description">';
                        echo '        <h3>' . htmlspecialchars($row['item_name']) . '</h3>';
                        echo '        <p class="item-price">$' . htmlspecialchars($row['item_price']) . '</p>';
                        echo '        <p class="item-info-news">' . htmlspecialchars($row['item_description']) . '</p>';
                        echo '    </div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No products available</p>';
                }
                ?>
                <div class="more-items">
                    <a href="shop.php">View More Items</a>
                </div>
            </div>
        </div>
    </main>

    <div id="footer"></div>
    <script>
        let images = ['uploads/sliding1.png', 'uploads/sliding2.png', 'uploads/sliding3.png'];
        let currentIndex = 0;

        function changeImage(direction) {
            currentIndex = (currentIndex + direction + images.length) % images.length;
            document.getElementById("slideImage").src = images[currentIndex];
        }
    </script>
</body>
</html>