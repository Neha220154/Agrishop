<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);


if (!isset($_SESSION['email'])) {
    header("Location: seller_login.php");
    exit();
}

require('db.php'); // Include your database connection file

// Retrieve seller's email from the session
$seller_email = $_SESSION['email'];

// SQL query to fetch customer inquiries for the seller
$query = "SELECT * FROM inquiries WHERE seller_email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $seller_email);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Inquiries</title>
    <link rel="stylesheet" href="seller-style.css"> <!-- Link to your CSS file -->
</head>
<body>

<div class="container">
    <header class="header">
        <h1>Customer Inquiries</h1>
        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>
    </header>

    <main class="main-content">
        <h2>Inquiries from Customers</h2>

        <?php if ($result->num_rows > 0): ?>
            <table class="inquiries-table">
                <thead>
                    <tr>
                        <th>Inquiry ID</th>
                        <th>Customer Name</th>
                        <th>Email</th>
                        <th>Inquiry</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['inquiry_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['customer_email']); ?></td>
                            <td><?php echo htmlspecialchars($row['inquiry']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td>
                                <a href="respond_inquiry.php?id=<?php echo $row['inquiry_id']; ?>" class="btn">Respond</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No inquiries found.</p>
        <?php endif; ?>

        <?php
        $stmt->close();
        $conn->close();
        ?>
    </main>
</div>

</body>
</html>
