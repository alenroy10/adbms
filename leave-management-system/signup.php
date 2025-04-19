<?php
require_once __DIR__ . '/config/db.php';

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

<h2>Sign Up</h2>
<?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
<form method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br>

    <input type="submit" value="Sign Up">
</form>

<?php include __DIR__ . '/includes/footer.php'; ?>