<?php
// Include the necessary files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Start the session
session_start();

// Check if the user is logged in
if (!isLoggedIn()) {
    header('Location: login.php');
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
    // Retrieve the new password from the form
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate the new password
    if ($newPassword !== $confirmPassword) {
        $_SESSION['error'] = 'Passwords do not match.';
        header('Location: adminProfile.php');
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
    header('Location: adminProfile.php');
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

        // JavaScript function to handle deletion confirmation
        function openDeleteAccountPopup() {
            var popup = document.getElementById('deleteAccountPopup');
            popup.classList.add('show');
        }

        function closeDeleteAccountPopup() {
            var popup = document.getElementById('deleteAccountPopup');
            popup.classList.remove('show');
        }
    </script>
</head>
<body>
    <!-- Include the header file -->
    <?php include '../templates/adminHeader.php'; ?>

    <div class="container">
        <h1>Admin Profile</h1>

        <div class="profile-info">
            <p><strong>Admin Username:</strong> <?php echo $user['username']; ?></p>
            <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
        </div>

        <button onclick="openChangePasswordPopup()">Change Password</button>
        <button onclick="openDeleteAccountPopup()">Delete Account</button>

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

        <div id="deleteAccountPopup" class="popup">
            <div class="popup-content">
                <span class="close" onclick="closeDeleteAccountPopup()">&times;</span>
                <h2>Delete Account</h2>
                <p>Are you sure you want to delete your account?</p>
               <p>Please enter "DELETE" below to confirm:</p>
                <form method="POST" action="deleteAccount.php">
                    <input type="text" name="confirmation" required>
                    <button type="submit">Delete Account</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Include the footer file -->
    <?php include '../templates/footer.php'; ?>
</body>
</html>
