<?php

// Include the config file
require_once 'config.php';

// Declare the $pdo variable

// Function to get the database connection
function getDB()
{
    global $pdo;

    // Check if the database connection is established
    if (!$pdo) {
        connectToDatabase();
    }

    return $pdo;
}



// Function to establish the database connection
function connectToDatabase() {
    global $pdo;

    try {
        $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die('Database connection failed: ' . $e->getMessage());
    }
}

// Function to fetch a single row from the database
function fetchSingleRow($query, $params = []) {
    global $pdo;

    // Check if the database connection is established
    if (!$pdo) {
        connectToDatabase();
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to fetch multiple rows from the database
function fetchMultipleRows($query, $params = []) {
    global $pdo;

    // Check if the database connection is established
    if (!$pdo) {
        connectToDatabase();
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Other database-related functions can be defined here

connectToDatabase();
?>