<?php
    // Include necessary files and configurations
    require_once 'includes/config.php';
    require_once 'includes/db.php';
    require_once 'includes/functions.php';

    // Start session and check if the user is logged in
    session_start();

    // Redirect the user to the appropriate page based on their authentication status
    if (isLoggedIn()) {
        header('Location: dashboard/index.php');
        exit();
    } else {
        header('Location: auth/login.php');
        exit();
    }
?>
