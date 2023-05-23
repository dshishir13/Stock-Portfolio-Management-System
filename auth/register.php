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

// Check if the registration form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the input values from the registration form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Perform validation and registration process here
    // You need to write the code to validate the input data and create a new user in the database
    // Assuming you have a function called "registerUser" that handles the registration process
    $success = registerUser($username, $password, $email);

    if ($success) {
        // Registration successful
        // You can redirect the user to a success page or perform any other actions
        header('Location: ../auth/login.php?success=1');
        exit();
    } else {
        // Registration failed
        // You can handle the failure, such as displaying an error message or redirecting back to the registration page with an error parameter
        header('Location: ../auth/register.php?error=1');
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <!-- Include your CSS and JavaScript files -->
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/script.js"></script>
</head>
<body>
    <!-- Include the header file -->
    <?php include '../templates/header.php'; ?>

    <div class="container">
        <h1>Register</h1>

        <!-- Registration form -->
        <form method="POST" action="">
            <!-- Add your registration form fields here -->
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="email" name="email" placeholder="Email" required>
            <button type="submit">Register</button>
        </form>

        <!-- Link to the login page -->
        <p>Already have an account? <a href="../auth/login.php">Login here</a></p>
    </div>

    <!-- Include the footer file -->
    <?php include '../templates/footer.php'; ?>
</body>
</html>
