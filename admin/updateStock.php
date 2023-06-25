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

// Get the stock ID from the URL parameter
$stockId = $_GET['id'];

// Retrieve the stock details from the database
$stock = getStockById($stockId);

// Check if the stock exists
if (!$stock) {
    // Handle the case when the stock does not exist, such as displaying an error message or redirecting to the stock list page
    header('Location: stockList.php');
    exit();
}

// Check if the form is submitted for deleting the stock
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    // Delete the stock from the database
    $success = deleteStock($stockId);

    if ($success) {
        // Stock deleted successfully
        // Redirect to the stock list page or any other page as needed
        header('Location: adminDashboard.php');
        exit();
    } else {
        // Failed to delete stock
        // Handle the failure, such as displaying an error message or redirecting back to the form with an error parameter
        header("Location: updateStock.php?id=$stockId&error=3");
        exit();
    }
}

// Check if the form is submitted for updating the stock
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    // Get the updated values from the form
    $symbol = $_POST['symbol'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    // Validate the input values
    if (empty($symbol) || empty($name) || empty($price)) {
        // Handle validation error, such as displaying an error message or redirecting back to the form with an error parameter
        header("Location: updateStock.php?id=$stockId&error=1");
        exit();
    }

    // Update the stock in the database
    $success = updateStock($stockId, $symbol, $name, $price);

    if ($success) {
        // Stock updated successfully
        // Redirect to the stock list page or any other page as needed
        header('Location: stockList.php');
        exit();
    } else {
        // Failed to update stock
        // Handle the failure, such as displaying an error message or redirecting back to the form with an error parameter
        header("Location: updateStock.php?id=$stockId&error=2");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Stock</title>
    <!-- Include your CSS file -->
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <!-- Include the header file -->
    <?php include '../templates/header.php'; ?>

    <div class="container">
        <h1>Update Stock</h1>

        <!-- Update stock form -->
        <form method="POST" action="">
            <label for="symbol">Symbol:</label>
            <input type="text" name="symbol" id="symbol" value="<?php echo $stock['symbol']; ?>" required>

            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="<?php echo $stock['name']; ?>" required>

            <label for="price">Price:</label>
            <input type="number" name="price" id="price" step="0.01" value="<?php echo $stock['price']; ?>" required>

            <button type="submit" name="update">Update Stock</button>
        </form>

        <!-- Delete stock form -->
        <form method="POST" action="" onsubmit="return confirm('Are you sure you want to delete this stock?');">
            <input type="hidden" name="id" value="<?php echo $stock['id']; ?>">
            <button type="submit" name="delete">Delete Stock</button>
        </form>

        <!-- Link to go back to stock list or any other page as needed -->
        <p><a href="stockList.php">Back to Stock List</a></p>
    </div>

    <!-- Include the footer file -->
    <?php include '../templates/footer.php'; ?>
</body>
</html>
