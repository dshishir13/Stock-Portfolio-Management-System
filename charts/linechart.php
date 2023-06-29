<?php
require_once 'config.php';
require_once 'db.php';
require_once 'functions.php';

session_start();

if (!isLoggedIn()) {
    header('Location: ../auth/login.php');
    exit();
}

$userId = getCurrentUserId();

// Fetch the portfolio data
$query = "SELECT DATE_FORMAT(t.created_at, '%Y-%m-%d') AS date, 
            SUM(CASE WHEN t.transaction_type = 'buy' THEN t.quantity ELSE -t.quantity END * t.price) AS portfolio_value
          FROM transactions t
          INNER JOIN stocks s ON t.stock_id = s.id
          WHERE t.user_id = :userId
          GROUP BY DATE_FORMAT(t.created_at, '%Y-%m-%d')
          ORDER BY DATE_FORMAT(t.created_at, '%Y-%m-%d')";
$params = [':userId' => $userId];
$portfolioData = fetchMultipleRows($query, $params);

// Prepare the data for the line chart
$dates = [];
$values = [];

foreach ($portfolioData as $row) {
    $dates[] = $row['date'];
    $values[] = $row['portfolio_value'];
}

// Convert the data to JSON format
$datesJson = json_encode($dates);
$valuesJson = json_encode($values);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Portfolio Value - Stock Portfolio Management System</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include '../templates/header.php'; ?>

    <div class="container">
        <h1>Portfolio Value</h1>

        <div style="width: 800px; height: 400px; margin: 0 auto;">
            <canvas id="lineChart"></canvas>
        </div>
    </div>

    <?php include '../templates/footer.php'; ?>

    <script>
        var dates = <?php echo $datesJson; ?>;
        var values = <?php echo $valuesJson; ?>;

        var ctx = document.getElementById('lineChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Portfolio Value',
                    data: values,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    },
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Portfolio Value'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
