<?php
//hasan
session_start();

//check if user is logged in
if (!isset($_SESSION['user_id'])) {
    error_log('User is not logged in');
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Forbidden']);
    exit;
}

require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error_log('Invalid request method');
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$itemId = $_POST['itemId'];
error_log('Item ID: ' . $itemId);

try {
    $conn = getConnection();

    //check if the item exists in the user's wishlist
    $stmt = $conn->prepare("
        SELECT * 
        FROM ITEM_IN_WISHLIST
        WHERE item_id = ? AND wishlist_id = (
            SELECT wishlist_id
            FROM WISHLIST
            WHERE wishlist_user_id = ?
        )
    ");
    $stmt->execute([$itemId, $_SESSION['user_id']]);
    error_log('Query executed');

    if ($stmt->rowCount() === 0) {
        error_log('Item not found in wishlist');
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Item not found in wishlist']);
        exit;
    }

    //delete the item from the wishlist
    $stmt = $conn->prepare("
        DELETE FROM ITEM_IN_WISHLIST
        WHERE item_id = ? AND wishlist_id = (
            SELECT wishlist_id
            FROM WISHLIST
            WHERE wishlist_user_id = ?
        )
    ");
    $stmt->execute([$itemId, $_SESSION['user_id']]);
    error_log('Item removed from wishlist');

    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'Item removed from wishlist']);
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}