<?php
//hasan
session_start();
require_once __DIR__ . '../config/database.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['shipping_address'])) {
    header('Location: ../mira/cart.php');
    exit;
}

try {
    $conn = getConnection();
    $userId = $_SESSION['user_id'];
    $address = $_POST['shipping_address'];

    //start transaction
    $conn->beginTransaction();

    //create order
    $stmt = $conn->prepare("
        INSERT INTO ORDERS (order_user_id, order_date, order_status) 
        VALUES (?, CURRENT_DATE, 'Pending')
    ");
    $stmt->execute([$userId]);
    $orderId = $conn->lastInsertId();

    //get cart items
    $stmt = $conn->prepare("
        SELECT i.item_id, i.item_price, ic.quantity
        FROM ITEM_IN_CART ic
        JOIN CART c ON ic.cart_id = c.cart_id
        JOIN ITEM i ON ic.item_id = i.item_id
        WHERE c.cart_user_id = ?
    ");
    $stmt->execute([$userId]);
    $items = $stmt->fetchAll();

    //insert order items
    $stmt = $conn->prepare("
        INSERT INTO ORDER_ITEM (order_id, item_id, quantity) 
        VALUES (?, ?, ?)
    ");
    foreach ($items as $item) {
        $stmt->execute([$orderId, $item['item_id'], $item['quantity']]);
    }

    //clear cart
    $stmt = $conn->prepare("
        DELETE FROM ITEM_IN_CART 
        WHERE cart_id IN (SELECT cart_id FROM CART WHERE cart_user_id = ?)
    ");
    $stmt->execute([$userId]);

    $conn->commit();
    header('Location: ../petra/landing.html');
} catch (Exception $e) {
    $conn->rollBack();
    error_log('Order processing error: ' . $e->getMessage());
    header('Location: ../mira/cart.php');
}