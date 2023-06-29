<?php
// Include the necessary files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/adminFunctions.php';

// Start a session
session_start();

// Check if the user is logged in and has admin role
if (!isLoggedIn() || $_SESSION['role'] !== 'admin') {
    // Redirect to the login page or any other page as needed
    header('Location: ../auth/login.php');
    exit();
}

// Check if the form is submitted for adding a new user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addUser'])) {
    // Get the input values from the form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validate the input values
    if (empty($username) || empty($email) || empty($password) || empty($role)) {
        // Handle validation error, such as displaying an error message or redirecting back to the form with an error parameter
        header('Location: manageUsers.php?error=1');
        exit();
    }

    // Add the user to the database
    $success = addUser($username, $email, $password, $role);

    if ($success) {
        // User added successfully
        // Redirect to the user list page or any other page as needed
        header('Location: manageUsers.php');
        exit();
    } else {
        // Failed to add user
        // Handle the failure, such as displaying an error message or redirecting back to the form with an error parameter
        header('Location: manageUsers.php?error=2');
        exit();
    }
}

// Check if the form is submitted for updating a user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateUser'])) {
    // Get the user ID and input values from the form
    $userId = $_POST['userId'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validate the input values
    if (empty($username) || empty($email) || empty($role)) {
        // Handle validation error, such as displaying an error message or redirecting back to the form with an error parameter
        header("Location: manageUsers.php?edit=$userId&error=1");
        exit();
    }

    // Update the user in the database
    $success = updateUser($userId, $username, $email, $password, $role);

    if ($success) {
        // User updated successfully
        // Redirect to the user list page or any other page as needed
        header('Location: manageUsers.php');
        exit();
    } else {
        // Failed to update user
        // Handle the failure, such as displaying an error message or redirecting back to the form with an error parameter
        header("Location: manageUsers.php?edit=$userId&error=2");
        exit();
    }
}

// Check if the user ID is provided for deleting a user
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete'])) {
    // Get the user ID from the URL parameter
    $userId = $_GET['delete'];

    // Delete the user from the database
    $success = deleteUser($userId);

    if ($success) {
        // User deleted successfully
        // Redirect to the user list page or any other page as needed
        header('Location: manageUsers.php');
        exit();
    } else {
        // Failed to delete user
        // Handle the failure, such as displaying an error message or redirecting back to the user list with an error parameter
        header('Location: manageUsers.php?error=3');
        exit();
    }
}

// Retrieve all users from the database
$users = getAllUsers();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <!-- Include your CSS file -->
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <!-- Include the header file -->
    <?php include '../templates/adminHeader.php'; ?>

    <div class="container">
        <h1>Manage Users</h1>

        <!-- User List -->
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['role']; ?></td>
                    <td>
                        <a href="manageUsers.php?edit=<?php echo $user['id']; ?>">Edit</a>
                        <a href="manageUsers.php?delete=<?php echo $user['id']; ?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <?php if (isset($_GET['edit'])): ?>
            <!-- Edit User Form -->
            <?php
            $editUserId = $_GET['edit'];
            $editUser = getUserById($editUserId);
            if ($editUser):
            ?>
                <h2>Edit User</h2>
                <form method="POST" action="">
                    <input type="hidden" name="userId" value="<?php echo $editUserId; ?>">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" value="<?php echo $editUser['username']; ?>" required>

                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" value="<?php echo $editUser['email']; ?>" required>

                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password">

                    <label for="role">Role:</label>
                    <select name="role" id="role" required>
                        <option value="admin" <?php echo ($editUser['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                        <option value="user" <?php echo ($editUser['role'] === 'user') ? 'selected' : ''; ?>>User</option>
                    </select>

                    <button type="submit" name="updateUser">Update User</button>
                </form>
            <?php endif; ?>
        <?php else: ?>
            <!-- Add User Form -->
            <h2>Add User</h2>
            <form method="POST" action="">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>

                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>

                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>

                <label for="role">Role:</label>
                <select name="role" id="role" required>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>

                <button type="submit" name="addUser">Add User</button>
            </form>
        <?php endif; ?>
    </div>

    <!-- Include the footer file -->
    <?php include '../templates/footer.php'; ?>
</body>
</html>
