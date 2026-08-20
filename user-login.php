<?php
session_start();
require_once 'config/db.php';

$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            
            if (password_verify($password, $row['password']) || $password == $row['password']) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['name'];

                if (isset($_SESSION['redirect_url'])) {
                    $redirect_url = $_SESSION['redirect_url'];
                    unset($_SESSION['redirect_url']);
                    header("Location: " . $redirect_url);
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                $error_msg = "Invalid password!";
            }
        } else {
            $error_msg = "No account found with this email!";
        }
        $stmt->close();
    } else {
        $error_msg = "Please fill in all fields.";
    }
}

include 'includes/header.php';
?>

<style>
    .auth-page {
        background-color: #f8fafc;
        padding: 50px 0 80px;
        min-height: calc(100vh - 75px - 300px);
        display: flex;
        align-items: center;
    }

    .auth-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        border: 1px solid #f1f5f9;
        padding: 40px;
        max-width: 450px;
        margin: 0 auto;
        width: 100%;
    }

    .auth-title {
        font-size: 1.75rem; 
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 25px;
        text-align: center;
    }
 
    .custom-input { 
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 0.95rem;
    }

    .custom-input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        background-color: #ffffff;
    }

    .btn-auth {
        background-color: #2563eb;
        color: #ffffff;
        font-weight: 600;
        padding: 12px;
        border-radius: 8px;
        border: none;
        width: 100%;
        margin-top: 10px;
        transition: background-color 0.2s;
    }

    .btn-auth:hover {
        background-color: #1d4ed8;
    }
</style>

<div class="auth-page">
    <div class="container">
        <div class="auth-card">
            <h2 class="auth-title">Customer Login</h2>

            <?php if (isset($_GET['msg']) && $_GET['msg'] == 'login_required'): ?>
                <div class="alert alert-warning py-2 mb-3 fs-6">Please login to place your order!</div>
            <?php endif; ?>

            <?php if (!empty($error_msg)): ?>
                <div class="alert alert-danger py-2 mb-3 fs-6"><?php echo $error_msg; ?></div>
            <?php endif; ?>

            <form action="user-login.php" method="POST">
                <div class="mb-3">
                    <label class="form-label text-secondary fs-6">Email Address</label>
                    <input type="email" name="email" class="form-control custom-input" placeholder="Enter your email" required>
                </div>

                <div class="mb-4">
                    <label class="form-label text-secondary fs-6">Password</label>
                    <input type="password" name="password" class="form-control custom-input" placeholder="Enter your password" required>
                </div>

                <button type="submit" class="btn-auth">Login</button>
            </form>

            <small class="text-muted">Don't have an account? <a href="register.php" class="text-decoration-none fw-bold">Create Account</a></small>
            <div class="text-center mt-3">
</div>
     
</div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
