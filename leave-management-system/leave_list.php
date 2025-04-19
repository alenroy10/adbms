<?php
require_once __DIR__ . '/config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$leaves = $db->leaves;
$user_leaves = $leaves->find(['user_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);

include __DIR__ . '/includes/header.php';
?>

<h2>Your Leave Requests</h2>
<table>
    <tr>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Reason</th>
        <th>Status</th>
    </tr>
    <?php foreach ($user_leaves as $leave): ?>
    <tr>
        <td><?php echo $leave['start_date']->toDateTime()->format('Y-m-d'); ?></td>
        <td><?php echo $leave['end_date']->toDateTime()->format('Y-m-d'); ?></td>
        <td><?php echo htmlspecialchars($leave['reason']); ?></td>
        <td><?php echo htmlspecialchars($leave['status']); ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<?php include __DIR__ . '/includes/footer.php'; ?><?php
require_once __DIR__ . '/config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$leaves = $db->leaves;
$user_leaves = $leaves->find(['user_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);

include __DIR__ . '/includes/header.php';
?>

<h2>Your Leave Requests</h2>
<table>
    <tr>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Reason</th>
        <th>Status</th>
    </tr>
    <?php foreach ($user_leaves as $leave): ?>
    <tr>
        <td><?php echo $leave['start_date']->toDateTime()->format('Y-m-d'); ?></td>
        <td><?php echo $leave['end_date']->toDateTime()->format('Y-m-d'); ?></td>
        <td><?php echo htmlspecialchars($leave['reason']); ?></td>
        <td><?php echo htmlspecialchars($leave['status']); ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<?php include __DIR__ . '/includes/footer.php'; ?>