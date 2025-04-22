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

// Get leave statistics
$leave_requests = $db->leave_requests;
$userId = new ObjectId($_SESSION['user_id']);

$pendingCount = $leave_requests->countDocuments([
    'user_id' => $_SESSION['user_id'],
    'status' => 'pending'
]);

$approvedCount = $leave_requests->countDocuments([
    'user_id' => $_SESSION['user_id'],
    'status' => 'approved'
]);

$rejectedCount = $leave_requests->countDocuments([
    'user_id' => $_SESSION['user_id'],
    'status' => 'rejected'
]);

$totalCount = $pendingCount + $approvedCount + $rejectedCount;

// Get recent leave requests (last 5)
$recentRequests = $leave_requests->find(
    ['user_id' => $_SESSION['user_id']], 
    [
        'sort' => ['requested_at' => -1],
        'limit' => 5
    ]
)->toArray();

include __DIR__ . '/includes/header.php';
?>

<div class="page-header animate-slide-up">
    <h1>Dashboard</h1>
    <p>Manage your leave requests and view statistics</p>
</div>

<div class="card welcome-card animate-slide-up mb-4">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <div class="me-4">
                <i class="fa-solid fa-user-circle text-primary fa-4x"></i>
            </div>
            <div>
                <h2 class="card-title mb-2">Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
                <p class="mb-0">Welcome to your dashboard. Here you can request leaves, view your leave history, and see your leave statistics.</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3 animate-slide-up">
        <div class="stats-card h-100">
            <div class="stats-card-title">
                <i class="fa-solid fa-clipboard-list me-2"></i>Total Requests
            </div>
            <div class="stats-card-value"><?php echo $totalCount; ?></div>
            <div class="stats-card-description">All leave requests made</div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-3 animate-slide-up" style="animation-delay: 100ms;">
        <div class="stats-card h-100">
            <div class="stats-card-title">
                <i class="fa-solid fa-clock me-2"></i>Pending
            </div>
            <div class="stats-card-value"><?php echo $pendingCount; ?></div>
            <div class="stats-card-description">Awaiting approval</div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-3 animate-slide-up" style="animation-delay: 200ms;">
        <div class="stats-card h-100">
            <div class="stats-card-title">
                <i class="fa-solid fa-check-circle me-2"></i>Approved
            </div>
            <div class="stats-card-value"><?php echo $approvedCount; ?></div>
            <div class="stats-card-description">Successfully approved</div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-3 animate-slide-up" style="animation-delay: 300ms;">
        <div class="stats-card h-100">
            <div class="stats-card-title">
                <i class="fa-solid fa-times-circle me-2"></i>Rejected
            </div>
            <div class="stats-card-value"><?php echo $rejectedCount; ?></div>
            <div class="stats-card-description">Not approved</div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-12 animate-slide-up" style="animation-delay: 400ms;">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Leave Requests</h5>
                <a href="leave_list.php" class="btn btn-sm btn-outline-primary">
                    <i class="fa-solid fa-list me-1"></i>View All
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Leave Type</th>
                                <th>Dates</th>
                                <th>Status</th>
                                <th>Requested</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($recentRequests) > 0): ?>
                                <?php foreach ($recentRequests as $request): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($request['leave_type']); ?></td>
                                        <td><?php echo htmlspecialchars($request['start_date']); ?> to <?php echo htmlspecialchars($request['end_date']); ?></td>
                                        <td>
                                            <span class="status-badge status-<?php echo strtolower($request['status']); ?>">
                                                <?php echo htmlspecialchars(ucfirst($request['status'])); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($request['requested_at']->toDateTime()->format('M d, Y')); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-3">No leave requests found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-12 animate-slide-up" style="animation-delay: 500ms;">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row gap-3 justify-content-center">
                    <a href="leave_request.php" class="btn btn-primary">
                        <i class="fa-solid fa-plus-circle me-2"></i>Request New Leave
                    </a>
                    <a href="leave_list.php" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-history me-2"></i>View Leave History
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>