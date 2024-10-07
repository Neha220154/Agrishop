<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: admin-login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin-style.css">
</head>
<body>

<div class="container">
    <header class="header">
        <h1>Admin Dashboard</h1>
        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>
    </header>

    <nav class="sidebar">
        <ul>
            <li><a href="manage_sellers.php">Manage Sellers</a></li>
            <li><a href="manage_users.php">Manage Customers</a></li>
            <li><a href="manage_products.php">Manage Products</a></li>
            <li><a href="admin_settings.php">Settings</a></li>
        </ul>
    </nav>

    <main class="main-content">
        <h2>Welcome to the Admin Dashboard!</h2>
        <p>This is your admin dashboard where you can manage all aspects of the site.</p>

        <div class="card-container">
            <div class="card">
                <h3>Manage Sellers</h3>
                <p>View and manage all sellers.</p>
                <a href="manage_sellers.php" class="btn">Go to Sellers</a>
            </div>
            <div class="card">
                <h3>Manage Customers</h3>
                <p>View and manage all customers.</p>
                <a href="manage_users.php" class="btn">Go to Customers</a>
            </div>
            <div class="card">
                <h3>Manage Products</h3>
                <p>View and manage all products.</p>
                <a href="manage_products.php" class="btn">Go to Products</a>
            </div>
            <div class="card">
                <h3>Settings</h3>
                <p>Update your account settings.</p>
                <a href="admin_settings.php" class="btn">Go to Settings</a>
            </div>
        </div>
    </main>
</div>

</body>
</html>