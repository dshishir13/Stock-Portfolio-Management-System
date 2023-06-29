<?php
// Fetch the portfolio data
$query = "SELECT s.symbol, 
            SUM(CASE WHEN t.transaction_type = 'buy' THEN t.quantity ELSE -t.quantity END) AS net_quantity,
            SUM(CASE WHEN t.transaction_type = 'buy' THEN t.quantity ELSE -t.quantity END * t.price) AS total_value
          FROM stocks s
          INNER JOIN transactions t ON s.id = t.stock_id
          WHERE t.user_id = :userId
          GROUP BY s.symbol";
$params = [':userId' => $userId];
$portfolio = fetchMultipleRows($query, $params);

// Calculate the total value of the portfolio
$totalValue = 0;

foreach ($portfolio as $row) {
    $totalValue += $row['total_value'];
}

// Prepare the data for the bar chart
$labels = [];
$data = [];

foreach ($portfolio as $row) {
    $percentage = ($row['total_value'] / $totalValue) * 100;
    $labels[] = $row['symbol'];
    $data[] = $percentage;
}

// Convert the data to JSON format
$labelsJson = json_encode($labels);
$dataJson = json_encode($data);
?>

<div style="width: 400px; height: 400px; margin: 0 auto;">
    <canvas id="barChart"></canvas>
</div>

<!-- Include the Chart.js library using CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Initialize the bar chart -->
<script>
    var labels = <?php echo $labelsJson; ?>;
    var data = <?php echo $dataJson; ?>;

    var ctx = document.getElementById('barChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Stock Percentage',
                data: data,
                backgroundColor: 'rgba(75, 192, 192, 0.7)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
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
                        text: 'Stock Symbol'
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Percentage'
                    },
                    beginAtZero: true,
                    max: 100,
                    callback: function(value) {
                        return value + '%';
                    }
                }
            }
        }
    });
</script>
