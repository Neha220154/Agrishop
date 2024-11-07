<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit();
}

// Database connection
$host = 'localhost';
$db = 'agrishop';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Get the logged-in user's email from the session
$user_email = $_SESSION['email'];

// Initialize order confirmation flag
$order_confirmation = false;

// Handle order form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect order data from the form
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1; // Default to 1 if quantity is not set
    $order_date = date('Y-m-d H:i:s'); // Current date and time

    // Insert order into the orders table
    try {
        // SQL query to insert the order
        $sql = "INSERT INTO orders (user_email, product_name, price, quantity, order_date) 
                VALUES (:user_email, :product_name, :price, :quantity, :order_date)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_email', $user_email);
        $stmt->bindParam(':product_name', $product_name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':order_date', $order_date);
        $stmt->execute();

        // Set order confirmation flag to true
        $order_confirmation = true;
    } catch (PDOException $e) {
        // Display error message if something goes wrong
        echo "Error placing order: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Agrishop</title>
    <link rel="stylesheet" href="make_order.css">
</head>
<body>
    <h2>Order Confirmation</h2>

    <?php if ($order_confirmation): ?>
        <p>Your order has been placed successfully!</p>
        <p>Product: <?= htmlspecialchars($product_name) ?></p>
        <p>Price: $<?= htmlspecialchars($price) ?></p>
        <p>Quantity: <?= htmlspecialchars($quantity) ?></p>
        <p>Order Date: <?= htmlspecialchars($order_date) ?></p>
    <?php else: ?>
        <p>There was an issue placing your order.</p>
    <?php endif; ?>

    <a href="user-dashboard.php">Go back to dashboard</a>
</body>
</html>
