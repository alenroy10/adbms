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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Management System</title>
    <link rel="stylesheet" href="/adbms/leave-management-system/css/style.css">
</head>
<body>
    <header>
        <h1>Leave Management System</h1>
    </header>
    <nav>
        <a href="/adbms/leave-management-system/index.php">Home</a>
        <?php if (!$user): ?>
            <a href="/adbms/leave-management-system/login.php">Login</a>
            <a href="/adbms/leave-management-system/signup.php">Sign Up</a>
        <?php else: ?>
            <a href="/adbms/leave-management-system/leave_request.php">Request Leave</a>
            <a href="/adbms/leave-management-system/leave_list.php">View Leave History</a>
            <?php if ($isAdmin): ?>
                <a href="/adbms/leave-management-system/admin.php">Admin Panel</a>
            <?php endif; ?>
            <a href="/adbms/leave-management-system/logout.php">Logout</a>
        <?php endif; ?>
    </nav>
    <main> 
