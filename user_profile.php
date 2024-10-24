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

// Fetch user data
$email = $_SESSION['email'];
$stmt = $pdo->prepare("SELECT id, name, email, role FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $password = $_POST['password']; // Handle password hashing on update if needed
    $stmt = $pdo->prepare("UPDATE users SET name = ?, password = ? WHERE email = ?");
    $stmt->execute([$name, password_hash($password, PASSWORD_DEFAULT), $email]);
    $message = "Profile updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="user-profile.css">
</head>
<body>

<div class="container">
    <header class="header">
        <h1>User Profile</h1>
        <ul>
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
        <h2>Edit Your Profile</h2>
        
        <?php if (isset($message)) : ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Leave blank to keep current password">

            <button type="submit" class="btn">Update Profile</button>
        </form>
    </main>
</div>

</body>
</html>
