<?php
// Include database connection file
include('db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize user inputs
    $product_name = trim($_POST['product_name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $quantity = trim($_POST['quantity']);
    $image = $_FILES['image'];

    // Validate inputs
    if (empty($product_name) || empty($description) || empty($price) || empty($quantity) || empty($image['name'])) {
        $_SESSION['message'] = "All fields are required.";
    } else {
        // Validate image upload
        $target_dir = "uploads/"; // Ensure this directory exists and is writable
        $target_file = $target_dir . basename($image["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $uploadOk = 1;

        // Check if image file is a actual image or fake image
        $check = getimagesize($image["tmp_name"]);
        if ($check === false) {
            $_SESSION['message'] = "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size (limit to 5MB)
        if ($image["size"] > 5000000) {
            $_SESSION['message'] = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            $_SESSION['message'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $_SESSION['message'] = "Sorry, your file was not uploaded.";
        } else {
            // If everything is ok, try to upload file
            if (move_uploaded_file($image["tmp_name"], $target_file)) {
                // Prepare the INSERT query
                $query = "INSERT INTO products (product_name, description, price, quantity, image) VALUES (?, ?, ?, ?, ?)";

                if ($stmt = mysqli_prepare($conn, $query)) {
                    // Bind parameters (string, string, double, integer, string)
                    mysqli_stmt_bind_param($stmt, "ssdss", $product_name, $description, $price, $quantity, $target_file);

                    // Execute the statement
                    if (mysqli_stmt_execute($stmt)) {
                        $_SESSION['message'] = "Product added successfully.";
                    } else {
                        $_SESSION['message'] = "Error adding product.";
                    }

                    // Close the statement
                    mysqli_stmt_close($stmt);
                } else {
                    $_SESSION['message'] = "Error preparing statement.";
                }
            } else {
                $_SESSION['message'] = "Sorry, there was an error uploading your file.";
            }
        }
    }

    // Close the database connection
    mysqli_close($conn);

    // Redirect back to manage_product.php
    header("Location: manage_products.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" type="text/css" href="manage-product.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="container">
        <h2>Add New Product</h2>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <label>Product Name:</label><br>
            <input type="text" name="product_name"placeholder="enter product name" required><br>

            <label>Description:</label><br>
            <textarea name="description"placeholder="enter product description" required></textarea><br>

            <label>Price:</label><br>
            <input type="number" name="price"placeholder="enter product price" required step="0.01"><br>
            <label>Quantity:</label><br>
            <input type="number" name="quantity"placeholder="enter quantity" required><br>

            <label>Image:</label><br>
            <input type="file" name="image" accept="image/*" required><br>

            <input type="submit" value="Add Product">
        </form>
        <a href="admin_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
