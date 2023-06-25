<?php

require_once 'db.php';
global $pdo;

// Function to sanitize user input
function sanitizeInput($input) {
    // Implement your sanitization logic here
    // For example, you can use functions like htmlspecialchars or filter_var
    return htmlspecialchars($input);
}

// Function to check if the user is logged in
function isLoggedIn() {
    // Check if the 'user_id' session variable is set
    return isset($_SESSION['user_id']);
}

// Function to check if the logged-in user is an admin
function isAdmin() {
    // Check if the 'role' session variable is set to 'admin'
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Function to check if the logged-in user is an admin and logged in
function isAdminLoggedIn() {
    // Check if the user is logged in and has the 'role' session variable set to 'admin'
    if (isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        return true; // Admin is logged in
    }
    return false; // Admin is not logged in
}

// Validate the login credentials and retrieve the user ID and role
function validateLogin($username, $password) {
    // Establish a database connection
    $db = getDB();

    // Prepare the SQL statement to fetch the user details
    $sql = "SELECT id, role, password FROM users WHERE username = :username LIMIT 1";
    $stmt = $db->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':username', $username);

    // Execute the statement
    $stmt->execute();

    // Fetch the user details
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Login successful
            // Return the user ID and role as an associative array
            return ['id' => $user['id'], 'role' => $user['role']];
        }
    }

    // Login failed
    return false;
}

// Function to validate login credentials for admin
function validateAdminLogin($username, $password) {
    global $pdo;

    // Prepare the SQL statement to fetch the user details
    $sql = "SELECT id, role FROM users WHERE username = :username LIMIT 1";
    $stmt = $pdo->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':username', $username);

    // Execute the statement
    $stmt->execute();

    // Fetch the user details
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // User credentials are valid
        if ($user['role'] === 'admin') {
            // User is an admin
            return $user['id'];
        } else {
            // User is not an admin
            return false;
        }
    } else {
        // User credentials are invalid
        return false;
    }
}

// Function to get the current user ID
function getCurrentUserId() {
    if (isLoggedIn()) {
        return $_SESSION['user_id'];
    } else {
        return null;
    }
}

// Function to log out the user
function logout() {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to the login page or any other page as needed
    header('Location: ../auth/login.php');
    exit();
}

// Function to execute a database query
function executeQuery($query, $params = array()) {
    global $pdo;

    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->rowCount();
    } catch (PDOException $e) {
        // Handle the error appropriately, e.g. logging or displaying an error message
        exit('Failed to execute query: ' . $e->getMessage());
    }
}

// Fetches a stock by symbol and name
function getStockBySymbolAndName(string $symbol, string $name): ?array {
    $query = "SELECT id FROM stocks WHERE symbol = :symbol AND name = :name";
    $params = [
        ':symbol' => $symbol,
        ':name' => $name
    ];
    return fetchSingleRow($query, $params);
}

// Adds a new stock to the stocks table
function addStock(string $symbol, string $name, float $price): int {
    $query = "INSERT INTO stocks (symbol, name, price) VALUES (:symbol, :name, :price)";
    $params = [
        ':symbol' => $symbol,
        ':name' => $name,
        ':price' => $price
    ];
    return executeQuery($query, $params);
}


/**
 * Delete a stock from the database.
 *
 * @param int $stockId The ID of the stock to delete.
 * @return bool True if the stock was deleted successfully, false otherwise.
 */
function deleteStock($stockId)
{
    
    global $pdo;

    // Prepare the delete statement
    $stmt = $pdo->prepare("DELETE FROM stocks WHERE id = ?");
    $stmt->bind_param("i", $stockId);

    // Execute the delete statement
    $success = $stmt->execute();

    // Close the statement
    $stmt->close();

    return $success;
}

// Adds a new transaction to the transactions table
function addTransaction(int $userId, int $stockId, int $quantity, float $price, string $transactionType, float $transactionValue): void {
    $query = "INSERT INTO transactions (user_id, stock_id, quantity, price, transaction_type, transaction_value) VALUES (:userId, :stockId, :quantity, :price, :transactionType, :transactionValue)";
    $params = [
        ':userId' => $userId,
        ':stockId' => $stockId,
        ':quantity' => $quantity,
        ':price' => $price,
        ':transactionType' => $transactionType,
        ':transactionValue' => $transactionValue
    ];
    executeQuery($query, $params);
}


// Function to get stock by ID
function getStockById($stockId) {
    global $pdo;

    // Assuming you have a table named "stocks" with columns "id", "name", "symbol", and "price" to store the stock information
    $query = "SELECT * FROM stocks WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':id', $stockId);
    $stmt->execute();

    // Fetch the stock data
    $stock = $stmt->fetch(PDO::FETCH_ASSOC);

    // Return the stock data
    return $stock;
}

// Function to update a stock in the database
function updateStock($stockId, $symbol, $name, $price) {
    global $pdo;

    $query = "UPDATE stocks SET symbol = :symbol, name = :name, price = :price WHERE id = :stockId";
    $params = [
        ':symbol' => $symbol,
        ':name' => $name,
        ':price' => $price,
        ':stockId' => $stockId
    ];

    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// Function to get the last inserted ID
function getLastInsertId() {
    global $pdo;

    return $pdo->lastInsertId();
}

/**
 * Registers a new user with the provided details.
 *
 * @param string $username The username of the user.
 * @param string $password The password of the user.
 * @param string $email The email address of the user.
 * @param string $role The role of the user ('user' or 'admin').
 *
 * @return bool True if the registration is successful, false otherwise.
 */
function registerUser($username, $password, $email, $role)
{
    // Perform any necessary validation on the input data

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Establish a database connection
    $db = getDB();

    // Prepare the SQL statement to insert a new user
    $sql = "INSERT INTO users (username, password, email, role) VALUES (:username, :password, :email, :role)";
    $stmt = $db->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':role', $role);

    // Execute the statement
    $success = $stmt->execute();

    // Return the result of the registration process
    return $success;
}

/**
 * Executes a query and returns the last inserted ID.
 *
 * @param string $query The SQL query to execute.
 * @param array $params An associative array of parameter values.
 *
 * @return int|false The last inserted ID if successful, false otherwise.
 */
function executeQueryAndGetLastInsertId($query, $params)
{
    global $pdo;

    try {
        // Check if the database connection is established
        if (!$pdo) {
            connectToDatabase();
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        // Get the last inserted ID
        $lastInsertId = $pdo->lastInsertId();

        // Return the last inserted ID
        return $lastInsertId;
    } catch (PDOException $e) {
        // Handle the exception, e.g., log the error or display an error message
        // For example:
        error_log('Error executing query: ' . $e->getMessage());
        return false;
    }
}

// Function to fetch a single value from the database
function fetchSingleValue($query, $params = []) {
    global $pdo;

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    return $stmt->fetchColumn();
}

// Function to display success message
function displaySuccessMessage($message) {
    echo '<div class="alert alert-success">' . $message . '</div>';
}

// Function to display error message
function displayErrorMessage($message) {
    echo '<div class="alert alert-danger">' . $message . '</div>';
}
?>

