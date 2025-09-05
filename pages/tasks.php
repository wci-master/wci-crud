<?php
// Include config file and authentication helper
require_once "../config/database.php";
require_once "../includes/auth.php";

// Ensure user is logged in
ensureUserIsLoggedIn();

// Define variables
$title = $description = $status = "";
$title_err = $description_err = $status_err = "";
$action = isset($_GET["action"]) ? $_GET["action"] : "list";
$task_id = isset($_GET["id"]) ? $_GET["id"] : 0;
$success_msg = $error_msg = "";

// Process based on action
switch($action) {
    case "add":
        // Process form data when form is submitted for adding a task
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            // Validate title
            if(empty(trim($_POST["title"]))) {
                $title_err = "Please enter a title.";
            } else {
                $title = trim($_POST["title"]);
            }
            
            // Description can be empty
            $description = trim($_POST["description"]);
            
            // Validate status
            if(empty($_POST["status"])) {
                $status_err = "Please select a status.";
            } else {
                $status = $_POST["status"];
            }
            
            // Check input errors before inserting in database
            if(empty($title_err) && empty($status_err)) {
                // Prepare an insert statement
                $sql = "INSERT INTO tasks (user_id, title, description, status) VALUES (?, ?, ?, ?)";
                
                if($stmt = mysqli_prepare($conn, $sql)) {
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "isss", $param_user_id, $param_title, $param_description, $param_status);
                    
                    // Set parameters
                    $param_user_id = $_SESSION["id"];
                    $param_title = $title;
                    $param_description = $description;
                    $param_status = $status;
                    
                    // Attempt to execute the prepared statement
                    if(mysqli_stmt_execute($stmt)) {
                        // Redirect to tasks list
                        header("location: tasks.php");
                        exit();
                    } else {
                        $error_msg = "Something went wrong. Please try again later.";
                    }
                    
                    // Close statement
                    mysqli_stmt_close($stmt);
                }
            }
        }
        break;
        
    case "edit":
        // Check if task exists and belongs to the user
        $sql = "SELECT * FROM tasks WHERE id = ? AND user_id = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ii", $task_id, $_SESSION["id"]);
            
            if(mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                
                if(mysqli_num_rows($result) == 1) {
                    $task = mysqli_fetch_assoc($result);
                    
                    // Set form values if not submitted
                    if($_SERVER["REQUEST_METHOD"] != "POST") {
                        $title = $task["title"];
                        $description = $task["description"];
                        $status = $task["status"];
                    }
                } else {
                    // Task not found or doesn't belong to user
                    header("location: tasks.php");
                    exit();
                }
            } else {
                $error_msg = "Something went wrong. Please try again later.";
            }
            
            mysqli_stmt_close($stmt);
        }
        
        // Process form data when form is submitted for editing a task
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            // Validate title
            if(empty(trim($_POST["title"]))) {
                $title_err = "Please enter a title.";
            } else {
                $title = trim($_POST["title"]);
            }
            
            // Description can be empty
            $description = trim($_POST["description"]);
            
            // Validate status
            if(empty($_POST["status"])) {
                $status_err = "Please select a status.";
            } else {
                $status = $_POST["status"];
            }
            
            // Check input errors before updating in database
            if(empty($title_err) && empty($status_err)) {
                // Prepare an update statement
                $sql = "UPDATE tasks SET title = ?, description = ?, status = ? WHERE id = ? AND user_id = ?";
                
                if($stmt = mysqli_prepare($conn, $sql)) {
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "sssii", $param_title, $param_description, $param_status, $param_id, $param_user_id);
                    
                    // Set parameters
                    $param_title = $title;
                    $param_description = $description;
                    $param_status = $status;
                    $param_id = $task_id;
                    $param_user_id = $_SESSION["id"];
                    
                    // Attempt to execute the prepared statement
                    if(mysqli_stmt_execute($stmt)) {
                        // Redirect to tasks list
                        header("location: tasks.php");
                        exit();
                    } else {
                        $error_msg = "Something went wrong. Please try again later.";
                    }
                    
                    // Close statement
                    mysqli_stmt_close($stmt);
                }
            }
        }
        break;
        
    case "view":
        // Check if task exists and belongs to the user
        $sql = "SELECT * FROM tasks WHERE id = ? AND user_id = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ii", $task_id, $_SESSION["id"]);
            
            if(mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                
                if(mysqli_num_rows($result) == 1) {
                    $task = mysqli_fetch_assoc($result);
                } else {
                    // Task not found or doesn't belong to user
                    header("location: tasks.php");
                    exit();
                }
            } else {
                $error_msg = "Something went wrong. Please try again later.";
            }
            
            mysqli_stmt_close($stmt);
        }
        break;
        
    case "delete":
        // Check if task exists and belongs to the user
        $sql = "DELETE FROM tasks WHERE id = ? AND user_id = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ii", $task_id, $_SESSION["id"]);
            
            if(mysqli_stmt_execute($stmt)) {
                // Redirect to tasks list
                header("location: tasks.php");
                exit();
            } else {
                $error_msg = "Something went wrong. Please try again later.";
            }
            
            mysqli_stmt_close($stmt);
        }
        break;
        
    case "list":
    default:
        // Get all tasks for the user
        $tasks = [];
        $sql = "SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at DESC";
        
        if($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);
            
            if(mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                
                while($row = mysqli_fetch_assoc($result)) {
                    $tasks[] = $row;
                }
            } else {
                $error_msg = "Something went wrong. Please try again later.";
            }
            
            mysqli_stmt_close($stmt);
        }
        break;
}

