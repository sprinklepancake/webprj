<!--hasan-->
<?php
session_start();
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    //sanitize input
    function sanitizeInput($input) {
        if (is_string($input)) {
            return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
        }
        return $input;
    }

    $username = sanitizeInput($_POST['username'] ?? '');
    $password = sanitizeInput($_POST['password'] ?? '');

    if (!$username || !$password) {
        echo json_encode(['success' => false, 'message' => 'Please fill in all fields']);
        exit;
    }

    $conn = getConnection();
    
    $stmt = $conn->prepare("SELECT user_id, user_username, user_password, user_role FROM USERS WHERE user_username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    //password_verify to check the hashed password
    if ($user && password_verify($password, $user['user_password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['user_username'];
        $_SESSION['user_role'] = $user['user_role'];
        
        echo json_encode([
            'success' => true,
            'message' => 'Login successful',
            'redirect' => '../petra/landing.html'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
    }
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'An unexpected error occurred. Please try again later.'
    ]);
}