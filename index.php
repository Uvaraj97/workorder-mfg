<?php
/**
 * Task Manager - Entry Point
 * Redirects to login if not authenticated, otherwise to dashboard
 */
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
} else {
    header("Location: auth/login.php");
    exit();
}


