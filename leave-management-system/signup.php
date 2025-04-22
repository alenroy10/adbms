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
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];

    $users = $db->users;
    $existingUser = $users->findOne(['username' => $username]);

    if ($existingUser) {
        $error = "Username already exists";
    } else {
        $result = $users->insertOne([
            'username' => $username,
            'password' => $password,
            'email' => $email
        ]);

        if ($result->getInsertedCount() > 0) {
            $_SESSION['user_id'] = (string) $result->getInsertedId();
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Error creating user";
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<div class="auth-form-container animate-slide-up">
    <div class="card shadow">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <i class="fa-solid fa-user-plus text-primary fa-3x mb-3"></i>
                <h2 class="auth-form-heading">Create an Account</h2>
                <p class="auth-form-subheading">Join our leave management system</p>
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
                               placeholder="Choose a username" required autofocus>
                        <div class="invalid-feedback">Please choose a username.</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email" 
                               placeholder="Enter your email" required>
                        <div class="invalid-feedback">Please enter a valid email address.</div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="Create a password" required>
                        <div class="invalid-feedback">Please create a password.</div>
                    </div>
                    <div class="form-text">Password must be at least 6 characters long.</div>
                </div>

                <div class="d-grid mb-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fa-solid fa-user-plus me-2"></i>Sign Up
                    </button>
                </div>
                
                <div class="text-center">
                    <p class="mb-0">Already have an account? <a href="login.php" class="fw-medium">Login here</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>