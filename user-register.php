<?php
session_start();
require_once 'config/db.php';

$error_msg = "";
$success_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);

    if (!empty($name) && !empty($email) && !empty($phone) && !empty($password)) {
        
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $error_msg = "Email address already registered!";
        } else {
        
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            
            $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $phone, $hashed_password);

            if ($stmt->execute()) {
                $_SESSION['user_id'] = $stmt->insert_id;
                $_SESSION['user_name'] = $name;
 
                if (isset($_SESSION['redirect_url'])) {
                    $redirect_url = $_SESSION['redirect_url'];
                    unset($_SESSION['redirect_url']);
                    header("Location: " . $redirect_url);
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                $error_msg = "Something went wrong. Please try again.";
            }
            $stmt->close();
        }
        $check_stmt->close();
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
            <h2 class="auth-title">Create Account</h2>

            <?php if (!empty($error_msg)): ?>
                <div class="alert alert-danger py-2 mb-3 fs-6"><?php echo $error_msg; ?></div>
            <?php endif; ?>

            <form action="user-register.php" method="POST">
                <div class="mb-3">
                    <label class="form-label text-secondary fs-6">Full Name</label>
                    <input type="text" name="name" class="form-control custom-input" placeholder="Enter your full name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary fs-6">Email Address</label>
                    <input type="email" name="email" class="form-control custom-input" placeholder="Enter your email" required>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary fs-6">Phone Number</label>
                    <input type="tel" name="phone" class="form-control custom-input" placeholder="Enter 10-digit phone number" pattern="[0-9]{10}" required>
                </div>

                <div class="mb-4">
                    <label class="form-label text-secondary fs-6">Password</label>
                    <input type="password" name="password" class="form-control custom-input" placeholder="Create a password" required>
                </div>

                <button type="submit" class="btn-auth">Register</button>
            </form>

            <div class="text-center mt-4">
                <p class="text-muted mb-0">Already have an account? <a href="user-login.php" class="text-primary text-decoration-none fw-semibold">Login Here</a></p>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>