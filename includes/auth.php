<?php
// Authentication helper functions

/**
 * Check if user is logged in, if not redirect to login page
 */
function ensureUserIsLoggedIn() {
    // Initialize the session if not already started
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Check if the user is logged in, if not redirect to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: /wci-crud/pages/login.php");
        exit;
    }
}

/**
 * Redirect logged in users away from login/register pages
 */
function redirectLoggedInUser() {
    // Initialize the session if not already started
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // If user is already logged in, redirect to dashboard
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        header("location: /wci-crud/pages/dashboard.php");
        exit;
    }
}

/**
 * Sanitize user input
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>