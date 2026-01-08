<?php
// Determine base path based on current file location
$current_file = $_SERVER['PHP_SELF'];
$base_path = (strpos($current_file, '/tasks/') !== false || strpos($current_file, '/auth/') !== false) ? '../' : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Task Manager'; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo $base_path; ?>assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php if (isset($_SESSION['user_id'])) { ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?php echo $base_path; ?>dashboard.php">
                <i class="bi bi-check2-square"></i> Task Manager
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_path; ?>dashboard.php">
                            <i class="bi bi-house"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_path; ?>tasks/create.php">
                            <i class="bi bi-plus-circle"></i> Create Task
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_path; ?>tasks/list.php">
                            <i class="bi bi-list-ul"></i> View Tasks
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_path; ?>auth/logout.php">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php } ?>
    <div class="container mt-4">

