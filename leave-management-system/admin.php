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

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestId = $_POST['request_id'] ?? null;
    $action = $_POST['action'] ?? null;

    if ($requestId && in_array($action, ['approve', 'reject'])) {
        $status = $action === 'approve' ? 'approved' : 'rejected';
        try {
            $result = $leaveRequests->updateOne(
                ['_id' => new ObjectId($requestId)],
                ['$set' => ['status' => $status, 'processed_by' => $_SESSION['user_id'], 'processed_at' => new \MongoDB\BSON\UTCDateTime()]]
            );
            if ($result->getModifiedCount() > 0) {
                $success = 'Request has been ' . $status . ' successfully.';
            } else {
                $error = 'No changes were made to the request.';
            }
        } catch (Exception $e) {
            $error = 'Error processing request: ' . $e->getMessage();
        }
    }
}

// Get admin dashboard statistics
$pendingCount = $leaveRequests->countDocuments(['status' => 'pending']);
$approvedCount = $leaveRequests->countDocuments(['status' => 'approved']);
$rejectedCount = $leaveRequests->countDocuments(['status' => 'rejected']);
$totalCount = $pendingCount + $approvedCount + $rejectedCount;
$userCount = $users->countDocuments();

// Get requests with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$skip = ($page - 1) * $limit;
$totalPages = ceil($leaveRequests->countDocuments() / $limit);

$filter = [];
if (isset($_GET['status']) && in_array($_GET['status'], ['pending', 'approved', 'rejected'])) {
    $filter['status'] = $_GET['status'];
}

$requests = $leaveRequests->find(
    $filter, 
    [
        'sort' => ['requested_at' => -1],
        'skip' => $skip,
        'limit' => $limit
    ]
);

include __DIR__ . '/includes/header.php';
?>

<div class="page-header animate-slide-up d-sm-flex justify-content-between align-items-center">
    <div>
        <h1>Admin Dashboard</h1>
        <p>Manage leave requests and system users</p>
    </div>
    <div class="mt-3 mt-sm-0">
        <a href="dashboard.php" class="btn btn-outline-primary">
            <i class="fa-solid fa-user me-1"></i>Switch to User View
        </a>
    </div>
</div>

