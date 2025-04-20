<?php
use MongoDB\BSON\ObjectId;

require_once __DIR__ . '/config/db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$leaveRequests = $db->leave_requests;
$requests = $leaveRequests->find(['user_id' => new ObjectId($_SESSION['user_id'])]);

include __DIR__ . '/includes/header.php';
?>

<h2>Your Leave History</h2>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Leave Type</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Reason</th>
            <th>Status</th>
            <th>Requested At</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($requests as $request): ?>
            <tr>
                <td><?php echo htmlspecialchars($request['leave_type']); ?></td>
                <td><?php echo htmlspecialchars($request['start_date']); ?></td>
                <td><?php echo htmlspecialchars($request['end_date']); ?></td>
                <td><?php echo htmlspecialchars($request['reason'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars(ucfirst($request['status'])); ?></td>
                <td><?php echo htmlspecialchars($request['requested_at']->toDateTime()->format('Y-m-d H:i:s')); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include __DIR__ . '/includes/footer.php'; ?>
