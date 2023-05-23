<?php
// Include the necessary files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

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

    // Prepare the query
    $query = "INSERT INTO stocks (symbol, name) VALUES (:symbol, :name)";
    $params = [
        ':symbol' => $symbol,
        ':name' => $name
    ];

    // Execute the query to insert the stock
    executeQuery($query, $params);

    // Get the last inserted stock ID
    $stockId = getLastInsertId();

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
}
?>
