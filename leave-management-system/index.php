<?php
include __DIR__ . '/includes/header.php';
?>

<div class="row mt-4 animate-slide-up">
    <div class="col-lg-12">
        <div class="hero-section p-5 shadow-sm">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <h1 class="hero-title display-4">Employee Leave Management System</h1>
                    <p class="hero-description">A modern, secure, and efficient way to manage employee leaves. Streamline your leave approval process and maintain comprehensive records with our easy-to-use platform.</p>
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <div class="d-flex gap-3 mt-4">
                            <a href="login.php" class="btn btn-primary btn-lg">
                                <i class="fa-solid fa-sign-in-alt me-2"></i>Login
                            </a>
                            <a href="signup.php" class="btn btn-outline-primary btn-lg">
                                <i class="fa-solid fa-user-plus me-2"></i>Sign Up
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="mt-4">
                            <a href="dashboard.php" class="btn btn-primary btn-lg">
                                <i class="fa-solid fa-gauge-high me-2"></i>Go to Dashboard
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-lg-5 d-none d-lg-block text-center">
                    <i class="fa-solid fa-calendar-check text-primary" style="font-size: 180px; opacity: 0.8;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-lg-12">
        <h2 class="text-center mb-4">Our Features</h2>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-md-4 animate-slide-up">
        <div class="card feature-card shadow-hover h-100">
            <div class="card-body">
                <div class="feature-icon">
                    <i class="fa-solid fa-calendar-plus"></i>
                </div>
                <h3 class="feature-title">Easy Request</h3>
                <p class="feature-description">Submit leave requests with a user-friendly interface in just a few clicks.</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 animate-slide-up" style="animation-delay: 100ms;">
        <div class="card feature-card shadow-hover h-100">
            <div class="card-body">
                <div class="feature-icon">
                    <i class="fa-solid fa-check-double"></i>
                </div>
                <h3 class="feature-title">Fast Approval</h3>
                <p class="feature-description">Streamlined approval process for quick turnaround times with email notifications.</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 animate-slide-up" style="animation-delay: 200ms;">
        <div class="card feature-card shadow-hover h-100">
            <div class="card-body">
                <div class="feature-icon">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                <h3 class="feature-title">Clear History</h3>
                <p class="feature-description">Track all your leave requests in one organized dashboard with detailed analytics.</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-lg-12">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h2 class="card-title mb-4 text-center">Why Choose Our System?</h2>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="d-flex mb-3">
                            <div class="me-3">
                                <i class="fa-solid fa-shield-halved text-primary fa-2x"></i>
                            </div>
                            <div>
                                <h4>Secure & Reliable</h4>
                                <p class="text-muted">Built with industry standard security practices to keep your data safe.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex mb-3">
                            <div class="me-3">
                                <i class="fa-solid fa-gauge-high text-primary fa-2x"></i>
                            </div>
                            <div>
                                <h4>Fast & Efficient</h4>
                                <p class="text-muted">Optimized for performance to handle all your leave management needs.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex mb-3">
                            <div class="me-3">
                                <i class="fa-solid fa-laptop-code text-primary fa-2x"></i>
                            </div>
                            <div>
                                <h4>Modern Interface</h4>
                                <p class="text-muted">Intuitive and responsive design that works on all devices.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex mb-3">
                            <div class="me-3">
                                <i class="fa-solid fa-users text-primary fa-2x"></i>
                            </div>
                            <div>
                                <h4>Team Management</h4>
                                <p class="text-muted">Easily manage your team's leave requests from a central dashboard.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>