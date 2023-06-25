<?php
// Include the necessary files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Start a session
session_start();

// Check if the user is logged in and has admin role
if (!isLoggedIn() || $_SESSION['role'] !== 'admin') {
    // Redirect to the login page or any other page as needed
    header('Location: ../auth/login.php');
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the input values from the form
    $symbol = $_POST['symbol'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    // Validate the input values
    if (empty($symbol) || empty($name) || empty($price)) {
        // Handle validation error, such as displaying an error message or redirecting back to the form with an error parameter
        header('Location: addStock.php?error=1');
        exit();
    }

    // Add the stock to the database
    $success = addStock($symbol, $name, $price);

    if ($success) {
        // Stock added successfully
        // Redirect to the stock list page or any other page as needed
        header('Location: adminDashboard.php');
        exit();
    } else {
        // Failed to add stock
        // Handle the failure, such as displaying an error message or redirecting back to the form with an error parameter
        header('Location: addStock.php?error=2');
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Stock</title>
    <!-- Include your CSS file -->
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <!-- Include the header file -->
    <?php include '../templates/adminHeader.php'; ?>

    <div class="container">
        <h1>Add Stock</h1>

        <!-- Add stock form -->
        <form method="POST" action="">
            <label for="symbol">Symbol:</label>
            <input type="text" name="symbol" id="symbol" required>

            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>

            <label for="price">Price:</label>
            <input type="number" name="price" id="price" step="0.01" required>

            <button type="submit">Add Stock</button>
        </form>

        <!-- Link to go back to stock list or any other page as needed -->
        <p><a href="adminDashboard.php">Back to Dashboard</a></p>
    </div>

    <!-- Include the footer file -->
    <?php include '../templates/footer.php'; ?>
</body>
</html>
