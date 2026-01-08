<?php
/**
 * Delete Task Page
 * Handles task deletion
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

// Verify task exists and belongs to user, then delete
$stmt = $conn->prepare("DELETE FROM tasks WHERE id = ? AND created_by = ?");
$stmt->bind_param("ii", $task_id, $user_id);

if ($stmt->execute()) {
    $_SESSION['success_message'] = 'Task deleted successfully!';
} else {
    $_SESSION['error_message'] = 'Error deleting task: ' . $conn->error;
}

$stmt->close();
$conn->close();

// Redirect back to list
header("Location: list.php");
exit();


