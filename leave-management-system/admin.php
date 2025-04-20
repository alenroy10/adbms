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

$users = $db->users;
$user = $users->findOne(['_id' => new ObjectId($_SESSION['user_id'])]);

if (!$user || !isset($user['admin']) || $user['admin'] !== true) {
    // Not an admin, redirect to dashboard or show error
    header("Location: dashboard.php");
    exit;
}

$leaveRequests = $db->leave_requests;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestId = $_POST['request_id'] ?? null;
    $action = $_POST['action'] ?? null;

    if ($requestId && in_array($action, ['approve', 'reject'])) {
        $status = $action === 'approve' ? 'approved' : 'rejected';
        $leaveRequests->updateOne(
            ['_id' => new ObjectId($requestId)],
            ['$set' => ['status' => $status]]
        );
    }
}

$requests = $leaveRequests->find([], ['sort' => ['requested_at' => -1]]);

include __DIR__ . '/includes/header.php';
?>

<h2>Admin Panel - Leave Requests</h2>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>User</th>
            <th>Leave Type</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Reason</th>
            <th>Status</th>
            <th>Requested At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($requests as $request): 
            $requestUser = $users->findOne(['_id' => $request['user_id']]);
        ?>
            <tr>
                <td><?php echo htmlspecialchars($requestUser['username'] ?? 'Unknown'); ?></td>
                <td><?php echo htmlspecialchars($request['leave_type']); ?></td>
                <td><?php echo htmlspecialchars($request['start_date']); ?></td>
                <td><?php echo htmlspecialchars($request['end_date']); ?></td>
                <td><?php echo htmlspecialchars($request['reason'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars(ucfirst($request['status'])); ?></td>
                <td><?php echo htmlspecialchars($request['requested_at']->toDateTime()->format('Y-m-d H:i:s')); ?></td>
                <td>
                    <?php if ($request['status'] === 'pending'): ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="request_id" value="<?php echo $request['_id']; ?>">
                            <button type="submit" name="action" value="approve">Approve</button>
                        </form>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="request_id" value="<?php echo $request['_id']; ?>">
                            <button type="submit" name="action" value="reject">Reject</button>
                        </form>
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include __DIR__ . '/includes/footer.php'; ?>
