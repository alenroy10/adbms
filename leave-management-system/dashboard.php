<?php
require_once __DIR__ . '/config/db.php';

use MongoDB\BSON\ObjectId;

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$users = $db->users;
$user = $users->findOne(['_id' => new ObjectId($_SESSION['user_id'])]);

include __DIR__ . '/includes/header.php';
?>

<h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
<p>This is your dashboard. You can request leave or view your leave history from here.</p>

<?php include __DIR__ . '/includes/footer.php'; ?>