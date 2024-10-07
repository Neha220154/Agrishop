<?php
// Include database connection file
include('db.php');
session_start();

/*// Fetch the current admin's details if logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php"); // Redirect to login if not logged in
    exit();
}
*/
$admin_id = $_SESSION['admin_id'];

// Fetch current admin information
$query = "SELECT * FROM users WHERE id = ?"; // Assuming 'users' table contains admin info
if ($stmt = mysqli_prepare($conn, $query)) {
    mysqli_stmt_bind_param($stmt, "i", $admin_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $admin = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}

// Update settings if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    $error_message = "";

    // Validate inputs
    if (empty($name) || empty($email)) {
        $error_message = "Name and Email are required.";
    }

    // Check current password if new password is set
    if (!empty($new_password)) {
        if (empty($current_password) || empty($confirm_password)) {
            $error_message = "Please provide current password and confirm new password.";
        } elseif ($new_password !== $confirm_password) {
            $error_message = "New passwords do not match.";
        } else {
            // Verify current password
            if (!password_verify($current_password, $admin['password'])) {
                $error_message = "Current password is incorrect.";
            }
        }
    }

    if (empty($error_message)) {
        // Update admin information
        $query = "UPDATE users SET name = ?, email = ? WHERE id = ?";
        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, "ssi", $name, $email, $admin_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        // Update password if new password is set
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $query = "UPDATE users SET password = ? WHERE id = ?";
            if ($stmt = mysqli_prepare($conn, $query)) {
                mysqli_stmt_bind_param($stmt, "si", $hashed_password, $admin_id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
        }

        $_SESSION['message'] = "Settings updated successfully.";
        header("Location: admin_settings.php");
        exit();
    } else {
        $_SESSION['message'] = $error_message;
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <title>Admin Settings</title>
    <link rel="stylesheet" href="admin-settings.css">

</head>
<body>

<div class="container">
    <h2>Admin Settings</h2>
    
    <?php
    if (isset($_SESSION['message'])) {
        echo '<div class="message">' . $_SESSION['message'] . '</div>';
        unset($_SESSION['message']);
    }
    ?>

    <form action="admin_settings.php" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($admin['name']); ?>"placeholder="enter your name" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>"placeholder="enter your email" required><br><br>

        <label for="current_password">Current Password:</label>
        <input type="password" id="current_password" name="current_password" placeholder="enter your current password"><br><br>

        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password"placeholder="enter your new password"><br><br>

        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password"placeholder="enter confirm password"><br><br>

        <input type="submit" value="Update Settings">
    </form>
</div>
<a href="admin_dashboard.php">Back to Dashboard</a>

</body>
</html>
