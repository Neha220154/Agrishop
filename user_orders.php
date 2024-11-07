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

// Fetch orders for the logged-in user from the database
try {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_email = :user_email ORDER BY order_date DESC");
    $stmt->bindParam(':user_email', $user_email);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching orders: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Agrishop</title>
    <link rel="stylesheet" href="user-orders.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>My Orders</h1>
            <div class="logout">
                <a href="logout.php">Logout</a>
            </div>
        </header>

        <main class="main-content">
            <h2>Order History</h2>

            <?php if (count($orders) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Order Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?= htmlspecialchars($order['order_id']) ?></td>
                                <td><?= htmlspecialchars($order['product_name']) ?></td>
                                <td>$<?= htmlspecialchars($order['price']) ?></td>
                                <td><?= htmlspecialchars($order['quantity']) ?></td>
                                <td><?= htmlspecialchars($order['order_date']) ?></td>
                                <td>Completed</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>You have not placed any orders yet.</p>
            <?php endif; ?>
        </main>
        <a href="user-dashboard.php">Go back to dashboard</a>
    </div>
</body>
</html>
