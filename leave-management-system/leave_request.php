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
/** @var MongoDB\Collection $users */
/** @var MongoDB\Model\BSONDocument|null $user */
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
    } elseif (strtotime($start_date) > strtotime($end_date)) {
        $error = 'Start date cannot be after end date.';
    } else {
        try {
            $leaveRequests = $db->leave_requests;
            /** @var MongoDB\InsertOneResult $insertResult */
            $insertResult = $leaveRequests->insertOne([
                'user_id' => $_SESSION['user_id'],
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
        } catch (Exception $e) {
            $error = 'An error occurred: ' . $e->getMessage();
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<div class="page-header animate-slide-up">
    <h1>Request Leave</h1>
    <p>Fill out the form below to submit a new leave request</p>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto animate-slide-up">
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h3 class="mb-0">Leave Request Form</h3>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fa-solid fa-exclamation-circle me-2"></i>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="fa-solid fa-check-circle me-2"></i>
                        <?php echo htmlspecialchars($success); ?>
                        <div class="mt-2">
                            <a href="leave_list.php" class="btn btn-sm btn-success">
                                <i class="fa-solid fa-list me-1"></i>View My Leaves
                            </a>
                            <a href="dashboard.php" class="btn btn-sm btn-light ms-2">
                                <i class="fa-solid fa-home me-1"></i>Back to Dashboard
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

                <form method="POST" action="leave_request.php" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="leave_type" class="form-label">Leave Type</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-tag"></i></span>
                            <select class="form-select" id="leave_type" name="leave_type" required>
                                <option value="">Select Leave Type</option>
                                <option value="Sick Leave">Sick Leave</option>
                                <option value="Casual Leave">Casual Leave</option>
                                <option value="Earned Leave">Earned Leave</option>
                                <option value="Maternity Leave">Maternity Leave</option>
                            </select>
                            <div class="invalid-feedback">Please select a leave type.</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="start_date" class="form-label">Start Date</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-calendar"></i></span>
                                <input type="date" class="form-control" id="start_date" name="start_date" required>
                                <div class="invalid-feedback">Please select a start date.</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="end_date" class="form-label">End Date</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-calendar"></i></span>
                                <input type="date" class="form-control" id="end_date" name="end_date" required>
                                <div class="invalid-feedback">Please select an end date.</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="reason" class="form-label">Reason (optional)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-comment"></i></span>
                            <textarea class="form-control" id="reason" name="reason" rows="4" placeholder="Enter a reason for your leave request"></textarea>
                        </div>
                        <div class="form-text">Providing a reason helps your manager understand your leave request.</div>
                    </div>

                    <div class="d-flex flex-column flex-md-row gap-2 justify-content-between">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-paper-plane me-2"></i>Submit Request
                        </button>
                        <a href="dashboard.php" class="btn btn-outline-secondary">
                            <i class="fa-solid fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card shadow-sm animate-slide-up" style="animation-delay: 100ms;">
            <div class="card-body">
                <h5 class="card-title"><i class="fa-solid fa-info-circle me-2 text-primary"></i>Important Information</h5>
                <ul class="mb-0">
                    <li>Leave requests must be submitted at least 3 days in advance (except for emergencies)</li>
                    <li>Sick leave might require supporting documents for periods longer than 3 days</li>
                    <li>All leave requests are subject to manager approval</li>
                    <li>You'll receive an email notification when your request status changes</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
