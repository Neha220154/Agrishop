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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $conn->real_escape_string($_POST['product_name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = $conn->real_escape_string($_POST['price']);
    $quantity = $conn->real_escape_string($_POST['quantity']);
    $image = $conn->real_escape_string($_POST['image']);

    // Insert product into the database
    $sql = "INSERT INTO products (product_name, description, price, quantity, image)
            VALUES ('$product_name', '$description', '$price', '$quantity', '$image')";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='success'>New product added successfully!</div>";
    } else {
        echo "<div class='error'>Error: " . $conn->error . "</div>";
    }
}

// Close the connection
$conn->close();
?>
