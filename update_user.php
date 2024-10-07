<?php
// Include database connection file
include('db.php');
session_start();

// Assuming you fetch the user details to update
$id = $_GET['id']; // Get user ID from query string
$query = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// Close the statement
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <link rel="stylesheet" type="text/css" href="update-user.css">
</head>
<body>
    <div class="container">
        <h2>Update User</h2>
        
        <?php
        // Display message if exists
        if (isset($_SESSION['message'])) {
            echo '<div class="message">' . $_SESSION['message'] . '</div>';
            unset($_SESSION['message']);
        }
        ?>

        <form method="POST" action="update_user.php">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
            
            <label>Name:</label>
            <input type="text" name="name" value="<?php echo $user['name']; ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo $user['email']; ?>" required>

            <label>Password:</label>
            <input type="password" name="password" placeholder="Leave blank to keep current password">

            <label>Role:</label>
            <select name="role" required>
                <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>User</option>
                <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
            </select>

            <input type="submit" value="Update User">
        </form>
        <a href="admin_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
