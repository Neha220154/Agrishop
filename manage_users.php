<?php
// Include database connection file
include('db.php');

// Start the session for flash messages
session_start();

// Fetch users from the database
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="manage-users.css">
</head>
<body>

    <h2>Manage Users</h2>

    <!-- Display messages -->
    <?php
    if (isset($_SESSION['message'])) {
        echo '<div class="message">' . $_SESSION['message'] . '</div>';
        unset($_SESSION['message']);
    }
    ?>

    <!-- User Table -->
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php while ($user = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $user['id']; ?></td>
            <td><?php echo $user['name']; ?></td>
            <td><?php echo $user['email']; ?></td>
            <td><?php echo $user['role']; ?></td>
            <td>
                <form method="post" action="update_user_form.php" style="display:inline-block;">
                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                    <input type="submit" value="Edit">
                </form>
                <form method="post" action="delete_user.php" style="display:inline-block;">
                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                    <input type="submit" value="Delete" onclick="return confirm('Are you sure you want to delete this user?');">
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>

    <h3>Add New User</h3>
    <form method="post" action="add_user.php">
        <label>Name:</label><br>
        <input type="text" name="name"placeholder="enter your name" required><br>
        
        <label>Email:</label><br>
        <input type="email" name="email"placeholder="enter your email" required><br>
        
        <label>Password:</label><br>
        <input type="password" name="password"placeholder="enter your password" required><br>
        
        <label>Confirm Password:</label><br>
        <input type="password" name="confirm_password"placeholder="enter your confirm password" required><br>
        
        <label>Role:</label><br>
        <select name="role" required>
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select><br><br>

        <input type="submit" value="Add User">
    </form>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
