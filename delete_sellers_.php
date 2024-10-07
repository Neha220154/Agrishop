<?php
// Include database connection file
include('db.php');

// Check if 'id' is passed through GET or POST
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Prepare the DELETE query
    $query = "DELETE FROM seller WHERE id = ?";
    
    // Prepare statement to prevent SQL injection
    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $id); // 'i' stands for integer type

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            // Redirect back to the manage_sellers_.php page with success message
            header("Location: manage_sellers_.php?message=Seller+deleted+successfully");
        } else {
            echo "Error: Could not execute the delete query.";
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Error: Could not prepare the delete statement.";
    }
} else {
    echo "Invalid Request. No seller ID specified.";
}

// Close the database connection
mysqli_close($conn);
?>
