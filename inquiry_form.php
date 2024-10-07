<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Inquiry Form</title>
    <link rel="stylesheet" href="inquiry-style.css"> <!-- Link to your CSS file -->
</head>
<body>

<div class="container">
    <header>
        <h1>Customer Inquiry</h1>
    </header>

    <main>
        <form action="submit_inquiry.php" method="POST">
            <label for="customer_name">Name:</label>
            <input type="text" id="customer_name" name="customer_name"placeholder="enter your name" required><br><br>

            <label for="customer_email">Email:</label>
            <input type="email" id="customer_email" name="customer_email"placeholder="enter your email" required><br><br>

            <label for="inquiry">Your Inquiry:</label>
            <textarea id="inquiry" name="inquiry" rows="5"placeholder="enter your inquiry" required></textarea><br><br>

            <input type="submit" value="Submit Inquiry">
        </form>
    </main>
</div>

</body>
</html>
