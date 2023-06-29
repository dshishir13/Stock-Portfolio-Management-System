<?php
// Include the necessary files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Start the session
session_start();

// Check if the user is logged in
if (!isLoggedIn()) {
    header('Location: ../auth/login.php');
    exit();
}

// Retrieve the user ID
$userId = getCurrentUserId();

// Fetch the user data
$query = "SELECT * FROM users WHERE id = :userId";
$params = [':userId' => $userId];
$user = fetchSingleRow($query, $params);

// Handle password change form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the new password and confirm password from the form
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate the new password and confirm password
    if (empty($newPassword) || empty($confirmPassword)) {
        $_SESSION['error'] = 'Please enter a new password and confirm the password.';
        header('Location: profile.php');
        exit();
    }

    if ($newPassword !== $confirmPassword) {
        $_SESSION['error'] = 'The new password and confirm password do not match.';
        header('Location: profile.php');
        exit();
    }

    // Check for password complexity requirements and validate as needed
    // ...

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update the user's password in the database
    $query = "UPDATE users SET password = :password WHERE id = :userId";
    $params = [
        ':password' => $hashedPassword,
        ':userId' => $userId
    ];
    executeQuery($query, $params);

    // Display success message
    $_SESSION['success'] = 'Password updated successfully.';
    header('Location: profile.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
    <!-- Include your CSS file -->
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="../assets/js/script.js"></script>
    <style>
        /* CSS for the popup */
        .popup {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .popup-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 300px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: #000;
        }

        .show {
            display: block;
        }
    </style>
    <script>
        // JavaScript function to handle opening and closing of the popup
        function openChangePasswordPopup() {
            var popup = document.getElementById('changePasswordPopup');
            popup.classList.add('show');
        }

        function closeChangePasswordPopup() {
            var popup = document.getElementById('changePasswordPopup');
            popup.classList.remove('show');
        }
    </script>
</head>
<body>
    <!-- Include the header file -->
    <?php include '../templates/header.php'; ?>

    <div class="container">
        <h1>User Profile</h1>

        <div class="profile-info">
            <p><strong>Username:</strong> <?php echo $user['username']; ?></p>
            <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
        </div>

        <button onclick="openChangePasswordPopup()">Change Password</button>

        <div id="changePasswordPopup" class="popup">
            <div class="popup-content">
                <span class="close" onclick="closeChangePasswordPopup()">&times;</span>
                <h2>Change Password</h2>
                <form method="POST" action="profile.php">
                    <label for="new_password">New Password:</label>
                    <input type="password" id="new_password" name="new_password" required>

                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>

                    <button type="submit">Change Password</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Include the footer file -->
    <?php include '../templates/footer.php'; ?>
</body>
</html>
