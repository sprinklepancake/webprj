<?php
//hasan
session_start();
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

try {
    $conn = getConnection();
    
    //get all orders for the logged-in user
    $stmt = $conn->prepare("
        SELECT 
            o.order_date,
            i.item_name as product,
            oi.order_item_price as amount,
            o.order_status as status
        FROM ORDERS o
        JOIN ORDER_ITEM oi ON o.order_id = oi.order_id
        JOIN ITEM i ON oi.item_id = i.item_id
        WHERE o.order_user_id = ?
        ORDER BY o.order_date DESC
    ");
    
    $stmt->execute([$_SESSION['user_id']]);
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'transactions' => $transactions
    ]);
    
} catch (Exception $e) {
    error_log("Transaction error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error retrieving transactions'
    ]);
}