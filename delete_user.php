<?php
// Include database connection file
include('db.php');
session_start();

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Prepare the DELETE query
    $query = "DELETE FROM users WHERE id = ?";
    
    if ($stmt = mysqli_prepare($conn, $query)) {
        // Bind the parameter (integer)
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['message'] = "User deleted successfully.";
        } else {
            $_SESSION['message'] = "Error deleting user.";
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['message'] = "Error preparing statement.";
    }
} else {
    $_SESSION['message'] = "Invalid request.";
}

// Close the database connection
mysqli_close($conn);

// Redirect back to manage_users.php
header("Location: manage_users.php");
exit;
?>
