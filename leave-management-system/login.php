<?php
require_once __DIR__ . '/config/db.php';

session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $users = $db->users;
    $user = $users->findOne(['username' => $username]);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = (string) $user['_id'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password";
    }
}

include __DIR__ . '/includes/header.php';
?>

<div class="auth-form-container animate-slide-up">
    <div class="card shadow">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <i class="fa-solid fa-user-lock text-primary fa-3x mb-3"></i>
                <h2 class="auth-form-heading">Welcome Back</h2>
                <p class="auth-form-subheading">Sign in to your account to continue</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-2"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                        <input type="text" class="form-control" id="username" name="username" 
                               placeholder="Enter your username" required autofocus>
                        <div class="invalid-feedback">Please enter your username.</div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="Enter your password" required>
                        <div class="invalid-feedback">Please enter your password.</div>
                    </div>
                </div>

                <div class="d-grid mb-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fa-solid fa-sign-in-alt me-2"></i>Login
                    </button>
                </div>
                
                <div class="text-center">
                    <p class="mb-0">Don't have an account? <a href="signup.php" class="fw-medium">Sign up here</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>