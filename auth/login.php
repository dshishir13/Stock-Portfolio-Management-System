<?php
// Include the necessary files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Start a session
session_start();

// Check if the user is already logged in
if (!empty($_SESSION['user_id'])) {
    // Redirect to the dashboard if the user is already logged in
    header('Location: ../dashboard/index.php');
    exit();
}

// Handle form submission
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the input values from the login form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Perform login validation and verification here
    // You need to write the code to check if the username and password match a valid user in the database
    // Assuming you have a function called "validateLogin" that checks the credentials and returns the user ID
    $userId = validateLogin($username, $password);

    if ($userId) {
        // Login successful
        // Store the user ID in the session
        $_SESSION['user_id'] = $userId;

        // Redirect to the dashboard
        header('Location: ../dashboard/index.php');
        exit();
    } else {
        // Login failed
        // You can handle the failure, such as displaying an error message or redirecting back to the login page with an error parameter
        header('Location: ../auth/login.php?error=1');
        exit();
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <!-- Include your CSS and JavaScript files -->
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/script.js"></script>
</head>
<body>
    <!-- Include the header file -->
    <?php include '../templates/header.php'; ?>

    <div class="container">
        <h1>Login</h1>
        
        <!-- Login form -->
        <form method="POST" action="">
            <!-- Add your login form fields here -->
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="../auth/register.php">Register here</a></p>
    </div>

    <!-- Include the footer file -->
    <?php include '../templates/footer.php'; ?>
</body>
</html>
