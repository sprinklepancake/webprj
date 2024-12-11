<?php
//hasan
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

//log the incoming request
error_log('Cart action request received: ' . json_encode($_POST));

if (!isset($_SESSION['user_id'])) {
    error_log('User not logged in');
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

try {
    $conn = getConnection();
    $userId = $_SESSION['user_id'];
    $itemId = $_POST['item_id'] ?? null;
    $action = $_POST['action'] ?? null;

    //log the parsed parameters
    error_log("Processing request - UserID: $userId, ItemID: $itemId, Action: $action");

    if (!$itemId || !$action) {
        error_log('Missing parameters in request');
        echo json_encode(['success' => false, 'message' => 'Missing parameters']);
        exit;
    }

    //check if user has a cart
    $stmt = $conn->prepare("SELECT cart_id FROM CART WHERE cart_user_id = ?");
    $stmt->execute([$userId]);
    $cart = $stmt->fetch();
    
    if (!$cart) {
        error_log("Creating new cart for user: $userId");
        $stmt = $conn->prepare("INSERT INTO CART (cart_user_id, cart_quantity) VALUES (?, 0)");
        $stmt->execute([$userId]);
        $cartId = $conn->lastInsertId();
        error_log("New cart created with ID: $cartId");
    } else {
        $cartId = $cart['cart_id'];
        error_log("Using existing cart ID: $cartId");
    }

    if ($action === 'add') {
        //check if item exists first
        $stmt = $conn->prepare("SELECT item_id FROM ITEM WHERE item_id = ?");
        $stmt->execute([$itemId]);
        if (!$stmt->fetch()) {
            error_log("Item does not exist: $itemId");
            echo json_encode(['success' => false, 'message' => 'Item does not exist']);
            exit;
        }

        //check if item already in cart
        $stmt = $conn->prepare("SELECT * FROM ITEM_IN_CART WHERE cart_id = ? AND item_id = ?");
        $stmt->execute([$cartId, $itemId]);
        
        if (!$stmt->fetch()) {
            error_log("Adding item $itemId to cart $cartId");
            //add item to cart
            $stmt = $conn->prepare("INSERT INTO ITEM_IN_CART (cart_id, item_id) VALUES (?, ?)");
            $stmt->execute([$cartId, $itemId]);
            
            //update cart quantity
            $stmt = $conn->prepare("UPDATE CART SET cart_quantity = cart_quantity + 1 WHERE cart_id = ?");
            $stmt->execute([$cartId]);
            
            error_log("Item successfully added to cart");
            echo json_encode(['success' => true, 'message' => 'Item added to cart']);
        } else {
            error_log("Item already exists in cart");
            echo json_encode(['success' => true, 'message' => 'Item already in cart']);
        }
    } elseif ($action === 'remove') {
        error_log("Removing item $itemId from cart $cartId");
        $stmt = $conn->prepare("DELETE FROM ITEM_IN_CART WHERE cart_id = ? AND item_id = ?");
        $stmt->execute([$cartId, $itemId]);
        
        //update cart quantity
        $stmt = $conn->prepare("UPDATE CART SET cart_quantity = GREATEST(cart_quantity - 1, 0) WHERE cart_id = ?");
        $stmt->execute([$cartId]);
        
        echo json_encode(['success' => true, 'message' => 'Item removed from cart']);
    } else {
        error_log("Invalid action received: $action");
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }

} catch (PDOException $e) {
    error_log('Database error in cart_action.php: ' . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'Database error occurred',
        'debug' => $e->getMessage() //only include in development
    ]);
} catch (Exception $e) {
    error_log('General error in cart_action.php: ' . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'An unexpected error occurred',
        'debug' => $e->getMessage() //only include in development
    ]);
}