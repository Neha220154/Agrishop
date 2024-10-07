<?php
session_start();
require('db.php'); // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: seller_login.php");
    exit();
}

// Get the seller's email from the session
$seller_email = $_SESSION['email'];

// Fetch the seller's details from the database
$query = "SELECT id, name, email, role, status FROM seller WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $seller_email);
$stmt->execute();
$result = $stmt->get_result();

$seller = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $status = $_POST['status'];

    // Validate inputs
    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // Hash the password if it's being changed
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $update_query = "UPDATE seller SET name = ?, email = ?, password = ?, status = ? WHERE email = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("sssss", $name, $email, $hashed_password, $status, $seller_email);
        } else {
            // Update without changing the password
            $update_query = "UPDATE seller SET name = ?, email = ?, status = ? WHERE email = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("ssss", $name, $email, $status, $seller_email);
        }

        if ($stmt->execute()) {
            $success_message = "Profile updated successfully!";
        } else {
            $error_message = "Error updating profile: " . $stmt->error;
        }
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Settings</title>
    <link rel="stylesheet" href="setting-style.css"> <!-- Link to your CSS file -->
</head>
<body>

<div class="container">
    <header class="header">
        <h1>Seller Settings</h1>
        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>
    </header>

    <main class="main-content">
        <h2>Profile Information</h2>

        <?php if (isset($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <?php if (isset($success_message)): ?>
            <div class="success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <form action="seller_setting.php" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($seller['name']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($seller['email']); ?>" required>

            <label for="password">New Password:</label>
            <input type="password" id="password" name="password" placeholder="Leave blank if not changing">

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Leave blank if not changing">

            <label for="status">Status:</label>
            <select id="status" name="status" required>
                <option value="Active" <?php if ($seller['status'] == 'Active') echo 'selected'; ?>>Active</option>
                <option value="Inactive" <?php if ($seller['status'] == 'Inactive') echo 'selected'; ?>>Inactive</option>
            </select>

            <input type="submit" value="Update Profile">
        </form>
    </main>
</div>

</body>
</html>
