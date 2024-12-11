<?php
require_once '../config/database.php';
$conn = getConnection();
?>

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page - Fitness Equipment Store</title>
    <link rel="stylesheet" href="../main.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .landing-shop-container {
            background-color: var(--black);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin-top: 2rem;
            overflow: hidden;
        }

        .sliding-image-container {
            position: relative;
            width: 100%;
            height: 50vh;
            overflow: hidden;
        }

        .image-container {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 2rem;
            color: var(--white);
            background-color: rgba(0, 0, 0, 0.5);
            padding: 1rem;
            cursor: pointer;
            border: none;
            outline: none;
            transition: background-color 0.3s ease;
        }

        .left {
            left: 1rem;
        }

        .right {
            right: 1rem;
        }

        .arrow:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }

        .header-text {
            text-align: center;
            padding: 3rem 2rem;
            background: linear-gradient(to right, var(--primary-dark), var(--primary));
        }

        .header-text h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 1rem;
        }

        .header-text p {
            font-size: 1.1rem;
            color: var(--white);
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .shop-items {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            padding: 3rem 2rem;
        }

        .shop-item {
            background-color: var(--black);
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .shop-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .shop-item img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .item-description {
            padding: 1.5rem;
        }

        .item-description h3 {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--white);
            margin-bottom: 0.5rem;
        }

        .item-price {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .item-info-news {
            font-size: 0.9rem;
            color: var(--text);
            text-decoration: none;
        }

        .more-items {
            text-align: center;
            padding: 2rem 0;
        }

        .more-items a {
            display: inline-block;
            padding: 0.8rem 2rem;
            background-color: var(--primary);
            color: var(--white);
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .more-items a:hover {
            background-color: var(--primary-dark);
        }

        @media (max-width: 768px) {
            .header-text h1 {
                font-size: 2rem;
            }

            .header-text p {
                font-size: 1rem;
            }

            .shop-items {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }
        }
    </style>
</head>

<body class="body-backg">
    <div id="header"></div>

    <main class="container">
        <div class="landing-shop-container">
            <div class="sliding-image-container">
                <button class="arrow left" onclick="changeImage(-1)">&#10094;</button>
                <div class="image-container">
                    <img src="uploads/sliding1.png" id="slideImage" alt="Featured gym equipment">
                </div>
                <button class="arrow right" onclick="changeImage(1)">&#10095;</button>
            </div>

            <div class="header-text">
                <h1>Welcome to Fitness Equipment Store</h1>
                <p>Your one-stop shop for high-quality gym equipment to get you in shape. Browse our selection of the best equipment to enhance your workout routine and achieve your fitness goals!</p>
            </div>

            <div class="shop-items">
                <?php
                try {
                    $sql = "SELECT * FROM ITEM LIMIT 4";
                    $stmt = $conn->query($sql);
                    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                    if (count($items) > 0) {
                        foreach ($items as $row) {
                            echo '<div class="shop-item">';
                            echo '    <img src="' . htmlspecialchars($row['item_image']) . '" alt="' . htmlspecialchars($row['item_name']) . '" />';
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
                } catch (PDOException $e) {
                    error_log('Query error: ' . $e->getMessage());
                    echo '<p>Error retrieving products. Please try again later.</p>';
                }
                ?>
            </div>
            <div class="more-items">
                <a href="shop.php">View More Items</a>
            </div>
        </div>
    </main>

    <div id="footer"></div>
    <script src="../js/main.js"></script>
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