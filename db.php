<?php
$host = "localhost"; // Change if necessary
$user = "root"; // Your database username
$pass = ""; // Your database password
$dbname = "agrishop"; // Your database name

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
