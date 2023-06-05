<?php
// Include the necessary files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Start a session
session_start();

// Check if the user is logged in and is an admin
if (!isLoggedIn() || !isAdmin()) {
    // Redirect to the login page or non-admin page if the user is not logged in or is not an admin
    header('Location: ../auth/login.php');
    exit();
}

// Get the current admin user
$adminUser = getCurrentUser();

// Handle form submission or perform other admin-specific actions

?>

<!DOCTYPE html>
<html>
<head>
    <title>Stock Portfolio Management System - Admin Dashboard</title>
    <!-- Include your CSS and JavaScript files -->
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/script.js"></script>
</head>
<body>
    <!-- Include the header file -->
    <?php include '../templates/header.php'; ?>

    <div class="container">
        <h1>Welcome, <?php echo isset($adminUser['username']) ? $adminUser['username'] : ''; ?>!</h1>
        
        <!-- Admin-specific content and features -->

        <a href="../auth/logout.php">Logout</a>
    </div>

    <!-- Include the footer file -->
    <?php include '../templates/footer.php'; ?>
</body>
</html>
