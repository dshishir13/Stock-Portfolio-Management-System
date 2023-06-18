<!-- index.php -->
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

// Get the user ID
$userId = getCurrentUserId();

// Fetch the user data
$query = "SELECT * FROM users WHERE id = :userId";
$params = [':userId' => $userId];
$user = fetchSingleRow($query, $params);

// Fetch the portfolio data
$query = "SELECT s.id, s.symbol, s.name, 
            SUM(CASE WHEN t.transaction_type = 'buy' THEN t.quantity ELSE -t.quantity END) AS net_quantity,
            AVG(CASE WHEN t.transaction_type = 'buy' THEN t.quantity ELSE -t.quantity END * t.price / CASE WHEN t.transaction_type = 'buy' THEN t.quantity ELSE -t.quantity END) AS avg_buying_price,
            SUM(CASE WHEN t.transaction_type = 'buy' THEN t.quantity ELSE -t.quantity END * t.price) AS total_value
          FROM stocks s
          INNER JOIN transactions t ON s.id = t.stock_id
          WHERE t.user_id = :userId
          GROUP BY s.id, s.symbol, s.name";

$params = [':userId' => $userId];
$portfolio = fetchMultipleRows($query, $params);

// Fetch the transaction data
$query2 = "SELECT s.symbol, s.name, t.quantity, t.price, t.transaction_type, t.created_at
          FROM stocks s
          INNER JOIN transactions t ON s.id = t.stock_id
          WHERE t.user_id = :userId
          ORDER BY t.created_at DESC";
$params2 = [':userId' => $userId];
$transactions = fetchMultipleRows($query2, $params2);

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
        <h1>Welcome, <?php echo isset($user['username']) ? $user['username'] : ''; ?>!</h1>

        <!-- Display user's Portfolio -->
        <h2>Portfolio</h2>
        <?php if (!empty($portfolio)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Stock Symbol</th>
                        <th>Stock Name</th>
                        <th>Quantity</th>
                        <th>Average Buying Price</th>
                        <th>Total Value</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($portfolio as $row): ?>
                        <tr>
                            <td><?php echo $row['symbol']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['net_quantity']; ?></td>
                            <td><?php echo $row['avg_buying_price']; ?></td>
                            <td><?php echo $row['total_value']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No stocks in the portfolio.</p>
        <?php endif; ?>


        <!-- Display user's Transactions -->
        <h2>Transactions</h2>
        <?php if (!empty($transactions)): ?>
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
                    <?php foreach ($transactions as $row): ?>
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
            <p>No Transactions.</p>
        <?php endif; ?>


        <!-- Add Transaction button -->
        <a href="../includes/addPortfolio.php" class="add-transaction-btn">Add Transaction</a>

        <a href="../auth/logout.php" class="logout-btn">Logout</a>
    </div>

    <!-- Include the footer file -->
    <?php include '../templates/footer.php'; ?>
</body>
</html>
