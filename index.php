<?php
// Include header
require_once "includes/header.php";
?>

<div class="welcome-section">
    <h2>Welcome to WCI CRUD Application</h2>
    <p>This is a simple CRUD application built with HTML, CSS, JavaScript, and PHP.</p>
    <p>It demonstrates the basic operations of creating, reading, updating, and deleting records.</p>
    
    <?php if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true): ?>
        <div class="cta-buttons">
            <a href="pages/login.php" class="btn btn-primary">Login</a>
            <a href="pages/register.php" class="btn btn-secondary">Register</a>
        </div>
    <?php else: ?>
        <div class="cta-buttons">
            <a href="pages/dashboard.php" class="btn btn-primary">Go to Dashboard</a>
        </div>
    <?php endif; ?>
</div>

<div class="features-section">
    <h3>Features</h3>
    <div class="features-grid">
        <div class="feature-card">
            <h4>User Authentication</h4>
            <p>Secure signup and login functionality</p>
        </div>
        <div class="feature-card">
            <h4>User Dashboard</h4>
            <p>Personalized dashboard for each user</p>
        </div>
        <div class="feature-card">
            <h4>Profile Management</h4>
            <p>Edit and update your profile information</p>
        </div>
        <div class="feature-card">
            <h4>Task Management</h4>
            <p>Create, read, update, and delete tasks</p>
        </div>
    </div>
</div>

<?php
// Include footer
require_once "includes/footer.php";
?>