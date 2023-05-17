<?php
    // Include necessary files and configurations
    require_once '../config/config.php';
    require_once '../includes/db.php';

    // Start session
    session_start();

    // Check if the user is already logged in, redirect to dashboard if true
    if (isset($_SESSION['user_id'])) {
        header('Location: ../dashboard/');
        exit();
    }

    // Define variables and initialize with empty values
    $username = $password = '';
    $username_err = $password_err = '';

    // Process form data when the form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Validate username
        if (empty(trim($_POST['username']))) {
            $username_err = 'Please enter your username.';
        } else {
            $username = trim($_POST['username']);
        }

        // Validate password
        if (empty(trim($_POST['password']))) {
            $password_err = 'Please enter your password.';
        } else {
            $password = trim($_POST['password']);
        }

        // Check database for username and password
        if (empty($username_err) && empty($password_err)) {
            // Add your database query logic here to validate the username and password
            // If the credentials are valid, set the session variables and redirect to the dashboard
            // Example:
            // $user_id = 123; // Replace with actual user ID retrieved from the database
            // $_SESSION['user_id'] = $user_id;
            // header('Location: ../dashboard/');
            // exit();

            // For this example, let's assume the credentials are invalid
            $username_err = 'Invalid username or password.';
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/styles.css">
</head>
<body>
    <?php include '../templates/header.php'; ?>

    <main>
        <h1>Login</h1>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo $username; ?>">
                <span class="error"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
                <span class="error"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <button type="submit">Login</button>
            </div>
            <p>Don't have an account? <a href="register.php">Register here</a>.</p>
        </form>
    </main>

    <?php include '../templates/footer.php'; ?>
</body>
</html>
