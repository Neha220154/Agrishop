<?php
// Include database connection file
include('db.php');
session_start();

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Fetch the user data
    $query = "SELECT * FROM users WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        
        // Check if user exists
        if (!$user) {
            $_SESSION['message'] = "User not found.";
            header("Location: manage_users.php");
            exit;
        }
        
        mysqli_stmt_close($stmt);
    }
} else {
    $_SESSION['message'] = "Invalid request.";
    header("Location: manage_users.php");
    exit;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <link rel="stylesheet" href="update-user.css">
</head>
<body>

    <h2>Update User</h2>

    <form method="post" action="update_user.php">
        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
        <label>Name:</label><br>
        <input type="text" name="name" value="<?php echo $user['name']; ?>" required><br>
        
        <label>Email:</label><br>
        <input type="email" name="email" value="<?php echo $user['email']; ?>" required><br>
        
        <label>Role:</label><br>
        <select name="role" required>
            <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>User</option>
            <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
        </select><br><br>

        <input type="submit" value="Update User">
    </form>

</body>
</html>
