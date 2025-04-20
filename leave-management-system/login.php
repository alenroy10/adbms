<?php
require_once __DIR__ . '/config/db.php';

session_start();

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

<h2>Login</h2>
<?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
<form method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br>

    <input type="submit" value="Login">
</form>

<?php include __DIR__ . '/includes/footer.php'; ?>