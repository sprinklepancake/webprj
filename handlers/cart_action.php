<!--hasan-->
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

try {
    $conn = getConnection();
    $userId = $_SESSION['user_id'];
    $itemId = $_POST['item_id'] ?? null;
    $action = $_POST['action'] ?? null;

    if (!$itemId || !$action) {
        echo json_encode(['success' => false, 'message' => 'Missing parameters']);
        exit;
    }

    //check if user has a cart, if not create one
    $stmt = $conn->prepare("SELECT cart_id FROM CART WHERE cart_user_id = ?");
    $stmt->execute([$userId]);
    $cart = $stmt->fetch();

    if (!$cart) {
        $stmt = $conn->prepare("INSERT INTO CART (cart_user_id, cart_quantity) VALUES (?, 0)");
        $stmt->execute([$userId]);
        $cartId = $conn->lastInsertId();
    } else {
        $cartId = $cart['cart_id'];
    }

    if ($action === 'add') {
        //check if item already in cart
        $stmt = $conn->prepare("SELECT * FROM ITEM_IN_CART WHERE cart_id = ? AND item_id = ?");
        $stmt->execute([$cartId, $itemId]);
        if (!$stmt->fetch()) {
            //add item to cart
            $stmt = $conn->prepare("INSERT INTO ITEM_IN_CART (cart_id, item_id) VALUES (?, ?)");
            $stmt->execute([$cartId, $itemId]);
        }
        echo json_encode(['success' => true, 'message' => 'Item added to cart']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error']);
}