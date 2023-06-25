<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

global $pdo;

// Add a new user to the database
function addUser($username, $email, $password, $role) {
    global $pdo;

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the query
    $query = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($query);

    // Bind the parameters
    $stmt->bind_param("ssss", $username, $email, $hashedPassword, $role);

    // Execute the query
    $success = $stmt->execute();

    // Close the statement
    $stmt->close();

    return $success;
}

// Update an existing user in the database
function updateUser($userId, $username, $email, $password, $role) {
    global $pdo;

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the query
    $query = "UPDATE users SET username = ?, email = ?, password = ?, role = ? WHERE id = ?";
    $stmt = $pdo->prepare($query);

    // Bind the parameters
    $stmt->bind_param("ssssi", $username, $email, $hashedPassword, $role, $userId);

    // Execute the query
    $success = $stmt->execute();

    // Close the statement
    $stmt->close();

    return $success;
}

// Delete a user from the database
function deleteUser($userId) {
    global $pdo;

    // Prepare the query
    $query = "DELETE FROM users WHERE id = ?";
    $stmt = $pdo->prepare($query);

    // Bind the parameter
    $stmt->bind_param("i", $userId);

    // Execute the query
    $success = $stmt->execute();

    // Close the statement
    $stmt->close();

    return $success;
}

// Retrieve all users from the database
function getAllUsers() {
    global $pdo;

    // Prepare the query
    $query = "SELECT * FROM users";
    $result = $pdo->query($query);

    $users = array();

    // Fetch the results
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    // Free the result set
    $result->free();

    return $users;
}

// Retrieve a user by ID from the database
function getUserById($userId) {
    global $pdo;

    // Prepare the query
    $query = "SELECT * FROM users WHERE id = :id";
    $stmt = $pdo->prepare($query);

    // Bind the parameter
    $stmt->bindValue(':id', $userId, PDO::PARAM_INT);

    // Execute the query
    $stmt->execute();

    // Fetch the result
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user;
}






?>
