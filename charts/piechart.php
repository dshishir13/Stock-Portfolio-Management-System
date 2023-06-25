<?php
// Fetch the portfolio data
$query = "SELECT s.symbol, 
            SUM(CASE WHEN t.transaction_type = 'buy' THEN t.quantity ELSE -t.quantity END) AS net_quantity,
            AVG(CASE WHEN t.transaction_type = 'buy' THEN t.quantity ELSE -t.quantity END * t.price / CASE WHEN t.transaction_type = 'buy' THEN t.quantity ELSE -t.quantity END) AS avg_buying_price,
            SUM(CASE WHEN t.transaction_type = 'buy' THEN t.quantity ELSE -t.quantity END * t.price) AS total_value
          FROM stocks s
          INNER JOIN transactions t ON s.id = t.stock_id
          WHERE t.user_id = :userId
          GROUP BY s.symbol";
$params = [':userId' => $userId];
$portfolio = fetchMultipleRows($query, $params);

// Prepare the data for the pie chart
$labels = [];
$data = [];

foreach ($portfolio as $row) {
    $labels[] = $row['symbol'];
    $data[] = $row['total_value'];
}

// Convert the data to JSON format
$labelsJson = json_encode($labels);
$dataJson = json_encode($data);
?>

<div style="width: 400px; height: 400px; margin: 0 auto;">
    <canvas id="pieChart"></canvas>
</div>

<!-- Include the Chart.js library using CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Initialize the pie chart -->
<script>
    var labels = <?php echo $labelsJson; ?>;
    var data = <?php echo $dataJson; ?>;

    var ctx = document.getElementById('pieChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    // Add more colors as needed
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'right'
            }
        }
    });
</script>
