<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/db.php';

use MongoDB\BSON\ObjectId;

$user = null;
$isAdmin = false;
if (isset($_SESSION['user_id'])) {
    $users = $db->users;
    $user = $users->findOne(['_id' => new ObjectId($_SESSION['user_id'])]);
    if ($user && isset($user['admin']) && $user['admin'] === true) {
        $isAdmin = true;
    }
}

// Determine current page for navigation highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="A professional leave management system for organizations to manage employee leave requests efficiently." />
    <meta name="keywords" content="leave management, employee leave, time off, absence management" />
    <meta name="author" content="Leave Management System" />
    <title>Leave Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/variables.css">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/components.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/theme.css">
    <!-- Favicon -->
    <link rel="shortcut icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📅</text></svg>">
</head>
<body>
    <header class="header shadow-sm">
        <div class="container">
            <nav class="navbar navbar-expand-lg py-2">
                <a href="index.php" class="navbar-brand d-flex align-items-center">
                    <i class="fa-solid fa-calendar-check text-primary me-2 fs-3"></i>
                    <span class="fw-bold">Leave Management System</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <?php if (!$user): ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $current_page == 'index.php' ? 'active' : ''; ?>" href="index.php">
                                    <i class="fa-solid fa-home me-1"></i> Home
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $current_page == 'login.php' ? 'active' : ''; ?>" href="login.php">
                                    <i class="fa-solid fa-sign-in-alt me-1"></i> Login
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-primary text-white px-3 ms-2" href="signup.php">
                                    <i class="fa-solid fa-user-plus me-1"></i> Sign Up
                                </a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $current_page == 'index.php' ? 'active' : ''; ?>" href="index.php">
                                    <i class="fa-solid fa-home me-1"></i> Home
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">
                                    <i class="fa-solid fa-gauge-high me-1"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $current_page == 'leave_request.php' ? 'active' : ''; ?>" href="leave_request.php">
                                    <i class="fa-solid fa-calendar-plus me-1"></i> Request Leave
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $current_page == 'leave_list.php' ? 'active' : ''; ?>" href="leave_list.php">
                                    <i class="fa-solid fa-history me-1"></i> Leave History
                                </a>
                            </li>
                            <?php if ($isAdmin): ?>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo $current_page == 'admin.php' ? 'active' : ''; ?>" href="admin.php">
                                        <i class="fa-solid fa-user-shield me-1"></i> Admin Panel
                                    </a>
                                </li>
                            <?php endif; ?>
                            <li class="nav-item">
                                <a class="nav-link btn btn-outline-danger px-3 ms-2" href="logout.php">
                                    <i class="fa-solid fa-sign-out-alt me-1"></i> Logout
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </nav>
        </div>
    </header>
    <main class="py-4">
        <div class="container">
