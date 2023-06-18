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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $symbol = $_POST['symbol'] ?? '';
    $name = $_POST['name'] ?? '';
    $quantity = $_POST['quantity'] ?? 0;
    $price = $_POST['price'] ?? 0;
    $transactionType = $_POST['transaction_type'] ?? '';

    if (empty($symbol) || empty($name) || empty($quantity) || empty($price) || empty($transactionType)) {
        $_SESSION['error'] = 'Please fill in all the fields.';
        header('Location: ../dashboard/addPortfolio.php');
        exit();
    }

    $existingStock = getStockBySymbolAndName($symbol, $name);

    if ($existingStock) {
        $stockId = $existingStock['id'];
    } else {
        $stockId = addStock($symbol, $name, $price);
    }

    addTransaction($userId, $stockId, $quantity, $price, $transactionType);

    header('Location: ../dashboard/index.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Portfolio - Stock Portfolio Management System</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="../js/script.js"></script>
</head>
<body>
    <?php include '../templates/header.php'; ?>

    <div class="container">
        <h1>Add Portfolio</h1>

        <form class="add-portfolio-form" action="addPortfolio.php" method="POST">
            <label for="symbol">Stock Symbol:</label>
            <input type="text" id="symbol" name="symbol" required>

            <label for="name">Stock Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" required>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" required>

            <label for="transaction_type">Transaction Type:</label>
            <select id="transaction_type" name="transaction_type" required>
                <option value="buy">Buy</option>
                <option value="sell">Sell</option>
            </select>

            <button type="submit">Add Transaction</button>
        </form>
    </div>

    <?php include '../templates/footer.php'; ?>
</body>
</html>
