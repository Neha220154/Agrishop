<?php
session_start();
require('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inquiry_id = $_POST['inquiry_id'];
    $response = trim($_POST['response']);

    // Basic validation
    if (empty($response)) {
        echo "Response cannot be empty!";
        exit();
    }

    // Prepare SQL statement to update the inquiry
    $query = "UPDATE inquiries SET status = 'Responded', response = ? WHERE inquiry_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $response, $inquiry_id);

    if ($stmt->execute()) {
        echo "Response submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    header("Location: customer_inquiries.php");
    exit();
}
?>