<?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show animate-slide-up" role="alert">
        <i class="fa-solid fa-check-circle me-2"></i>
        <?php echo htmlspecialchars($success); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show animate-slide-up" role="alert">
        <i class="fa-solid fa-exclamation-circle me-2"></i>
        <?php echo htmlspecialchars($error); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row g-4 mb-4">
    <div class="col-md-4 col-lg-4 col-xl-2 animate-slide-up">
        <div class="card admin-card h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fa-solid fa-users fa-3x text-primary"></i>
                </div>
                <h2 class="stats-card-value mb-2"><?php echo $userCount; ?></h2>
                <p class="stats-card-title mb-0">Total Users</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 col-lg-4 col-xl-2 animate-slide-up" style="animation-delay: 100ms;">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fa-solid fa-clipboard-list fa-3x text-primary"></i>
                </div>
                <h2 class="stats-card-value mb-2"><?php echo $totalCount; ?></h2>
                <p class="stats-card-title mb-0">Total Requests</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 col-lg-4 col-xl-2 animate-slide-up" style="animation-delay: 200ms;">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fa-solid fa-clock fa-3x text-warning"></i>
                </div>
                <h2 class="stats-card-value mb-2"><?php echo $pendingCount; ?></h2>
                <p class="stats-card-title mb-0">Pending</p>
            </div>
            <div class="card-footer p-2 text-center">
                <a href="?status=pending" class="btn btn-sm btn-warning w-100">View Pending</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 col-lg-4 col-xl-2 animate-slide-up" style="animation-delay: 300ms;">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fa-solid fa-check-circle fa-3x text-success"></i>
                </div>
                <h2 class="stats-card-value mb-2"><?php echo $approvedCount; ?></h2>
                <p class="stats-card-title mb-0">Approved</p>
            </div>
            <div class="card-footer p-2 text-center">
                <a href="?status=approved" class="btn btn-sm btn-success w-100">View Approved</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 col-lg-4 col-xl-2 animate-slide-up" style="animation-delay: 400ms;">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fa-solid fa-times-circle fa-3x text-danger"></i>
                </div>
                <h2 class="stats-card-value mb-2"><?php echo $rejectedCount; ?></h2>
                <p class="stats-card-title mb-0">Rejected</p>
            </div>
            <div class="card-footer p-2 text-center">
                <a href="?status=rejected" class="btn btn-sm btn-danger w-100">View Rejected</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 col-lg-4 col-xl-2 animate-slide-up" style="animation-delay: 500ms;">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fa-solid fa-calendar-check fa-3x text-primary"></i>
                </div>
                <div class="stats-card-value mb-2">
                    <a href="?status=" class="btn btn-primary">View All</a>
                </div>
                <p class="stats-card-title mb-0">All Requests</p>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4 animate-slide-up" style="animation-delay: 600ms;">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="mb-0">
            <?php 
                if (isset($_GET['status'])) {
                    echo ucfirst($_GET['status']) . ' ';
                }
            ?>
            Leave Requests
        </h3>
        <div class="d-flex">
            <?php if (isset($_GET['status'])): ?>
                <a href="?" class="btn btn-sm btn-outline-secondary me-2">
                    <i class="fa-solid fa-filter-circle-xmark me-1"></i>Clear Filter
                </a>
            <?php endif; ?>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-filter me-1"></i>Filter
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown">
                    <li><a class="dropdown-item" href="?">All Requests</a></li>
                    <li><a class="dropdown-item" href="?status=pending">Pending</a></li>
                    <li><a class="dropdown-item" href="?status=approved">Approved</a></li>
                    <li><a class="dropdown-item" href="?status=rejected">Rejected</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Employee</th>
                        <th>Leave Details</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Reason</th>
                        <th>Requested</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $hasRequests = false; ?>
                    <?php foreach ($requests as $request): $hasRequests = true;
                        $requestUser = $users->findOne(['_id' => new ObjectId($request['user_id'])]);
                    ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        <div class="avatar bg-primary-light text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-weight: bold;">
                                            <?php echo strtoupper(substr($requestUser['username'] ?? 'U', 0, 1)); ?>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-0"><?php echo htmlspecialchars($requestUser['username'] ?? 'Unknown'); ?></h6>
                                        <small class="text-muted"><?php echo htmlspecialchars($requestUser['email'] ?? 'No email'); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">
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
                                    <i class="fa-solid <?php echo $icon; ?> me-1"></i>
                                    <?php echo htmlspecialchars($request['leave_type']); ?>
                                </span>
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
                                    <span class="text-muted fst-italic">No reason</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo htmlspecialchars($request['requested_at']->toDateTime()->format('Y-m-d H:i:s')); ?>">
                                    <?php echo htmlspecialchars($request['requested_at']->toDateTime()->format('M d, Y')); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($request['status'] === 'pending'): ?>
                                    <div class="d-flex gap-2">
                                        <form method="POST" class="mb-0">
                                            <input type="hidden" name="request_id" value="<?php echo $request['_id']; ?>">
                                            <button type="submit" name="action" value="approve" class="btn btn-sm btn-success" title="Approve">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        </form>
                                        <form method="POST" class="mb-0">
                                            <input type="hidden" name="request_id" value="<?php echo $request['_id']; ?>">
                                            <button type="submit" name="action" value="reject" class="btn btn-sm btn-danger" title="Reject">
                                                <i class="fa-solid fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted">
                                        <i class="fa-solid fa-check-circle me-1"></i>Processed
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$hasRequests): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fa-solid fa-clipboard-list text-muted fa-3x mb-3"></i>
                                <p class="mb-0">No leave requests found</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if ($totalPages > 1): ?>
    <div class="card-footer">
        <nav aria-label="Leave request pagination">
            <ul class="pagination justify-content-center mb-0">
                <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?><?php echo isset($_GET['status']) ? '&status=' . $_GET['status'] : ''; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?><?php echo isset($_GET['status']) ? '&status=' . $_GET['status'] : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?><?php echo isset($_GET['status']) ? '&status=' . $_GET['status'] : ''; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
