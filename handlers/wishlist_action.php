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

    //check if user has a wishlist, if not create one (same as in the page)
    $stmt = $conn->prepare("SELECT wishlist_id FROM WISHLIST WHERE wishlist_user_id = ?");
    $stmt->execute([$userId]);
    $wishlist = $stmt->fetch();

    if (!$wishlist) {
        $stmt = $conn->prepare("INSERT INTO WISHLIST (wishlist_user_id, wishlist_quantity) VALUES (?, 0)");
        $stmt->execute([$userId]);
        $wishlistId = $conn->lastInsertId();
    } else {
        $wishlistId = $wishlist['wishlist_id'];
    }

    if ($action === 'add') {
        //check if item already in wishlist
        $stmt = $conn->prepare("SELECT * FROM ITEM_IN_WISHLIST WHERE wishlist_id = ? AND item_id = ?");
        $stmt->execute([$wishlistId, $itemId]);
        if (!$stmt->fetch()) {
            $stmt = $conn->prepare("INSERT INTO ITEM_IN_WISHLIST (wishlist_id, item_id) VALUES (?, ?)");
            $stmt->execute([$wishlistId, $itemId]);
        }
        echo json_encode(['success' => true, 'message' => 'Item added to wishlist']);
    } elseif ($action === 'remove') {
        $stmt = $conn->prepare("DELETE FROM ITEM_IN_WISHLIST WHERE wishlist_id = ? AND item_id = ?");
        $stmt->execute([$wishlistId, $itemId]);
        echo json_encode(['success' => true, 'message' => 'Item removed from wishlist']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error']);
}