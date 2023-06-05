<?php

require_once 'db.php';
global $pdo;

// Function to sanitize user input
function sanitizeInput($input) {
    // Implement your sanitization logic here
    // For example, you can use functions like htmlspecialchars or filter_var
    return htmlspecialchars($input);
}

// Check if the user is logged in
function isLoggedIn() {
    // Check if the 'user_id' session variable is set
    return isset($_SESSION['user_id']);
}

// Validate the login credentials and retrieve the user ID
function validateLogin($username, $password) {
    // Query the database to check if the username exists
    $query = "SELECT id, password FROM users WHERE username = :username";
    $params = [':username' => $username];
    $result = fetchSingleRow($query, $params);

    if ($result) {
        // Verify the password
        if (password_verify($password, $result['password'])) {
            // Password is correct
            return $result['id']; // Return the user ID
        }
    }

    return false; // Login validation failed
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
function executeQuery($query, $params = []) {
    global $pdo;

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
}

// Function to get the last inserted ID
function getLastInsertId() {
    global $pdo;

    return $pdo->lastInsertId();
}

// Function to register a new user
function registerUser($username, $password, $email) {
    // Perform validation and registration process here
    // For example, you can insert the user details into the database and return true if successful, or false if the registration fails
    
    // Replace the code below with your actual registration logic
    
    // Assuming you have a database connection established
    global $pdo;
    
    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert the user details into the database
    $query = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
    $params = [
        ':username' => $username,
        ':password' => $hashedPassword,
        ':email' => $email
    ];
    
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        
        return true; // Registration successful
    } catch (PDOException $e) {
        // Registration failed
        return false;
    }
}

/**
 * Executes a query and returns the last inserted ID.
 * @param string $query The SQL query to execute.
 * @param array $params An associative array of parameter values.
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

// Function to display success message
function displaySuccessMessage($message) {
    echo '<div class="alert alert-success">' . $message . '</div>';
}

// Function to display error message
function displayErrorMessage($message) {
    echo '<div class="alert alert-danger">' . $message . '</div>';
}

// Example usage:
// $sanitizedInput = sanitizeInput($_POST['input']);
// if (isLoggedIn()) {
//     $userId = getCurrentUserId();
//     // Perform actions specific to logged-in users
// } else {
//     // Handle actions for non-logged-in users
// }
// displaySuccessMessage("Operation completed successfully");
// displayErrorMessage("An error occurred");
?>
