<?php
session_start();
include 'includes/db_connect.php';

$userId = $_SESSION['user_id'];
$itemId = $_POST['item_id'];
$action = $_POST['action'];

if ($action == 'add') {
    $sql = "SELECT wishlist_id FROM WISHLIST WHERE wishlist_user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        $sql = "INSERT INTO WISHLIST (wishlist_user_id, wishlist_quantity) VALUES (?, 1)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $wishlistId = $stmt->insert_id;
    } else {
        $wishlistId = $result->fetch_assoc()['wishlist_id'];
    }

    $sql = "SELECT * FROM ITEM_IN_WISHLIST WHERE wishlist_id = ? AND item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $wishlistId, $itemId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $sql = "INSERT INTO ITEM_IN_WISHLIST (item_id, wishlist_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $itemId, $wishlistId);
        $stmt->execute();
        echo "Item added to wishlist";
    } else {
        echo "Item already in wishlist";
    }

} elseif ($action == 'remove') {
    $sql = "SELECT wishlist_id FROM WISHLIST WHERE wishlist_user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $wishlistId = $result->fetch_assoc()['wishlist_id'];

    $sql = "DELETE FROM ITEM_IN_WISHLIST WHERE item_id = ? AND wishlist_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $itemId, $wishlistId);
    $stmt->execute();
    echo "Item removed from wishlist";
}
?>