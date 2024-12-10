<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/../config/database.php';

try {
    $conn = getConnection();
    
    // Get equipment guide items ordered by display_order
    $stmt = $conn->prepare("SELECT * FROM EQUIPMENT_GUIDE ORDER BY display_order ASC");
    $stmt->execute();
    $equipment_items = $stmt->fetchAll();
    
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    http_response_code(500);
    echo 'Database error: ' . $e->getMessage();
    exit;
}
?>
<!--hasan-->
<!DOCTYPE html>
<html lang="en">
<head>
    <!--//TODO: work on SEO-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipment Guide - Fitness Equipment Store</title>
    <link rel="stylesheet" href="../main.css">
    <!--bootstrap css-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJwL+fOHzv5vwJn60Xy54QkRsM16u1JeDb6IkzGy0y5e5cXhU5tF7eTt6Qt5" crossorigin="anonymous">
    <style>
        /* Your existing CSS stays the same */
        .equipment-guide-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem;
            min-height: 100vh;
            box-sizing: border-box;
        }

        .equipment-card {
            background-color: var(--black);
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            max-width: 400px;
            margin: 1.5rem 0;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            opacity: 0;
            transform: translateY(20px);
        }

        .equipment-card.visible {
            opacity: 1;
            transform: translateY(0);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }

        .equipment-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2), 0 0 10px rgba(72, 133, 237, 0.3);
        }

        .equipment-card img {
            width: 100%;
            height: auto;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .equipment-card img:hover {
            transform: scale(1.1); 
        }

        .equipment-card-content {
            padding: 1.5rem;
        }

        .equipment-card-title {
            color: var(--primary-dark);
            font-size: 1.5rem;
            margin: 0.5rem 0;
        }

        .equipment-card-description {
            color: var(--text-light);
            font-size: 1rem;
            line-height: 1.5;
            margin: 0.5rem 0;
        }

        .equipment-card-specs {
            font-size: 0.9rem;
            color: var(--text);
            margin-top: 0.5rem;
        }

        .equipment-card-specs li {
            margin: 0.2rem 0;
            text-align: left;
        }

        .view-in-shop-btn {
            background-color: var(--secondary-dark);
            color: var(--black);
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-transform: uppercase;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            margin-top: 1rem;
            transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        }

        .view-in-shop-btn:hover {
            background-color: var(--primary-dark);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        @media (min-width: 768px) {
            .equipment-guide-container {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 1.5rem;
                justify-items: center;
            }
        }
    </style>
</head>
<body class="body-backg">
    <div id="header"></div>

    <main class="container">
        <div class="row equipment-guide-container">
            <?php foreach ($equipment_items as $item): ?>
            <div class="col-12 col-md-4">
                <div class="equipment-card">
                    <img src="<?= htmlspecialchars($item['guide_image']) ?>" alt="<?= htmlspecialchars($item['guide_title']) ?>">
                    <div class="equipment-card-content">
                        <h3 class="equipment-card-title"><?= htmlspecialchars($item['guide_title']) ?></h3>
                        <p class="equipment-card-description">
                            <?= htmlspecialchars($item['guide_description']) ?>
                        </p>
                        <ul class="equipment-card-specs">
                            <?php 
                            $specs = explode('|', $item['guide_specs']);
                            foreach ($specs as $spec):
                            ?>
                                <li><?= htmlspecialchars($spec) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <a href="<?= htmlspecialchars($item['shop_link']) ?>" class="view-in-shop-btn">View in Shop</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>

    <div id="footer"></div>
    
    <!--bootstrap js-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0WpRDi7GYNXkJkhYo5Ckxl3J+r+g6ay6SzkFIM9GxGm5yz+7" crossorigin="anonymous"></script>
    <script>
        //make sure DOM loads content to then load the cards
        document.addEventListener("DOMContentLoaded", function() {
            const cards = document.querySelectorAll('.equipment-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('visible');
                }, 200 * index); //some delay for animation stuff
            });
        });
    </script>
    <script src="../js/main.js"></script>
</body>
</html>