<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require('db.php'); // Ensure this file contains correct database connection code

/*// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}*/

// Initialize variables
$product_id = $name = $price = $quantity = "";
$message = "";

// Check if a product ID is provided
if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']); // Sanitize the product ID

    // Fetch product details
    $query = "SELECT * FROM products WHERE product_id = $product_id LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $product = mysqli_fetch_assoc($result);
        if ($product) {
            // Ensure the correct column names are used
            $name = htmlspecialchars($product['product_name']);
            $price = htmlspecialchars($product['price']);
            $quantity = htmlspecialchars($product['quantity']);
        } else {
            $message = "Product not found.";
        }
    } else {
        die("Database query failed: " . mysqli_error($conn));
    }
}

// Update product details if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Use mysqli_real_escape_string for user inputs
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);

    // Update the product in the database
    $update_query = "UPDATE products SET product_name = '$name', price = '$price', quantity = '$quantity' WHERE product_id = $product_id";
    
    if (mysqli_query($conn, $update_query)) {
        $message = "Product updated successfully.";
    } else {
        $message = "Error updating product: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="edit-product.css"> 
</head>
<body>
    <h1>Edit Product</h1>
    
    <?php if ($message) { ?>
        <div><?php echo $message; ?></div>
    <?php } ?>
    
    <form action="edit_product.php?id=<?php echo $product_id; ?>" method="POST">
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $name; ?>" required><br><br>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" value="<?php echo $price; ?>" step="0.01" required><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" value="<?php echo $quantity; ?>" required><br><br>

        <input type="submit" value="Update Product">
    </form>

    <br>
    <a href="manage_products.php">Back to Manage Products</a>
    <br>
    <a href="logout.php">Logout</a>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
