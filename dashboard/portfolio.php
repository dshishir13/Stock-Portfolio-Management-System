<?php
// Include the necessary files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Check if the user is logged in
if (!isLoggedIn()) {
    // Redirect to the login page if the user is not logged in
    header('Location: login.php');
    exit();
}

// Get the current user ID
$userId = getCurrentUserId();

// Fetch user's portfolio details from the database
$query = "SELECT s.symbol, s.name, SUM(t.quantity) AS total_quantity, SUM(t.quantity * t.price) AS total_value
          FROM stocks s
          INNER JOIN transactions t ON s.id = t.stock_id
          WHERE t.user_id = :userId
          GROUP BY s.symbol, s.name";
$params = [':userId' => $userId];
$portfolio = fetchMultipleRows($query, $params);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Stock Portfolio Management System - Portfolio</title>
    <!-- Include your CSS and JavaScript files -->
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/script.js"></script>
</head>
<body>
    <!-- Include the header file -->
    <?php include '../templates/header.php'; ?>

    <div class="container">
        <h1>My Portfolio</h1>

        <!-- Display user's portfolio -->
        <?php if (count($portfolio) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Stock Symbol</th>
                        <th>Stock Name</th>
                        <th>Total Quantity</th>
                        <th>Total Value</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($portfolio as $row): ?>
                        <tr>
                            <td><?php echo $row['symbol']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['total_quantity']; ?></td>
                            <td><?php echo $row['total_value']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No stocks in the portfolio.</p>
        <?php endif; ?>
    </div>

    <!-- Include the footer file -->
    <?php include '../templates/footer.php'; ?>
</body>
</html>
