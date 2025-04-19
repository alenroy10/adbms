<?php
require_once __DIR__ . '/config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $reason = $_POST['reason'];

    $leaves = $db->leaves;
    $result = $leaves->insertOne([
        'user_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id']),
        'start_date' => new MongoDB\BSON\UTCDateTime(strtotime($start_date) * 1000),
        'end_date' => new MongoDB\BSON\UTCDateTime(strtotime($end_date) * 1000),
        'reason' => $reason,
        'status' => 'pending'
    ]);

    if ($result->getInsertedCount() > 0) {
        $success = "Leave request submitted successfully";
    } else {
        $error = "Error submitting leave request";
    }
}

include __DIR__ . '/includes/header.php';
?>

<h2>Request Leave</h2>
<?php 
if (isset($error)) echo "<p style='color: red;'>$error</p>";
if (isset($success)) echo "<p style='color: green;'>$success</p>";
?>
<form method="POST">
    <label for="start_date">Start Date:</label>
    <input type="date" id="start_date" name="start_date" required><br>

    <label for="end_date">End Date:</label>
    <input type="date" id="end_date" name="end_date" required><br>

    <label for="reason">Reason:</label>
    <textarea id="reason" name="reason" required></textarea><br>

    <input type="submit" value="Submit Leave Request">
</form>

<?php include __DIR__ . '/includes/footer.php'; ?>