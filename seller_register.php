<?php
session_start();
require('db.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? ''); // For verification only
    $role = $_POST['role'] ?? 'user'; // Default to 'user' if not set

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into sellers table
    $query = "INSERT INTO seller (name, email, password, role, status) 
              VALUES (?, ?, ?, ?, 'pending')";
    
    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);
    
    if ($stmt->execute()) {
        echo "Registration successful. Please wait for approval.";
    } else {
        echo "Error: " . $stmt->error;
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
    <title> Registration</title>
    <link rel="stylesheet" href="style-register.css">
</head>
<body>
    <div class="registration-container">
        <form action="seller_register.php" method="POST">
            <h2>Seller Registration</h2>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="seller">seller</option>
                <option value="user">user</option>
            </select>

            <input type="submit" value="Register">
        </form>
    </div>
</body>
</html>
