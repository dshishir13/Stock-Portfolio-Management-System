<?php
// Include the necessary files
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Start the session
session_start();

// Call the logout function
logout();

// Redirect to the login page after logout
header('Location: ../auth/login.php');
exit();
?>