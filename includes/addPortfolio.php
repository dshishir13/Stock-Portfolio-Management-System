<?php
// Include the necessary files
require_once 'config.php';
require_once 'db.php';
require_once 'functions.php';

// Start a session
session_start();

// Check if the user is logged in
if (!isLoggedIn()) {
    // Redirect to the login page if the user is not logged in
    header('Location: ../auth/login.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $symbol = $_POST['symbol'];
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $transactionType = $_POST['transaction_type'];

    // Validate the form data (you can add your own validation logic here)

    // Prepare the query to insert the stock
    $query = "INSERT INTO stocks (symbol, name) VALUES (:symbol, :name)";
    $params = [
        ':symbol' => $symbol,
        ':name' => $name
    ];

    // Execute the query to insert the stock
    $stockId = executeQueryAndGetLastInsertId($query, $params);

    if ($stockId) {
        // Prepare the query to insert the transaction
        $query = "INSERT INTO transactions (user_id, stock_id, quantity, price, transaction_type) 
                  VALUES (:userId, :stockId, :quantity, :price, :transactionType)";
        $params = [
            ':userId' => getCurrentUserId(),
            ':stockId' => $stockId,
            ':quantity' => $quantity,
            ':price' => $price,
            ':transactionType' => $transactionType
        ];

        // Execute the query to insert the transaction
        executeQuery($query, $params);

        // Redirect to the dashboard or any other page you want
        header('Location: ../dashboard/index.php');
        exit();
    } else {
        // Handle the error, e.g., display an error message or redirect to an error page
        // For example:
        echo "Error adding portfolio.";
        exit();
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Add Portfolio - Stock Portfolio Management System</title>
    <!-- Include your CSS and JavaScript files -->
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/script.js"></script>
</head>
<body>
    <!-- Include the header file -->
    <?php include '../templates/header.php'; ?>

    <div class="container">
        <h1>Add Portfolio</h1>

        <form action="addPortfolio.php" method="POST">
            <label for="symbol">Stock Symbol:</label>
            <input type="text" id="symbol" name="symbol" required>

            <label for="name">Stock Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" required>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" required>

            <label for="transaction_type">Transaction Type:</label>
            <select id="transaction_type" name="transaction_type" required>
                <option value="buy">Buy</option>
                <option value="sell">Sell</option>
            </select>

            <button type="submit">Add Portfolio</button>
        </form>
    </div>

    <!-- Include the footer file -->
    <?php include '../templates/footer.php'; ?>
</body>
</html>
