<!-- line_chart.php -->
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

// Fetch the data for the line chart
$query = "SELECT created_at, SUM(quantity * price) AS total_value
          FROM transactions
          WHERE user_id = :userId
          GROUP BY created_at";
$params = [':userId' => $userId];
$chartData = fetchMultipleRows($query, $params);

// Convert the data to JSON format
$jsonData = json_encode($chartData);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Stock Portfolio Management System - Line Chart</title>
    <!-- Include your CSS and JavaScript files -->
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@latest"></script>
    <script src="../js/line_chart.js"></script>
</head>
<body>
    <!-- Include the header file -->
    <?php include '../templates/header.php'; ?>

    <div class="container">
        <h2>Line Chart</h2>

        <div id="lineChart"></div>
    </div>

    <!-- Include the footer file -->
    <?php include '../templates/footer.php'; ?>

    <script>
        // Initialize the line chart with the fetched data
        var lineData = <?php echo $jsonData; ?>;
        createLineChart(lineData);
    </script>
</body>
</html>
