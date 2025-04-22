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
$requests = $leaveRequests->find(['user_id' => $_SESSION['user_id']], ['sort' => ['requested_at' => -1]]);

// Count by status
$pendingCount = $leaveRequests->countDocuments(['user_id' => $_SESSION['user_id'], 'status' => 'pending']);
$approvedCount = $leaveRequests->countDocuments(['user_id' => $_SESSION['user_id'], 'status' => 'approved']);
$rejectedCount = $leaveRequests->countDocuments(['user_id' => $_SESSION['user_id'], 'status' => 'rejected']);

include __DIR__ . '/includes/header.php';
?>

<div class="page-header animate-slide-up">
    <h1>My Leave History</h1>
    <p>View and manage all your leave requests</p>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4 animate-slide-up">
        <div class="card h-100 border-start border-5 border-primary bg-primary-light">
            <div class="card-body d-flex align-items-center">
                <div class="flex-shrink-0 me-3">
                    <i class="fa-solid fa-clock text-primary fa-2x"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Pending</h6>
                    <h3 class="mb-0"><?php echo $pendingCount; ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 animate-slide-up" style="animation-delay: 100ms;">
        <div class="card h-100 border-start border-5 border-success" style="border-color: var(--success) !important">
            <div class="card-body d-flex align-items-center">
                <div class="flex-shrink-0 me-3">
                    <i class="fa-solid fa-check-circle text-success fa-2x"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Approved</h6>
                    <h3 class="mb-0"><?php echo $approvedCount; ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 animate-slide-up" style="animation-delay: 200ms;">
        <div class="card h-100 border-start border-5 border-danger" style="border-color: var(--danger) !important">
            <div class="card-body d-flex align-items-center">
                <div class="flex-shrink-0 me-3">
                    <i class="fa-solid fa-times-circle text-danger fa-2x"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Rejected</h6>
                    <h3 class="mb-0"><?php echo $rejectedCount; ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4 animate-slide-up" style="animation-delay: 300ms;">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="mb-0">Leave Request History</h3>
        <a href="leave_request.php" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus me-1"></i>New Request
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Leave Type</th>
                        <th>Dates</th>
                        <th>Status</th>
                        <th>Reason</th>
                        <th>Requested</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $hasRequests = false; ?>
                    <?php foreach ($requests as $request): $hasRequests = true; ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php
                                    $icon = 'fa-calendar';
                                    if (strpos($request['leave_type'], 'Sick') !== false) {
                                        $icon = 'fa-briefcase-medical';
                                    } elseif (strpos($request['leave_type'], 'Casual') !== false) {
                                        $icon = 'fa-umbrella-beach';
                                    } elseif (strpos($request['leave_type'], 'Earned') !== false) {
                                        $icon = 'fa-business-time';
                                    } elseif (strpos($request['leave_type'], 'Maternity') !== false) {
                                        $icon = 'fa-baby';
                                    }
                                    ?>
                                    <i class="fa-solid <?php echo $icon; ?> me-2 text-primary"></i>
                                    <?php echo htmlspecialchars($request['leave_type']); ?>
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    <div><i class="fa-solid fa-calendar-day me-1"></i> From: <?php echo htmlspecialchars(date('M d, Y', strtotime($request['start_date']))); ?></div>
                                    <div><i class="fa-solid fa-calendar-day me-1"></i> To: <?php echo htmlspecialchars(date('M d, Y', strtotime($request['end_date']))); ?></div>
                                    <?php 
                                    $days = (strtotime($request['end_date']) - strtotime($request['start_date'])) / (60 * 60 * 24) + 1;
                                    ?>
                                    <span class="badge bg-light text-dark mt-1"><?php echo $days; ?> day<?php echo $days > 1 ? 's' : ''; ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge status-<?php echo strtolower($request['status']); ?>">
                                    <?php echo htmlspecialchars(ucfirst($request['status'])); ?>
                                </span>
                            </td>
                            <td>
                                <?php if(!empty($request['reason'])): ?>
                                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo htmlspecialchars($request['reason']); ?>">
                                        <?php echo substr(htmlspecialchars($request['reason']), 0, 30) . (strlen($request['reason']) > 30 ? '...' : ''); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted fst-italic">No reason provided</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo htmlspecialchars($request['requested_at']->toDateTime()->format('Y-m-d H:i:s')); ?>">
                                    <?php echo htmlspecialchars($request['requested_at']->toDateTime()->format('M d, Y')); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($request['status'] === 'pending'): ?>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="if(confirm('Are you sure you want to cancel this leave request?')) { alert('Feature not implemented yet'); }">
                                        <i class="fa-solid fa-times me-1"></i>Cancel
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" disabled>
                                        <i class="fa-solid fa-lock me-1"></i>Finalized
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$hasRequests): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fa-solid fa-calendar-xmark text-muted fa-3x mb-3"></i>
                                <p class="mb-0">No leave requests found</p>
                                <a href="leave_request.php" class="btn btn-primary mt-3">Request Your First Leave</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if ($hasRequests): ?>
    <div class="card-footer text-center">
        <a href="leave_request.php" class="btn btn-primary">
            <i class="fa-solid fa-plus-circle me-2"></i>Request New Leave
        </a>
        <a href="dashboard.php" class="btn btn-outline-secondary ms-2">
            <i class="fa-solid fa-tachometer-alt me-2"></i>Back to Dashboard
        </a>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
