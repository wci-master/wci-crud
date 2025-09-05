<?php
// Database configuration
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'wci_crud');

// Attempt to connect to MySQL database
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

// Check connection
if (!$conn) {
    die("ERROR: Could not connect to MySQL. " . mysqli_connect_error());
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if (mysqli_query($conn, $sql)) {
    // Select the database
    mysqli_select_db($conn, DB_NAME);
    
    // Create users table if it doesn't exist
    $users_table = "CREATE TABLE IF NOT EXISTS users (
        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        full_name VARCHAR(100) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )";
    
    mysqli_query($conn, $users_table);
    
    // Create tasks table if it doesn't exist
    $tasks_table = "CREATE TABLE IF NOT EXISTS tasks (
        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,
        title VARCHAR(100) NOT NULL,
        description TEXT,
        status ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    
    mysqli_query($conn, $tasks_table);
} else {
    echo "Error creating database: " . mysqli_error($conn);
}
?>