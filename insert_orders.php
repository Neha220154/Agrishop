<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

// Database connection
$host = 'localhost';
$db = 'agrishop';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $customer_name = $conn->real_escape_string($_POST['customer_name']);
    $product_name = $conn->real_escape_string($_POST['product_name']);
    $quantity = (int) $_POST['quantity'];
    $total_price = (float) $_POST['total_price'];
    $image_url = $conn->real_escape_string($_POST['image_url']); // New field for image URL
    
    // Prepare SQL statement to insert order
    $sql = "INSERT INTO orders (customer_name, product_name, quantity, total_price, image_url) 
            VALUES ('$customer_name', '$product_name', $quantity, $total_price, '$image_url')";

    if ($conn->query($sql) === TRUE) {
        echo "New order created successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
