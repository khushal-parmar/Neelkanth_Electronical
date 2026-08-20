<?php
session_start();
require_once 'config/db.php';

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($email) && !empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            $message = "Account created successfully! <a href='login.php'>Login here</a>";
        } else {
            $error = "Error: Username or Email already exists.";
        }
        $stmt->close();
    } else {
        $error = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Neelkanth Electricals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #eef2f6; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .register-card { width: 100%; max-width: 420px; border-radius: 12px; border: none; }
    </style>
</head>
<body>

<div class="card register-card shadow-sm p-4 bg-white">
    <h3 class="text-center fw-bold mb-1">Create Account</h3>
    <p class="text-center text-muted small mb-4">Sign up for admin access</p>

    <?php if(!empty($message)): ?>
        <div class="alert alert-success py-2 fs-6"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if(!empty($error)): ?>
        <div class="alert alert-danger py-2 fs-6"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <div class="mb-3">
            <input type="text" name="username" class="form-control fs-6" placeholder="Username" required>
        </div>
        <div class="mb-3">
            <input type="email" name="email" class="form-control fs-6" placeholder="Email" required>
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control fs-6" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Register</button>
    </form>
    
    <div class="text-center mt-3">
        <small class="text-muted">Already have an account? <a href="login.php" class="text-decoration-none fw-bold">Login</a></small>
    </div>
</div>

</body>
</html>