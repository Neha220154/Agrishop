<?php
// Include database connection file
include('db.php');

// Start the session for flash messages
session_start();

// Check if form is submitted
if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['role'])) {
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];

    // Basic validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "Invalid email format.";
        header("Location: update_seller_form.php?id=$id");
        exit;
    }

    // Prepare the UPDATE query
    $query = "UPDATE seller SET name = ?, email = ?, role = ? WHERE id = ?";
    
    if ($stmt = mysqli_prepare($conn, $query)) {
        // Bind the parameters (string, string, string, integer)
        mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $role, $id);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['message'] = "Seller updated successfully.";
            header("Location: manage_sellers_.php");
            exit;
        } else {
            $_SESSION['message'] = "Error: Could not update the seller.";
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['message'] = "Error: Could not prepare the update statement.";
    }
} else {
    $_SESSION['message'] = "All fields are required!";
}

// Close the database connection
mysqli_close($conn);

// Redirect back to manage_sellers_.php
header("Location: manage_sellers_.php");
exit;
?>
