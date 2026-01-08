<?php
/**
 * Dashboard Page
 * Main dashboard showing statistics and recent tasks
 */
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

require_once 'config/db.php';

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'User';

// Get statistics
$stats_query = "SELECT 
    COUNT(*) as total_tasks,
    SUM(CASE WHEN status = 'Open' THEN 1 ELSE 0 END) as open_tasks,
    SUM(CASE WHEN status = 'In Progress' THEN 1 ELSE 0 END) as in_progress_tasks,
    SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) as completed_tasks
    FROM tasks WHERE created_by = ?";
$stmt = $conn->prepare($stats_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stats = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Get recent tasks
$recent_query = "SELECT t.*, u.username as created_by_name 
                 FROM tasks t 
                 LEFT JOIN users u ON t.created_by = u.id 
                 WHERE t.created_by = ? 
                 ORDER BY t.created_at DESC 
                 LIMIT 5";
$stmt = $conn->prepare($recent_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$recent_tasks = $stmt->get_result();
$stmt->close();

$pageTitle = 'Dashboard - Task Manager';
include 'includes/header.php';
?>

<div class="page-header">
    <h2><i class="bi bi-speedometer2"></i> Dashboard</h2>
    <p class="text-muted">Welcome back, <strong><?php echo htmlspecialchars($username); ?></strong>!</p>
</div>

<!-- Statistics Cards -->
<div class="row dashboard-stats">
    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-primary text-white">
            <div class="stat-number"><?php echo $stats['total_tasks'] ?? 0; ?></div>
            <div class="stat-label">Total Tasks</div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-info text-white">
            <div class="stat-number"><?php echo $stats['open_tasks'] ?? 0; ?></div>
            <div class="stat-label">Open Tasks</div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-warning text-dark">
            <div class="stat-number"><?php echo $stats['in_progress_tasks'] ?? 0; ?></div>
            <div class="stat-label">In Progress</div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-success text-white">
            <div class="stat-number"><?php echo $stats['completed_tasks'] ?? 0; ?></div>
            <div class="stat-label">Completed</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-lightning-charge"></i> Quick Actions</h5>
            </div>
            <div class="card-body">
                <a href="tasks/create.php" class="btn btn-primary me-2">
                    <i class="bi bi-plus-circle"></i> Create New Task
                </a>
                <a href="tasks/list.php" class="btn btn-outline-primary">
                    <i class="bi bi-list-ul"></i> View All Tasks
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Tasks -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Tasks</h5>
                <a href="tasks/list.php" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                <?php if ($recent_tasks->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($task = $recent_tasks->fetch_assoc()): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($task['title']); ?></strong></td>
                                        <td><?php echo htmlspecialchars(substr($task['description'], 0, 50)) . (strlen($task['description']) > 50 ? '...' : ''); ?></td>
                                        <td>
                                            <span class="badge status-<?php echo strtolower(str_replace(' ', '-', $task['status'])); ?>">
                                                <?php echo htmlspecialchars($task['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($task['created_at'])); ?></td>
                                        <td>
                                            <a href="tasks/edit.php?id=<?php echo $task['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <p>No tasks yet. Create your first task to get started!</p>
                        <a href="tasks/create.php" class="btn btn-primary">Create Task</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
<?php $conn->close(); ?>


