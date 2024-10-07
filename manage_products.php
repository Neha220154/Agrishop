<?php
// Include database connection file
include('db.php');
session_start();

// Check if a product should be deleted
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete_query = "DELETE FROM products WHERE product_id = ?";
    
    if ($stmt = mysqli_prepare($conn, $delete_query)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $_SESSION['message'] = "Product deleted successfully.";
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['message'] = "Error deleting product.";
    }
}

// Check for any session messages
$message = "";
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

// Fetch products from the database
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" type="text/css" href="manage-product.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="container">
        <h2>Manage Products</h2>

        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <table>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
            <?php while ($product = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $product['product_id']; ?></td>
                <td><?php echo $product['product_name']; ?></td>
                <td><?php echo $product['description']; ?></td>
                <td><?php echo $product['price']; ?></td>
                <td><?php echo $product['quantity']; ?></td>
                <td>
                    <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['product_name']; ?>" width="100">
                </td>
                <td>
                    <a href="update_product.php?id=<?php echo $product['product_id']; ?>">Edit</a> |
                    <a href="?delete=<?php echo $product['product_id']; ?>" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>

        <h3>Add New Product</h3>
        <form method="POST" action="manage_product.php" enctype="multipart/form-data">
            <label>Product Name:</label><br>
            <input type="text" name="product_name"placeholder="enter product name" required><br>

            <label>Description:</label><br>
            <textarea name="description"placeholder="enter product description" required></textarea><br>

            <label>Price:</label><br>
            <input type="number" name="price"placeholder="enter productprice" required step="0.01"><br>

            <label>Quantity:</label><br>
            <input type="number" name="quantity"placeholder="enter product quantity" required><br>

            <label>Image:</label><br>
            <input type="file" name="image" accept="image/*" required><br>

            <input type="submit" value="Add Product">
        </form>
    </div>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
