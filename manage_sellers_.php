<?php
// Include database connection file
include('db.php');

// Handle Create/Update/Delete actions
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'add') {
        // Add a new seller
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Secure hashing
        $role = $_POST['role'];

        // Insert into database
        $query = "INSERT INTO seller (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
        if (mysqli_query($conn, $query)) {
            echo "New seller added successfully.";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } elseif ($_POST['action'] == 'update') {
        // Update an existing seller
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        // Update query
        $query = "UPDATE seller SET name='$name', email='$email', role='$role' WHERE id=$id";
        if (mysqli_query($conn, $query)) {
            echo "Seller updated successfully.";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } elseif ($_POST['action'] == 'delete') {
        // Delete a seller
        $id = $_POST['id'];

        // Delete query
        $query = "DELETE FROM seller WHERE id=$id";
        if (mysqli_query($conn, $query)) {
            echo "Seller deleted successfully.";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

// Fetch all sellers
$sellers = mysqli_query($conn, "SELECT * FROM seller");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Sellers</title>
    <link rel="stylesheet" href="manage_sellers.css">

</head>
<body>
    <h2>Manage Sellers</h2>

    <!-- Add Seller Form -->
    <form method="post" action="manage_sellers_.php">
        <input type="hidden" name="action" value="add">
        <label>Name:</label><br>
        <input type="text" name="name"placeholder="enter your name" required><br>
        <label>Email:</label><br>
        <input type="email" name="email"placeholder="enter your email" required><br>
        <label>Password:</label><br>
        <input type="password" name="password"placeholder="enter your password" required><br>
        <label>Confirm Password:</label><br>
        <input type="password" name="confirm_password"placeholder="enter eonfirm password" required><br>
        <label>Role:</label><br>
        <select name="role">
            <option value="seller">Seller</option>
            <option value="admin">Admin</option>
        </select><br><br>
        <input type="submit" value="Add Seller">
    </form>

    <hr>

    <!-- Sellers List -->
    <h3>Seller List</h3>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($seller = mysqli_fetch_assoc($sellers)) { ?>
                <tr>
                    <td><?php echo $seller['id']; ?></td>
                    <td><?php echo $seller['name']; ?></td>
                    <td><?php echo $seller['email']; ?></td>
                    <td><?php echo $seller['role']; ?></td>
                    <td>
                        <form method="post" action="manage_sellers_.php" style="display:inline-block;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $seller['id']; ?>">
                            <input type="submit" value="Delete" onclick="return confirm('Are you sure you want to delete this seller?');">
                        </form>
                        
                        <!-- Update Button -->
                        <form method="post" action="update_seller_form.php" style="display:inline-block;">
                            <input type="hidden" name="id" value="<?php echo $seller['id']; ?>">
                            <input type="submit" value="Update">
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</body>
</html>
