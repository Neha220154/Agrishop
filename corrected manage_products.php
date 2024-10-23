<?php
// Include database connection file
include('db.php');
session_start();

// Check if a product should be deleted
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete_query = "DELETE FROM product WHERE product_id = ?";
    
    if ($stmt = mysqli_prepare($conn, $delete_query)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $_SESSION['message'] = "Product deleted successfully.";
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['message'] = "Error deleting product.";
    }
}
// Check if a new product is being added
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input data
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']); // 12.32
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $image = mysqli_real_escape_string($conn, $_POST['image_url']);

    // Prepare the insert query
    $insert_query = "INSERT INTO `product`(`product_name`, `description`, `price`, `quantity`, `image`) 
                     VALUES ('$product_name', '$description', $price, $quantity, '$image')";

    // Execute the query
    if (mysqli_query($conn, $insert_query)) {
        $_SESSION['message'] = "Product inserted successfully.";
    } else {
        // Handle error
        $_SESSION['message'] = "Product inserted failed.";
    }
}


// Check for any session messages
$message = "";
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

// Fetch products from the database
$query = "SELECT * FROM product";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" type="text/css" href="manage-product.css"> <!-- Link to your CSS file -->
    <script>
        function convertImageToDataURL(event) {
            const file = event.target.files[0];
            console.log(file);
            const reader = new FileReader();
            reader.onload = function() {
                document.querySelector('input[name="image_url"]').value = reader.result;
            };
            reader.readAsDataURL(file);
        }
    </script>
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
                    <a href="edit_product.php?id=<?php echo $product['product_id']; ?>">Edit</a> |
                    <a href="?delete=<?php echo $product['product_id']; ?>" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>

        <h3>Add New Product</h3>
        <form method="POST" >
            <label>Product Name:</label><br>
            <input type="text" name="product_name" placeholder="Enter product name" required><br>

            <label>Description:</label><br>
            <textarea name="description" placeholder="Enter product description" required></textarea><br>

            <label>Price:</label><br>
            <input type="number" name="price" placeholder="Enter product price" required step="0.01"><br>

            <label>Quantity:</label><br>
            <input type="number" name="quantity" placeholder="Enter product quantity" required><br>

            <label>Image:</label><br>
            <input type="hidden" name="image_url" value=""/>
            <input type="file" name="image" accept="image/*" required onchange="convertImageToDataURL(event)"><br>

            <input type="submit" value="Add Product">
        </form>
        <a href="admin_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
