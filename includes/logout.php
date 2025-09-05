<?php
// Initialize the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header("location: /wci-crud/index.php");
exit;
?>