<!--hasan-->
<?php
session_start();
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || !isset($_POST['itemId']) || !isset($_POST['action'])) {
    echo json_encode(['success' => false]);
    exit;
}

try {
    $conn = getConnection();
    $userId = $_SESSION['user_id'];
    $itemId = $_POST['itemId'];
    $action = $_POST['action'];

    //get cart ID (thats auto incremented)
    $stmt = $conn->prepare("SELECT cart_id FROM CART WHERE cart_user_id = ?");
    $stmt->execute([$userId]);
    $cart = $stmt->fetch();

    if ($cart) {
        if ($action === 'increase') {
            $stmt = $conn->prepare("
                UPDATE ITEM_IN_CART 
                SET quantity = quantity + 1 
                WHERE cart_id = ? AND item_id = ?
            ");
        } else {
            $stmt = $conn->prepare("
                UPDATE ITEM_IN_CART 
                SET quantity = GREATEST(quantity - 1, 0) 
                WHERE cart_id = ? AND item_id = ?
            ");
        }
        $stmt->execute([$cart['cart_id'], $itemId]);

        //remove item if quantity is 0
        $stmt = $conn->prepare("
            DELETE FROM ITEM_IN_CART 
            WHERE cart_id = ? AND item_id = ? AND quantity = 0
        ");
        $stmt->execute([$cart['cart_id'], $itemId]);

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    echo json_encode(['success' => false]);
}