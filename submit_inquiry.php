<?php
session_start();

require('db.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input
    $customer_name = trim($_POST['customer_name']);
    $customer_email = trim($_POST['customer_email']);
    $inquiry = trim($_POST['inquiry']);

    // Basic validation
    if (empty($customer_name) || empty($customer_email) || empty($inquiry)) {
        echo "All fields are required!";
        exit();
    }

    // Prepare SQL statement to prevent SQL injection
    $query = "INSERT INTO inquiries (customer_name, customer_email, inquiry, status, seller_email) VALUES (?, ?, ?, 'Pending', ?)";
    $stmt = $conn->prepare($query);

    // Assuming you have a way to get the seller's email. This can be set dynamically or retrieved from the session.
    $seller_email = 'seller@example.com'; // Change this to the appropriate value

    $stmt->bind_param("ssss", $customer_name, $customer_email, $inquiry, $seller_email);

    if ($stmt->execute()) {
        echo "Inquiry submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
