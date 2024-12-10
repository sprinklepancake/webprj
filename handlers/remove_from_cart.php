<?php
//hasan
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || !isset($_POST['itemId'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit;
}

try {
    $conn = getConnection();
    $userId = $_SESSION['user_id'];
    $itemId = $_POST['itemId'];

    //debug logs (during dev)
    error_log("Attempting to remove item. User ID: $userId, Item ID: $itemId");

    //get cart ID
    $stmt = $conn->prepare("SELECT cart_id FROM CART WHERE cart_user_id = ?");
    $stmt->execute([$userId]);
    $cart = $stmt->fetch();

    if ($cart) {
        error_log("Cart found with ID: " . $cart['cart_id']);
        
        //remove item from cart
        $stmt = $conn->prepare("DELETE FROM ITEM_IN_CART WHERE cart_id = ? AND item_id = ?");
        $stmt->execute([$cart['cart_id'], $itemId]);
        
        //update cart quantity
        $stmt = $conn->prepare("UPDATE CART SET cart_quantity = (
            SELECT COUNT(*) FROM ITEM_IN_CART WHERE cart_id = ?
        ) WHERE cart_id = ?");
        $stmt->execute([$cart['cart_id'], $cart['cart_id']]);

        error_log("Item removed successfully");
        echo json_encode(['success' => true]);
    } else {
        error_log("No cart found for user");
        echo json_encode(['success' => false, 'message' => 'Cart not found']);
    }
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    error_log('Stack trace: ' . $e->getTraceAsString());
    echo json_encode([
        'success' => false, 
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}