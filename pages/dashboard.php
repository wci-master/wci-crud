<?php
// Include config file and authentication helper
require_once "../config/database.php";
require_once "../includes/auth.php";

// Ensure user is logged in
ensureUserIsLoggedIn();

// Get user's task count
$task_count = 0;
$pending_count = 0;
$completed_count = 0;

$sql = "SELECT COUNT(*) as total, 
       SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
       SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed
       FROM tasks WHERE user_id = ?";

if($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);
    
    if(mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        
        if($row = mysqli_fetch_assoc($result)) {
            $task_count = $row["total"];
            $pending_count = $row["pending"];
            $completed_count = $row["completed"];
        }
    }
    
    mysqli_stmt_close($stmt);
}

// Get recent tasks
$recent_tasks = [];
$sql = "SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at DESC LIMIT 5";

if($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);
    
    if(mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        
        while($row = mysqli_fetch_assoc($result)) {
            $recent_tasks[] = $row;
        }
    }
    
    mysqli_stmt_close($stmt);
}

// Include header
require_once "../includes/header.php";
?>

<div class="dashboard-container">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION["full_name"]); ?>!</h2>
    
    <div class="dashboard-stats">
        <div class="stat-card">
            <h3>Total Tasks</h3>
            <p class="stat-number"><?php echo $task_count; ?></p>
        </div>
        <div class="stat-card">
            <h3>Pending Tasks</h3>
            <p class="stat-number"><?php echo $pending_count; ?></p>
        </div>
        <div class="stat-card">
            <h3>Completed Tasks</h3>
            <p class="stat-number"><?php echo $completed_count; ?></p>
        </div>
    </div>
    
    <div class="dashboard-actions">
        <a href="tasks.php?action=add" class="btn btn-primary">Create New Task</a>
        <a href="profile.php" class="btn btn-secondary">Edit Profile</a>
    </div>
    
    <div class="recent-tasks">
        <h3>Recent Tasks</h3>
        <?php if(count($recent_tasks) > 0): ?>
            <table class="task-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($recent_tasks as $task): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($task["title"]); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $task["status"]; ?>">
                                    <?php echo ucfirst($task["status"]); ?>
                                </span>
                            </td>
                            <td><?php echo date("M d, Y", strtotime($task["created_at"])); ?></td>
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
    </div>
</div>

<?php
// Include footer
require_once "../includes/footer.php";
?>