// Include header
require_once "../includes/header.php";
?>

<div class="tasks-container">
    <?php if(!empty($error_msg)): ?>
        <div class="alert alert-danger"><?php echo $error_msg; ?></div>
    <?php endif; ?>
    
    <?php if(!empty($success_msg)): ?>
        <div class="alert alert-success"><?php echo $success_msg; ?></div>
    <?php endif; ?>
    
    <?php if($action == "list"): ?>
        <div class="tasks-header">
            <h2>My Tasks</h2>
            <a href="tasks.php?action=add" class="btn btn-primary">Add New Task</a>
        </div>
        
        <?php if(count($tasks) > 0): ?>
            <table class="task-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($tasks as $task): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($task["title"]); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $task["status"]; ?>">
                                    <?php echo ucfirst($task["status"]); ?>
                                </span>
                            </td>
                            <td><?php echo date("M d, Y", strtotime($task["created_at"])); ?></td>
                            <td><?php echo date("M d, Y", strtotime($task["updated_at"])); ?></td>
                            <td>
                                <a href="tasks.php?action=view&id=<?php echo $task["id"]; ?>" class="btn-sm btn-info">View</a>
                                <a href="tasks.php?action=edit&id=<?php echo $task["id"]; ?>" class="btn-sm btn-warning">Edit</a>
                                <a href="tasks.php?action=delete&id=<?php echo $task["id"]; ?>" class="btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this task?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You haven't created any tasks yet. <a href="tasks.php?action=add">Create your first task</a>.</p>
        <?php endif; ?>
        
    <?php elseif($action == "add" || $action == "edit"): ?>
        <h2><?php echo ($action == "add") ? "Add New Task" : "Edit Task"; ?></h2>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?action=" . $action . ($action == "edit" ? "&id=" . $task_id : "")); ?>" method="post">
            <div class="form-group <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>">
                <label>Title</label>
                <input type="text" name="title" class="form-control" value="<?php echo $title; ?>">
                <span class="help-block"><?php echo $title_err; ?></span>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control"><?php echo $description; ?></textarea>
            </div>
            <div class="form-group <?php echo (!empty($status_err)) ? 'has-error' : ''; ?>">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="pending" <?php echo ($status == "pending") ? "selected" : ""; ?>>Pending</option>
                    <option value="in_progress" <?php echo ($status == "in_progress") ? "selected" : ""; ?>>In Progress</option>
                    <option value="completed" <?php echo ($status == "completed") ? "selected" : ""; ?>>Completed</option>
                </select>
                <span class="help-block"><?php echo $status_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="<?php echo ($action == "add") ? "Add Task" : "Update Task"; ?>">
                <a href="tasks.php" class="btn btn-default">Cancel</a>
            </div>
        </form>
        
    <?php elseif($action == "view"): ?>
        <div class="task-view">
            <h2>Task Details</h2>
            
            <div class="task-info">
                <h3><?php echo htmlspecialchars($task["title"]); ?></h3>
                
                <div class="task-meta">
                    <span class="status-badge status-<?php echo $task["status"]; ?>">
                        <?php echo ucfirst($task["status"]); ?>
                    </span>
                    <span class="task-date">
                        Created: <?php echo date("M d, Y", strtotime($task["created_at"])); ?>
                    </span>
                    <span class="task-date">
                        Last Updated: <?php echo date("M d, Y", strtotime($task["updated_at"])); ?>
                    </span>
                </div>
                
                <div class="task-description">
                    <h4>Description</h4>
                    <p><?php echo !empty($task["description"]) ? nl2br(htmlspecialchars($task["description"])) : "No description provided."; ?></p>
                </div>
                
                <div class="task-actions">
                    <a href="tasks.php?action=edit&id=<?php echo $task["id"]; ?>" class="btn btn-warning">Edit</a>
                    <a href="tasks.php?action=delete&id=<?php echo $task["id"]; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this task?');">Delete</a>
                    <a href="tasks.php" class="btn btn-default">Back to Tasks</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
// Include footer
require_once "../includes/footer.php";
?>