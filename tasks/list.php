<?php
/**
 * List Tasks Page
 * Displays all tasks with filtering and pagination options
 */
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once '../config/db.php';

$user_id = $_SESSION['user_id'];
$filter_status = $_GET['status'] ?? '';

// Build query with optional status filter
if (!empty($filter_status)) {
    $query = "SELECT t.*, u.username as created_by_name 
              FROM tasks t 
              LEFT JOIN users u ON t.created_by = u.id 
              WHERE t.created_by = ? AND t.status = ?
              ORDER BY t.created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $user_id, $filter_status);
} else {
    $query = "SELECT t.*, u.username as created_by_name 
              FROM tasks t 
              LEFT JOIN users u ON t.created_by = u.id 
              WHERE t.created_by = ?
              ORDER BY t.created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

$pageTitle = 'View Tasks - Task Manager';
include '../includes/header.php';

// Display success/error messages
if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> ' . htmlspecialchars($_SESSION['success_message']) . '
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>';
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle"></i> ' . htmlspecialchars($_SESSION['error_message']) . '
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>';
    unset($_SESSION['error_message']);
}
?>

<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h2><i class="bi bi-list-ul"></i> All Tasks</h2>
        <p class="text-muted">Manage your tasks</p>
    </div>
    <a href="create.php" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Create New Task
    </a>
</div>

<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="" class="row g-3">
            <div class="col-md-4">
                <label for="status" class="form-label">Filter by Status</label>
                <select class="form-select" id="status" name="status" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="Open" <?php echo $filter_status === 'Open' ? 'selected' : ''; ?>>Open</option>
                    <option value="In Progress" <?php echo $filter_status === 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                    <option value="Completed" <?php echo $filter_status === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                    <option value="Closed" <?php echo $filter_status === 'Closed' ? 'selected' : ''; ?>>Closed</option>
                </select>
            </div>
            <?php if ($filter_status): ?>
                <div class="col-md-4 d-flex align-items-end">
                    <a href="list.php" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i> Clear Filter
                    </a>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Tasks Table -->
<div class="card">
    <div class="card-body">
        <?php if ($result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($task = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $task['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($task['title']); ?></strong></td>
                                <td><?php echo htmlspecialchars(substr($task['description'], 0, 50)) . (strlen($task['description']) > 50 ? '...' : ''); ?></td>
                                <td>
                                    <span class="badge status-<?php echo strtolower(str_replace(' ', '-', $task['status'])); ?>">
                                        <?php echo htmlspecialchars($task['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($task['created_by_name'] ?? 'N/A'); ?></td>
                                <td><?php echo date('M d, Y h:i A', strtotime($task['created_at'])); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="edit.php?id=<?php echo $task['id']; ?>" class="btn btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="delete.php?id=<?php echo $task['id']; ?>" 
                                           class="btn btn-outline-danger" 
                                           title="Delete"
                                           onclick="return confirm('Are you sure you want to delete this task?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h5>No tasks found</h5>
                <p class="text-muted">
                    <?php echo $filter_status ? 'No tasks with status "' . htmlspecialchars($filter_status) . '".' : 'You haven\'t created any tasks yet.'; ?>
                </p>
                <a href="create.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Create Your First Task
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<?php $conn->close(); ?>

