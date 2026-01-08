-- Task Manager Sample Data
-- Execute this SQL file after running setup.sql
-- Use the database
USE task_manager;

-- Insert sample users
-- Default password: admin123 (plain text for demo)
-- In production, use password_hash() in PHP
INSERT INTO users (username, password) VALUES 
('admin', 'admin123'),
('user1', 'user123'),
('user2', 'user123')
ON DUPLICATE KEY UPDATE username=username;

-- Note: In production, passwords should be hashed using password_hash()
-- Example: INSERT INTO users (username, password) VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Insert sample tasks
-- Assuming admin user has id = 1
INSERT INTO tasks (title, description, status, created_by) VALUES
('Setup Development Environment', 'Install XAMPP, configure database, and set up the project structure.', 'Completed', 1),
('Design Database Schema', 'Create tables for users and tasks with proper relationships and indexes.', 'Completed', 1),
('Implement Authentication System', 'Create login and logout functionality with session management.', 'Completed', 1),
('Build Dashboard Interface', 'Design and implement the main dashboard with statistics and recent tasks.', 'In Progress', 1),
('Create Task CRUD Operations', 'Implement create, read, update, and delete functionality for tasks.', 'In Progress', 1),
('Add Task Filtering', 'Allow users to filter tasks by status (Open, In Progress, Completed, Closed).', 'Open', 1),
('Implement Search Functionality', 'Add search feature to find tasks by title or description.', 'Open', 1),
('Add Email Notifications', 'Send email notifications when tasks are assigned or status changes.', 'Open', 1),
('Create Admin Panel', 'Build admin interface to manage users and all tasks.', 'Open', 1),
('Add Export Functionality', 'Allow users to export tasks to CSV or Excel format.', 'Open', 1);


