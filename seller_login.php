<?php
session_start();
require('db.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Prepare SQL statement to prevent SQL injection
    $query = "SELECT password FROM seller WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Password is correct, start a session
            $_SESSION['email'] = $email; // Store email in session
            echo "Login successful! Redirecting...";
            header("Location: seller_dashboard.php"); // Redirect to the dashboard
            exit();
            // Redirect or perform actions after login
        } else {
            $error_message = "Invalid email or password.";
        }
    } else {
        $error_message = "Invalid email or password.";
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="seller-style.css">
</head>
<body>
    <div class="login-container">
        <form action="seller_login.php" method="POST">
            <h2>Login</h2>
            <?php if (isset($error_message)): ?>
                <div class="error"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email"placeholder="enter your email" required><br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password"placeholder="enter your password" required><be><br>

            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
