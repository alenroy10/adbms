<?php
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

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

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $leave_type = $_POST['leave_type'] ?? '';
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $reason = $_POST['reason'] ?? '';

    if (!$leave_type || !$start_date || !$end_date) {
        $error = 'Please fill in all required fields.';
    } else {
        $leaveRequests = $db->leave_requests;
        $insertResult = $leaveRequests->insertOne([
            'user_id' => new ObjectId($_SESSION['user_id']),
            'leave_type' => $leave_type,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'reason' => $reason,
            'status' => 'pending',
            'requested_at' => new UTCDateTime()
        ]);

        if ($insertResult->getInsertedCount() > 0) {
            $success = 'Leave request submitted successfully.';
        } else {
            $error = 'Failed to submit leave request.';
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<h2>Request Leave</h2>

<?php if ($error): ?>
    <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<?php if ($success): ?>
    <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
<?php endif; ?>

<form method="POST" action="leave_request.php">
    <label for="leave_type">Leave Type:</label>
    <select id="leave_type" name="leave_type" required>
        <option value="">Select Leave Type</option>
        <option value="Sick Leave">Sick Leave</option>
        <option value="Casual Leave">Casual Leave</option>
        <option value="Earned Leave">Earned Leave</option>
        <option value="Maternity Leave">Maternity Leave</option>
    </select><br><br>

    <label for="start_date">Start Date:</label>
    <input type="date" id="start_date" name="start_date" required><br><br>

    <label for="end_date">End Date:</label>
    <input type="date" id="end_date" name="end_date" required><br><br>

    <label for="reason">Reason (optional):</label><br>
    <textarea id="reason" name="reason" rows="4" cols="50"></textarea><br><br>

    <input type="submit" value="Submit Request">
</form>

<?php include __DIR__ . '/includes/footer.php'; ?>
