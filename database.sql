-- WCI CRUD Application Database Schema

-- Create database
CREATE DATABASE IF NOT EXISTS wci_crud;

-- Use the database
USE wci_crud;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Create tasks table
CREATE TABLE IF NOT EXISTS tasks (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    status ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Add sample user (password: password123)
-- INSERT INTO users (username, password, email, full_name) VALUES
-- ('demo', '$2y$10$8i5Eo8ALQpj5WOeJqmq5AOlSWnwZlmyl5Kfm5WlZjdz/B6FmCiOAy', 'demo@example.com', 'Demo User');

-- Add sample tasks
-- INSERT INTO tasks (user_id, title, description, status) VALUES
-- (1, 'Complete project documentation', 'Write comprehensive documentation for the CRUD project', 'pending'),
-- (1, 'Fix login validation', 'Improve validation on the login form', 'in_progress'),
-- (1, 'Add pagination to tasks list', 'Implement pagination for better user experience', 'pending'),
-- (1, 'Update CSS styles', 'Enhance the visual appearance of the application', 'completed');

-- Note: The sample data is commented out by default.
-- Uncomment and modify as needed for testing purposes.