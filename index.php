<?php
    // Include necessary files and configurations
    require_once 'config/config.php';
    require_once 'includes/db.php';

    // Start session and check if the user is logged in
    session_start();
    // Add your authentication logic here

    // Redirect the user to the appropriate page based on their authentication status
    if (isset($_SESSION['user_id'])) {
        header('Location: dashboard/');
        exit();
    } else {
        header('Location: auth/login.php');
        exit();
    }
?>
