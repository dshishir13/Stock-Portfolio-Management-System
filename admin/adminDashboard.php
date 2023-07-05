<?php
// Include the necessary files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Start a session
session_start();


// Check if the user is logged in as an admin
if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Redirect to the login page if not logged in as an admin
    header('Location: ../auth/login.php');
    exit();
}

// Get the admin user ID
$adminUserId = $_SESSION['user_id'];

// Get additional admin data from the database if needed

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <!-- Include your CSS file -->
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <!-- Include the header file -->
    <?php include '../templates/adminHeader.php'; ?>

    <div class="container">
        <h1>Welcome, Admin</h1>

        <!-- View Users Data -->
        <h2 id="users">Users Data</h2>
        <?php
        // Fetch the users data
        $query = "SELECT * FROM users";
        $users = fetchMultipleRows($query);

        if (!empty($users)) {
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>User ID</th>';
            echo '<th>Username</th>';
            echo '<th>Email</th>';
            // Add other user data columns if needed
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            foreach ($users as $user) {
                echo '<tr>';
                echo '<td>' . $user['id'] . '</td>';
                echo '<td>' . $user['username'] . '</td>';
                echo '<td>' . $user['email'] . '</td>';
                // Add other user data columns if needed
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p>No users found.</p>';
        }
        ?>

        <!-- View Stocks Data -->
        <h2>Stocks Data</h2>
        <?php
        // Fetch the stocks data
        $query = "SELECT * FROM stocks";
        $stocks = fetchMultipleRows($query);

        if (!empty($stocks)) {
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Stock ID</th>';
            echo '<th>Symbol</th>';
            echo '<th>Name</th>';
            echo '<th>Price</th>';
            // Add other stock data columns if needed
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            foreach ($stocks as $stock) {
                echo '<tr>';
                echo '<td>' . $stock['id'] . '</td>';
                echo '<td>' . $stock['symbol'] . '</td>';
                echo '<td>' . $stock['name'] . '</td>';
                echo '<td>' . $stock['price'] . '</td>';
                // Add other stock data columns if needed
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p>No stocks found.</p>';
        }
        ?>
    </div>
    <div class="container">
        <a href="addStock.php">Add Stocks</a>
        <a href="updateStock.php">Update Stocks</a>
    </div>

    <!-- Include the footer file -->
    <?php include '../templates/footer.php'; ?>
</body>
</html>
