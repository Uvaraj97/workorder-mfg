# Task Manager - Simple Helpdesk / Task Management System

A professional **Core PHP** task management system with CRUD operations, authentication, and session management. Perfect for demonstrating core PHP skills and industry-relevant practices.

## 🎯 Project Features

- ✅ **User Authentication** - Secure login and logout with session management
- ✅ **Role-Based Access** - Admin and User roles
- ✅ **Task Management** - Full CRUD operations (Create, Read, Update, Delete)
- ✅ **Dashboard** - Statistics and recent tasks overview
- ✅ **Task Filtering** - Filter tasks by status
- ✅ **Secure Database** - Prepared statements to prevent SQL injection
- ✅ **Modern UI** - Bootstrap 5 with responsive design

## 🧱 Tech Stack

| Layer | Technology |
|-------|-----------|
| **Backend** | PHP (Core PHP - No Framework) |
| **Database** | MySQL |
| **Frontend** | HTML, CSS, Bootstrap 5 |
| **Server** | XAMPP / WAMP / LAMP |

## 📁 Project Structure

```
workorder-mfg/
│
├── assets/
│   └── css/
│       └── style.css          # Custom styles
│
├── auth/
│   ├── login.php              # Login page and authentication
│   └── logout.php             # Session destruction and logout
│
├── config/
│   └── db.php                 # Database connection configuration
│
├── database/
│   ├── setup.sql              # Database schema setup
│   └── sample_data.sql        # Sample data for testing
│
├── includes/
│   ├── header.php             # Common header with navigation
│   └── footer.php             # Common footer
│
├── tasks/
│   ├── create.php             # Create new task
│   ├── edit.php               # Edit existing task
│   ├── delete.php             # Delete task
│   └── list.php               # List all tasks with filtering
│
├── dashboard.php              # Main dashboard with statistics
├── index.php                  # Entry point (redirects to login/dashboard)
└── README.md                  # This file
```

## 🚀 Installation & Setup

### Prerequisites

- **XAMPP** (or WAMP/LAMP) installed
- **PHP 7.4+** 
- **MySQL 5.7+** or **MariaDB 10.2+**
- **Web Browser** (Chrome, Firefox, Edge, etc.)

### Step 1: Clone/Download Project

Place the `workorder-mfg` folder in your web server directory:
- **XAMPP**: `C:\xampp\htdocs\workorder-mfg\`
- **WAMP**: `C:\wamp64\www\workorder-mfg\`
- **LAMP**: `/var/www/html/workorder-mfg/`

### Step 2: Database Setup

1. Open **phpMyAdmin** (http://localhost/phpmyadmin)
2. Click on **SQL** tab
3. Copy and paste the contents of `database/setup.sql`
4. Click **Go** to create the database and tables
5. (Optional) Run `database/sample_data.sql` to add sample data

### Step 3: Configure Database Connection

Edit `config/db.php` if needed:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // Your MySQL password (if set)
define('DB_NAME', 'task_manager');
```

### Step 4: Access the Application

Open your web browser and navigate to:
```
http://localhost/workorder-mfg/
```

## 🔐 Default Login Credentials

After running `sample_data.sql`, you can use:

| Username | Password | Role |
|----------|----------|------|
| admin | admin123 | Admin |
| user1 | user123 | User |
| user2 | user123 | User |

**⚠️ Security Note**: For production use, always hash passwords using `password_hash()` and verify with `password_verify()`.

## 📊 Database Schema

### Users Table

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Tasks Table

```sql
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    status VARCHAR(20) DEFAULT 'Open',
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);
```

**Status Values**: `Open`, `In Progress`, `Completed`, `Closed`

## 🔄 Workflow

1. User opens `index.php`
2. Redirects to `auth/login.php` if not authenticated
3. Login validation → Session created
4. User lands on `dashboard.php`
5. Create / View / Update / Delete tasks
6. Logout → Session destroyed

## 🎨 Features Overview

### Dashboard
- **Statistics Cards**: Total, Open, In Progress, and Completed tasks
- **Recent Tasks**: Last 5 tasks displayed
- **Quick Actions**: Direct links to create/view tasks

### Task Management
- **Create Task**: Add new tasks with title, description, and status
- **List Tasks**: View all tasks with filtering by status
- **Edit Task**: Update task details
- **Delete Task**: Remove tasks with confirmation

### Security Features
- ✅ **Prepared Statements**: SQL injection prevention
- ✅ **Session Management**: Secure session handling
- ✅ **Input Validation**: Server-side validation
- ✅ **XSS Protection**: HTML escaping with `htmlspecialchars()`
- ✅ **Access Control**: Users can only manage their own tasks

## 🔧 Customization

### Adding Password Hashing

In `auth/login.php`, update password verification:

```php
// Replace plain text comparison with:
if (password_verify($password, $user['password'])) {
    // Login success
}
```

When creating users, hash passwords:

```php
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
```

### Changing Status Options

Edit status dropdown in `tasks/create.php` and `tasks/edit.php`:

```php
<select class="form-select" id="status" name="status">
    <option value="Open">Open</option>
    <option value="In Progress">In Progress</option>
    <option value="Completed">Completed</option>
    <option value="Closed">Closed</option>
    <!-- Add more statuses here -->
</select>
```

## 📝 Code Highlights

### Prepared Statements (Security Best Practice)

```php
// ✅ Secure - Using prepared statements
$stmt = $conn->prepare("SELECT * FROM tasks WHERE created_by = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

// ❌ Insecure - SQL Injection vulnerability
$query = "SELECT * FROM tasks WHERE created_by = $user_id";
```

### Input Sanitization

```php
// HTML escaping to prevent XSS
echo htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8');
```

## 🚀 Future Enhancements

- [ ] AJAX for seamless user experience
- [ ] REST API for mobile apps
- [ ] File attachments for tasks
- [ ] Email notifications
- [ ] Task comments/notes
- [ ] Due dates and reminders
- [ ] User roles and permissions
- [ ] Task assignment to users
- [ ] Export to CSV/Excel
- [ ] Search functionality

## 📄 License

This project is open source and available for educational purposes.

## 👨‍💻 Development

**Author**: Senior Software Architect  
**Framework**: Core PHP (No Framework)  
**Purpose**: Demonstrate core PHP skills, CRUD operations, and best practices

## 💡 Tips for Interviews

1. **MVC Structure**: Explain the separation of concerns (config, auth, tasks, includes)
2. **Security**: Highlight prepared statements and input validation
3. **Scalability**: Discuss how the code can be extended
4. **Best Practices**: Mention PSR standards and coding conventions
5. **Database Design**: Explain foreign keys, indexes, and relationships

---

**Happy Coding! 🎉**
