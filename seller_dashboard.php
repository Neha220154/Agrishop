<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: seller_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard</title>
    <link rel="stylesheet" href="seller-dashboard.css">
</head>
<body>

<div class="container">
    <header class="header">
        <h1>Seller Dashboard</h1>
        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>
    </header>

    <main class="main-content">
        <h2>Welcome to the Seller Dashboard!</h2>
        <p>Here you can view your orders, respond to customer inquiries, and manage your account settings.</p>

        <div class="card-container">
            <div class="card">
                <h3>View Orders</h3>
                <p>Check and process incoming orders.</p>
                <a href="view_orders.php" class="btn">View Orders</a>
            </div>
            <div class="card">
                <h3>Customer Inquiries</h3>
                <p>Respond to customer queries and concerns.</p>
                <a href="customer_inquiries.php" class="btn">Check Inquiries</a>
            </div>
            <div class="card">
                <h3>Settings</h3>
                <p>Update your profile and account settings.</p>
                <a href="seller_settings.php" class="btn">Account Settings</a>
            </div>
        </div>
    </main>
</div>

</body>
</html>
