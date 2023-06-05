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

// Fetch the portfolio data
$query = "SELECT s.symbol, s.name, t.quantity, t.price, t.transaction_type, t.created_at
          FROM stocks s
          INNER JOIN transactions t ON s.id = t.stock_id
          WHERE t.user_id = :userId";
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
        <h1>Welcome, <?php echo isset($user['username']) ? $user['username'] : ''; ?>!</h1>
        
        <!-- Display user's portfolio -->
       <!-- portfolio.php -->

            <h2>Portfolio</h2>
            <?php if (!empty($portfolio)): ?>
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


        <!-- Add Transaction button -->
        <a href="../includes/addPortfolio.php" class="add-transaction-btn">Add Transaction</a>

        <a href="../auth/logout.php">Logout</a>
    </div>

    <!-- Include the footer file -->
    <?php include '../templates/footer.php'; ?>
</body>
</html>
