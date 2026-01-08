<?php
/**
 * Create Task Page
 * Allows users to create new tasks
 */
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once '../config/db.php';

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? 'Open';
    $user_id = $_SESSION['user_id'];
    
    // Validate input
    if (empty($title)) {
        $error = 'Task title is required.';
    } elseif (strlen($title) > 100) {
        $error = 'Task title must be less than 100 characters.';
    } else {
        // Insert task using prepared statement
        $stmt = $conn->prepare("INSERT INTO tasks (title, description, status, created_by) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $title, $description, $status, $user_id);
        
        if ($stmt->execute()) {
            $success = 'Task created successfully!';
            // Clear form data
            $_POST = array();
            // Optionally redirect after 1 second
            header("refresh:1;url=list.php");
        } else {
            $error = 'Error creating task: ' . $conn->error;
        }
        
        $stmt->close();
    }
}

$pageTitle = 'Create Task - Task Manager';
include '../includes/header.php';
?>

<div class="page-header">
    <h2><i class="bi bi-plus-circle"></i> Create New Task</h2>
    <p class="text-muted">Add a new task to your task list</p>
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
                            value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>"
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
                        ><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="Open" <?php echo (isset($_POST['status']) && $_POST['status'] == 'Open') ? 'selected' : 'selected'; ?>>Open</option>
                            <option value="In Progress" <?php echo (isset($_POST['status']) && $_POST['status'] == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                            <option value="Completed" <?php echo (isset($_POST['status']) && $_POST['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                            <option value="Closed" <?php echo (isset($_POST['status']) && $_POST['status'] == 'Closed') ? 'selected' : ''; ?>>Closed</option>
                        </select>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="list.php" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Create Task
                        </button>
                    </div>
                    </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<?php $conn->close(); ?>

