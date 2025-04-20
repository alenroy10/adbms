<?php
require_once __DIR__ . '/config/db.php';

use MongoDB\BSON\UTCDateTime;

$adminUsername = 'admin';
$adminPasswordPlain = 'admin123';
$adminEmail = 'admin@example.com';

$users = $db->users;

// Check if admin user already exists
$existingAdmin = $users->findOne(['username' => $adminUsername]);
if ($existingAdmin) {
    echo "Admin user already exists.\n";
    exit;
}

// Hash the password
$hashedPassword = password_hash($adminPasswordPlain, PASSWORD_DEFAULT);

// Insert admin user
$result = $users->insertOne([
    'username' => $adminUsername,
    'password' => $hashedPassword,
    'email' => $adminEmail,
    'admin' => true,
    'created_at' => new UTCDateTime()
]);

if ($result->getInsertedCount() > 0) {
    echo "Admin user created successfully.\n";
} else {
    echo "Failed to create admin user.\n";
}
?>
