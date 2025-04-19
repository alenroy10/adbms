<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Management System</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header>
        <h1>Leave Management System</h1>
        <nav>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/dashboard.php">Dashboard</a>
                <a href="/leave_request.php">Request Leave</a>
                <a href="/leave_list.php">Leave List</a>
                <a href="/logout.php">Logout</a>
            <?php else: ?>
                <a href="/login.php">Login</a>
                <a href="/signup.php">Sign Up</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>