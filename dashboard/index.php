<?php
// index.php

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

// Get the current user ID
$userId = getCurrentUserId();

// Fetch user details from the database
$query = "SELECT * FROM users WHERE id = :id";
$params = [':id' => $userId];
$user = fetchSingleRow($query, $params);

// Check if user details were fetched successfully
if (!$user || !is_array($user)) {
    // Handle the error, e.g., display an error message or redirect to an error page
    // For example:
    echo "Error retrieving user details.";
    exit();
}

// Fetch user's portfolio details from the database
$query = "SELECT * FROM stocks INNER JOIN transactions ON stocks.id = transactions.stock_id WHERE user_id = :userId";
$params = [':userId' => $userId];
$portfolio = fetchMultipleRows($query, $params);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Stock Portfolio Management System - Dashboard</title>
    <!-- Include your CSS and JavaScript files -->
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/script.js"></script>
</head>
<body>
    <!-- Include the header file -->
    <?php include '../templates/header.php'; ?>

    <div class="container">
        <h1>Welcome, <?php echo $user['username']; ?>!</h1>
        
        <!-- Display user's portfolio -->
        <h2>Portfolio</h2>
        <?php if (count($portfolio) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Stock Symbol</th>
                        <th>Stock Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Transaction Type</th>
                        <th>Transaction Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($portfolio as $row): ?>
                        <tr>
                            <td><?php echo $row['symbol']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td><?php echo $row['price']; ?></td>
                            <td><?php echo $row['transaction_type']; ?></td>
                            <td><?php echo $row['created_at']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No stocks in the portfolio.</p>
        <?php endif; ?>

        <a href="../auth/logout.php">Logout</a>
    </div>

    <!-- Include the footer file -->
    <?php include '../templates/footer.php'; ?>
</body>
</html>
