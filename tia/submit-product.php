<?php
// Database connection details
$host = 'localhost'; // Update this with your database host
$username = 'root';  // Update this with your database username
$password = '';      // Update this with your database password
$dbname = 'fitness_store'; // Update this with your database name

// Connect to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = floatval($_POST['price']);
    $dateAdded = date('Y-m-d'); // Get the current date
    $imagePath = '';

    // Handle the uploaded file
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/'; // Directory to store uploaded files
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true); // Create the directory if it doesn't exist
        }

        $imageName = basename($_FILES['image']['name']);
        $imagePath = $uploadDir . uniqid() . '_' . $imageName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            die("Failed to upload the image.");
        }
    }

    // Insert data into the database
    $sql = "INSERT INTO item (item_name, item_description, item_price, item_image, item_date_added, aggregate_rating, item_quantity)
            VALUES ('$title', '$description', $price, '$imagePath', '$dateAdded', 0, 1)";

    if ($conn->query($sql) === TRUE) {
        header('Location: ../tia/posting.html');
        
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the connection
$conn->close();
?>
