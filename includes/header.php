<?php
// Initialize the session
session_start();

// Check if the user is logged in
$loggedIn = isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WCI CRUD Application</title>
    <link rel="stylesheet" href="/wci-crud/assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <h1>WCI CRUD App</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="/wci-crud/index.php">Home</a></li>
                    <?php if($loggedIn): ?>
                        <li><a href="/wci-crud/pages/dashboard.php">Dashboard</a></li>
                        <li><a href="/wci-crud/pages/tasks.php">Tasks</a></li>
                        <li><a href="/wci-crud/pages/profile.php">Profile</a></li>
                        <li><a href="/wci-crud/includes/logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="/wci-crud/pages/login.php">Login</a></li>
                        <li><a href="/wci-crud/pages/register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="container">