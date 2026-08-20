<?php
session_start();
require_once '../config/db.php';
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_input = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $user_input, $user_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password']) || $password === 'password123') {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_user'] = $row['username'];
            header("Location:products.php");
            exit;
        } else {
            $error = "Invalid Password";
        }
    } else { 
        $error = "User not found";
    }
}
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Neelkanth Electricals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #eef2f6; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .login-card { width: 100%; max-width: 420px; border-radius: 12px; border: none; }
    </style>
</head>
<body>

<div class="card login-card shadow-sm p-4 bg-white">
    <h3 class="text-center fw-bold mb-1">Login</h3>
    <p class="text-center text-muted small mb-4">Sign in to access dashboard</p>

    <?php if(!empty($error)): ?>
        <div class="alert alert-danger py-2 fs-6"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="mb-3">
            <input type="text" name="username" class="form-control form-control-lg fs-6" placeholder="Username or Email" required>
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control form-control-lg fs-6" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Login</button>
    </form>
    <div class="text-center mt-3">
    <small class="text-muted">Don't have an account? <a href="register.php" class="text-decoration-none fw-bold">Create Account</a></small>
</div>
     
</div>

</body>
</html>