<?php
$host = 'localhost'; // Your database host, usually 'localhost'
$dbname = 'Mira_database'; // Replace with your database name
$username = 'root'; // Default username for XAMPP is 'root'
$password = ''; // Default password for XAMPP is an empty string

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
