<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    $conn = getConnection();
    error_log("Database connection successful");
} catch (PDOException $e) {
    error_log('Database connection error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

//function to sanitize input
function sanitizeInput($input) {
    if (is_string($input)) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    return $input;
}

//required fields
$username = sanitizeInput($_POST['username'] ?? '');
$password = sanitizeInput($_POST['password'] ?? '');
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$firstName = sanitizeInput($_POST['firstName'] ?? '');
$lastName = sanitizeInput($_POST['lastName'] ?? '');
$role = filter_input(INPUT_POST, 'role', FILTER_VALIDATE_INT);

//optional fields
$phoneNumber = sanitizeInput($_POST['phoneNumber'] ?? '');
$country = sanitizeInput($_POST['country'] ?? '');
$region = sanitizeInput($_POST['region'] ?? '');

//debug processed data
error_log('Processed data:');
error_log("Username: $username");
error_log("Email: $email");
error_log("First Name: $firstName");
error_log("Last Name: $lastName");
error_log("Role: $role");

//validate required fields
if (!$username || !$password || !$email || !$firstName || !$lastName || !$role) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
    exit;
}

//validate role
if ($role !== 1 && $role !== 2) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please select a valid account type']);
    exit;
    //1 is buyer 2 is seller
}

//validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address']);
    exit;
}

//validate username format
if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Username can only contain letters and numbers']);
    exit;
}

//validate password length
if (strlen($password) < 8) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters long']);
    exit;
}

//validate phone number if provided
if ($phoneNumber && !preg_match('/^[0-9]{8}$/', $phoneNumber)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Phone number must be exactly 8 digits']);
    exit;
}

try {
    //check if username already exists
    $stmt = $conn->prepare("SELECT user_id FROM USERS WHERE user_username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Username already exists']);
        exit;
    }

    //check if email already exists
    $stmt = $conn->prepare("SELECT user_id FROM USERS WHERE user_email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Email already exists']);
        exit;
    }

    //hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    //convert phone number to integer if provided
    $phoneNumberInt = $phoneNumber ? intval($phoneNumber) : null;
    
    error_log("Attempting to insert new user");
    
    $stmt = $conn->prepare("
        INSERT INTO USERS (
            user_username,
            user_password,
            user_email,
            user_first_name,
            user_last_name,
            user_role,
            user_country,
            user_region,
            user_phone_number
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $params = [
        $username,
        $hashedPassword,
        $email,
        $firstName,
        $lastName,
        $role,
        $country ?: null,
        $region ?: null,
        $phoneNumberInt
    ];
    
    error_log("Execute parameters: " . print_r($params, true));
    
    $stmt->execute($params);
    error_log("User inserted successfully");

    $userId = $conn->lastInsertId();

    //set session variables
    $_SESSION['user_id'] = $userId;
    $_SESSION['username'] = $username;
    $_SESSION['user_role'] = $role;

    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Registration successful',
        'redirect' => '../petra/landing.html'
    ]);
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    error_log('SQL state: ' . $e->getCode());
    if (isset($stmt)) {
        error_log('SQL query: ' . $stmt->queryString);
    }
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}