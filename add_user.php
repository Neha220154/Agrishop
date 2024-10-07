<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include database connection file
include('db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize user inputs
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $role = trim($_POST['role']);

    // Validate inputs
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password) || empty($role)) {
        $_SESSION['message'] = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $_SESSION['message'] = "Passwords do not match.";
    } else {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the INSERT query
        $query = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
        
        if ($stmt = mysqli_prepare($conn, $query)) {
            // Bind parameters (string, string, string, string)
            mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $hashed_password, $role);
            
            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['message'] = "User added successfully.";
            } else {
                $_SESSION['message'] = "Error adding user: " . mysqli_error($conn);
            }

            // Close the statement
            mysqli_stmt_close($stmt);
        } else {
            $_SESSION['message'] = "Error preparing statement: " . mysqli_error($conn);
        }
    }
}

// Close the database connection
mysqli_close($conn);

// Redirect back to manage_users.php
header("Location: manage_users.php");
exit;
?>
