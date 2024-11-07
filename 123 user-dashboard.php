<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
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

// Fetch products from the database
try {
    $stmt = $pdo->query("SELECT product_name, description, price, quantity, image FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching products: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="user-dashboard.css">
</head>
<body>

<div class="container">
    <header class="header">
        <h1>User Dashboard</h1>
        <ul>
            <li><a href="user_profile.php">Profile</a></li>
            <li><a href="user_orders.php">My Orders</a></li>
           
            <li><a href="make_order.php">Make Order</a></li>
            <li><a href="user_settings.php">Settings</a></li>
        </ul>
        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>
    </header>

    <main class="main-content">
        <h2>Welcome....!</h2>
        <p>Browse our products below:</p>

        <div class="card-container">
            <?php if ($products): ?>
                <?php foreach ($products as $product): ?>
                    <div class="card">
                        <?php
                        // Display image from database if it's a base64 image, otherwise load from images folder
                        if (strpos($product['image'], 'data:image/jpeg;base64,') === 0) {
                            echo '<img src="' . htmlspecialchars($product['image']) . '" alt="' . htmlspecialchars($product['product_name']) . '">';
                        } else {
                            echo '<img src="images/' . htmlspecialchars($product['image']) . '" alt="' . htmlspecialchars($product['product_name']) . '">';
                        }
                        ?>
                        <h3><?= htmlspecialchars($product['product_name']) ?></h3>
                        <p>Price: $<?= htmlspecialchars($product['price']) ?></p>

                        <!-- Form that submits to make_order.php -->
                        <form method="POST" action="make_order.php">
                            <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['product_name']) ?>">
                            <input type="hidden" name="price" value="<?= htmlspecialchars($product['price']) ?>">
                            <button type="submit" name="make_order" class="btn">Make Order</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No products available at the moment.</p>
            <?php endif; ?>
        </div>
    </main>
</div>

</body>
</html>
