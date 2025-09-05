<?php
// Include config file and authentication helper
require_once "../config/database.php";
require_once "../includes/auth.php";

// Ensure user is logged in
ensureUserIsLoggedIn();

// Define variables and initialize with empty values
$full_name = $email = $current_password = $new_password = $confirm_password = "";
$full_name_err = $email_err = $current_password_err = $new_password_err = $confirm_password_err = "";
$success_msg = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Check which form was submitted
    if(isset($_POST["update_profile"])) {
        // Validate full name
        if(empty(trim($_POST["full_name"]))) {
            $full_name_err = "Please enter your full name.";
        } else {
            $full_name = trim($_POST["full_name"]);
        }
        
        // Validate email
        if(empty(trim($_POST["email"]))) {
            $email_err = "Please enter an email.";
        } else {
            // Check if email is valid
            if(!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
                $email_err = "Please enter a valid email address.";
            } else {
                // Check if email is already taken by another user
                $sql = "SELECT id FROM users WHERE email = ? AND id != ?";
                
                if($stmt = mysqli_prepare($conn, $sql)) {
                    mysqli_stmt_bind_param($stmt, "si", $param_email, $param_id);
                    
                    $param_email = trim($_POST["email"]);
                    $param_id = $_SESSION["id"];
                    
                    if(mysqli_stmt_execute($stmt)) {
                        mysqli_stmt_store_result($stmt);
                        
                        if(mysqli_stmt_num_rows($stmt) == 1) {
                            $email_err = "This email is already taken by another user.";
                        } else {
                            $email = trim($_POST["email"]);
                        }
                    } else {
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                    
                    mysqli_stmt_close($stmt);
                }
            }
        }
        
        // Check input errors before updating the database
        if(empty($full_name_err) && empty($email_err)) {
            // Prepare an update statement
            $sql = "UPDATE users SET full_name = ?, email = ? WHERE id = ?";
            
            if($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "ssi", $param_full_name, $param_email, $param_id);
                
                $param_full_name = $full_name;
                $param_email = $email;
                $param_id = $_SESSION["id"];
                
                if(mysqli_stmt_execute($stmt)) {
                    // Update session variables
                    $_SESSION["full_name"] = $full_name;
                    $_SESSION["email"] = $email;
                    
                    $success_msg = "Profile updated successfully.";
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }
                
                mysqli_stmt_close($stmt);
            }
        }
    } elseif(isset($_POST["change_password"])) {
        // Validate current password
        if(empty(trim($_POST["current_password"]))) {
            $current_password_err = "Please enter your current password.";
        } else {
            $current_password = trim($_POST["current_password"]);
            
            // Verify current password
            $sql = "SELECT password FROM users WHERE id = ?";
            
            if($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "i", $param_id);
                
                $param_id = $_SESSION["id"];
                
                if(mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    
                    if(mysqli_stmt_num_rows($stmt) == 1) {
                        mysqli_stmt_bind_result($stmt, $hashed_password);
                        if(mysqli_stmt_fetch($stmt)) {
                            if(!password_verify($current_password, $hashed_password)) {
                                $current_password_err = "Current password is incorrect.";
                            }
                        }
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }
                
                mysqli_stmt_close($stmt);
            }
        }
        
        // Validate new password
        if(empty(trim($_POST["new_password"]))) {
            $new_password_err = "Please enter the new password.";
        } elseif(strlen(trim($_POST["new_password"])) < 6) {
            $new_password_err = "Password must have at least 6 characters.";
        } else {
            $new_password = trim($_POST["new_password"]);
        }
        
        // Validate confirm password
        if(empty(trim($_POST["confirm_password"]))) {
            $confirm_password_err = "Please confirm the password.";
        } else {
            $confirm_password = trim($_POST["confirm_password"]);
            if(empty($new_password_err) && ($new_password != $confirm_password)) {
                $confirm_password_err = "Password did not match.";
            }
        }
        
        // Check input errors before updating the database
        if(empty($current_password_err) && empty($new_password_err) && empty($confirm_password_err)) {
            // Prepare an update statement
            $sql = "UPDATE users SET password = ? WHERE id = ?";
            
            if($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
                
                $param_password = password_hash($new_password, PASSWORD_DEFAULT);
                $param_id = $_SESSION["id"];
                
                if(mysqli_stmt_execute($stmt)) {
                    $success_msg = "Password changed successfully.";
                    
                    // Clear password fields
                    $current_password = $new_password = $confirm_password = "";
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }
                
                mysqli_stmt_close($stmt);
            }
        }
    }
}

// Get user data if not already set
if(empty($full_name) && empty($email)) {
    $full_name = $_SESSION["full_name"];
    $email = $_SESSION["email"];
}

// Include header
require_once "../includes/header.php";
?>

<div class="profile-container">
    <h2>Profile Management</h2>
    
    <?php if(!empty($success_msg)): ?>
        <div class="alert alert-success"><?php echo $success_msg; ?></div>
    <?php endif; ?>
    
    <div class="profile-section">
        <h3>Update Profile Information</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($full_name_err)) ? 'has-error' : ''; ?>">
                <label>Full Name</label>
                <input type="text" name="full_name" class="form-control" value="<?php echo $full_name; ?>">
                <span class="help-block"><?php echo $full_name_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" name="update_profile" class="btn btn-primary" value="Update Profile">
            </div>
        </form>
    </div>
    
    <div class="profile-section">
        <h3>Change Password</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($current_password_err)) ? 'has-error' : ''; ?>">
                <label>Current Password</label>
                <input type="password" name="current_password" class="form-control">
                <span class="help-block"><?php echo $current_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control">
                <span class="help-block"><?php echo $new_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" name="change_password" class="btn btn-primary" value="Change Password">
            </div>
        </form>
    </div>
    
    <div class="back-link">
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</div>

<?php
// Include footer
require_once "../includes/footer.php";
?>