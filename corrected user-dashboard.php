<?php
session_start();

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

// Initialize wishlist
if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

// Handle adding to wishlist
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_wishlist'])) {
    $product = $_POST['product_name'];
    if (!in_array($product, $_SESSION['wishlist'])) {
        $_SESSION['wishlist'][] = $product;
    }
}

// Fetch products from the database
$stmt = $pdo->query("SELECT product_name, description, price, quantity, image FROM product");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            <li><a href="user_cart.php">Shopping Cart</a></li>
            <li><a href="user_wishlist.php">Wishlist</a></li>
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
            <?php
            foreach ($products as $product) {
                echo '<div class="card">';
                if (strpos($product['image'], 'data:image/jpeg;base64,') === 0) {
                    echo '<img src="' . htmlspecialchars($product['image']) . '" alt="' . htmlspecialchars($product['product_name']) . '">';
                } else {
                    echo '<img src="images/' . htmlspecialchars($product['image']) . '" alt="' . htmlspecialchars($product['product_name']) . '">';
                }
                echo '<h3>' . htmlspecialchars($product['product_name']) . '</h3>';
                echo '<p>' . htmlspecialchars($product['price']) . '</p>';
                echo '<form method="POST" action="">';
                echo '<input type="hidden" name="product_name" value="' . htmlspecialchars($product['product_name']) . '">';
                echo '<button type="submit" name="add_to_wishlist" class="btn">Add to Wishlist</button>';
                echo '</form>';
                echo '</div>';
            }
            ?>
        </div>
    </main>
</div>

</body>
</html>
