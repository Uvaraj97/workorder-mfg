<?php
/**
 * Edit Task Page
 * Allows users to update existing tasks
 */
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once '../config/db.php';

$user_id = $_SESSION['user_id'];
$task_id = $_GET['id'] ?? 0;
$error = '';
$success = '';

// Fetch task details
$stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ? AND created_by = ?");
$stmt->bind_param("ii", $task_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$task = $result->fetch_assoc();
$stmt->close();

// Check if task exists and belongs to user
if (!$task) {
    header("Location: list.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? 'Open';
    
    // Validate input
    if (empty($title)) {
        $error = 'Task title is required.';
    } elseif (strlen($title) > 100) {
        $error = 'Task title must be less than 100 characters.';
    } else {
        // Update task using prepared statement
        $stmt = $conn->prepare("UPDATE tasks SET title = ?, description = ?, status = ? WHERE id = ? AND created_by = ?");
        $stmt->bind_param("sssii", $title, $description, $status, $task_id, $user_id);
        
        if ($stmt->execute()) {
            $success = 'Task updated successfully!';
            // Update local task array
            $task['title'] = $title;
            $task['description'] = $description;
            $task['status'] = $status;
            // Optionally redirect after 1 second
            header("refresh:1;url=list.php");
        } else {
            $error = 'Error updating task: ' . $conn->error;
        }
        
        $stmt->close();
    }
}

$pageTitle = 'Edit Task - Task Manager';
include '../includes/header.php';
?>

<div class="page-header">
    <h2><i class="bi bi-pencil"></i> Edit Task</h2>
    <p class="text-muted">Update task details</p>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="title" class="form-label">Task Title <span class="text-danger">*</span></label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="title" 
                            name="title" 
                            placeholder="Enter task title" 
                            required 
                            maxlength="100"
                            value="<?php echo htmlspecialchars($task['title']); ?>"
                        >
                        <small class="text-muted">Maximum 100 characters</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea 
                            class="form-control" 
                            id="description" 
                            name="description" 
                            rows="5" 
                            placeholder="Enter task description (optional)"
                        ><?php echo htmlspecialchars($task['description']); ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="Open" <?php echo $task['status'] == 'Open' ? 'selected' : ''; ?>>Open</option>
                            <option value="In Progress" <?php echo $task['status'] == 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                            <option value="Completed" <?php echo $task['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                            <option value="Closed" <?php echo $task['status'] == 'Closed' ? 'selected' : ''; ?>>Closed</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">
                            <strong>Created:</strong> <?php echo date('M d, Y h:i A', strtotime($task['created_at'])); ?>
                        </small>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="list.php" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Update Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<?php $conn->close(); ?>


