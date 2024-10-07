<?php
// Include database connection file
include('db.php');
session_start();

// Check if the product ID is set
if (!isset($_GET['product_id'])) {
    header("Location: manage_product.php"); // Redirect if no product ID is provided
    exit();
}

$product_id = $_GET['product_id'];

// Fetch the product details from the database
$query = "SELECT * FROM products WHERE product_id = ?";
if ($stmt = mysqli_prepare($conn, $query)) {
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result->num_rows == 0) {
        $_SESSION['message'] = "Product not found.";
        header("Location: manage_product.php");
        exit();
    }

    $product = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize user inputs
    $product_name = trim($_POST['product_name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $quantity = trim($_POST['quantity']);
    $target_file = $_POST['existing_image']; // Preserve existing image path

    // Check if an image is being uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image = $_FILES['image'];
        
        // Define the target directory and file name
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image["name"]);
        
        // Allow certain file formats
        $allowed_formats = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = pathinfo($target_file, PATHINFO_EXTENSION);
        
        if (!in_array(strtolower($file_extension), $allowed_formats)) {
            $_SESSION['message'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        } elseif ($image["size"] > 2000000) { // Limit file size to 2MB
            $_SESSION['message'] = "Sorry, your file is too large.";
        } else {
            // Move uploaded file to target directory
            if (move_uploaded_file($image["tmp_name"], $target_file)) {
                // File uploaded successfully
            } else {
                $_SESSION['message'] = "Sorry, there was an error uploading your file.";
            }
        }
    }

    // Prepare the UPDATE query
    $query = "UPDATE products SET product_name = ?, description = ?, price = ?, quantity = ?, image = ? WHERE product_id = ?";
    
    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "ssdisi", $product_name, $description, $price, $quantity, $target_file, $product_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['message'] = "Product updated successfully.";
            header("Location: manage_product.php");
            exit();
        } else {
            $_SESSION['message'] = "Error updating product.";
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['message'] = "Error preparing statement.";
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!-- HTML form for updating product -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <title>Update Product</title>
</head>
<body>

<div class="container">
    <h2>Update Product</h2>
    
    <?php
    if (isset($_SESSION['message'])) {
        echo '<div class="message">' . $_SESSION['message'] . '</div>';
        unset($_SESSION['message']);
    }
    ?>

    <form action="update_product.php?product_id=<?php echo $product['product_id']; ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="existing_image" value="<?php echo $product['image']; ?>">
        
        <label for="product_name">Product Name:</label>
        <input type="text" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required step="0.01">

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>" required>

        <label for="image">Upload Image (optional):</label>
        <input type="file" id="image" name="image">

        <input type="submit" value="Update Product">
    </form>
</div>

</body>
</html>
