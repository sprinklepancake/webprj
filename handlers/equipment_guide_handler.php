<?php
//hasan
//had to add __DIR__ to work
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

try {
    $conn = getConnection();
    
    $stmt = $conn->prepare("
        SELECT 
            i.item_id,
            i.item_name,
            i.item_description,
            i.item_image,
            i.item_price,
            c.category_name
        FROM ITEM i
        LEFT JOIN ITEM_IN_CATEGORY ic ON i.item_id = ic.item_id
        LEFT JOIN CATEGORY c ON ic.category_id = c.category_id
        WHERE i.item_quantity > 0
    ");
    
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'items' => $items
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error occurred'
    ]);
}