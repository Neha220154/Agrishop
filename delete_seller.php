<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: admin-login.php");
    exit();
}

$conn = new mysqli('localhost', 'neha@gmail.com', 'neha123', 'agrishop');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM seller WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: manage_sellers.php");
        exit();
    } else {
        echo "Error deleting seller: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
