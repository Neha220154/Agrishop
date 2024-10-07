<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: seller_login.php");
    exit();
}

require('db.php');

if (isset($_GET['id'])) {
    $inquiry_id = $_GET['id'];

    // Retrieve the inquiry details
    $query = "SELECT * FROM inquiries WHERE inquiry_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $inquiry_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $inquiry = $result->fetch_assoc();
    } else {
        echo "Inquiry not found.";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Respond to Inquiry</title>
    <link rel="stylesheet" href="seller-style.css">
</head>
<body>

<div class="container">
    <header class="header">
        <h1>Respond to Inquiry</h1>
        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>
    </header>

    <main class="main-content">
        <h2>Inquiry Details</h2>
        <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($inquiry['customer_name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($inquiry['customer_email']); ?></p>
        <p><strong>Inquiry:</strong> <?php echo htmlspecialchars($inquiry['inquiry']); ?></p>

        <h3>Your Response</h3>
        <form action="submit_response.php" method="POST">
            <input type="hidden" name="inquiry_id" value="<?php echo $inquiry['inquiry_id']; ?>">
            <textarea name="response" rows="5" required></textarea><br>
            <input type="submit" value="Submit Response">
        </form>
    </main>
</div>

</body>
</html>
