<?php
session_start();
require 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT password FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['email'] = $email;
            header("Location: admin_dashboard.php"); // Redirect to admin dashboard
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Invalid email or password.";
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
    <title>Admin Login</title>
    <style>
        /* styles.css */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-image:url('admin-image.jpg');
    background-attachment: fixed;
    background-size: cover;
}

form {
    width: 300px;
    background: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    color: #333;
    margin-bottom: 20px; /* Space between the heading and form */
}

label {
    display: block;
    margin-bottom: 5px;
    color: #555;
}

input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
}

button {
    width: 100%;
    padding: 10px;
    background-color: #5cb85c;
    border: none;
    border-radius: 4px;
    color: white;
    font-size: 16px;
    cursor: pointer;
}

button:hover {
    background-color: #4cae4c;
}

p {
    text-align: center;
    color: red; /* For error messages */
}

    </style>
</head>
<body>
    <h2>Admin Login</h2>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
    <form method="POST" action="">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email"placeholder="enter youe email" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password"placeholder="enter your password" required>
        <br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
