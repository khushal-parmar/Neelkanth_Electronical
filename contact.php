<?php
require_once 'config/db.php';
include 'includes/header.php';

$success_msg = "";
$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    if (!empty($name) && !empty($phone) && !empty($email) && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO inquiries (name, phone, email, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $phone, $email, $message);
        
        if ($stmt->execute()) {
            $success_msg = "Your message has been sent successfully!";
        } else {
            $error_msg = "Something went wrong. Please try again.";
        }
        $stmt->close();
    } else {
        $error_msg = "Please fill in all required fields.";
    }
}
?>

<style>
    .contact-page {
        background-color: #f8fafc;
        padding: 50px 0 80px;
        min-height: calc(100vh - 75px - 300px);
    }

    .main-heading {
        font-size: 2.25rem;
        font-weight: 800;
        color: #0f172a;
        text-align: center;
        position: relative;
        display: inline-block;
        padding-bottom: 8px;
    }

    .main-heading::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 35px;
        height: 3px;
        background-color: #2563eb;
        border-radius: 2px;
    }

    .contact-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        border: 1px solid #f1f5f9;
        padding: 40px;
        max-width: 800px;
        margin: 0 auto;
    }

    .get-in-touch-title {
        font-size: 1.35rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 25px;
    }

    .info-text {
        color: #475569;
        font-size: 0.95rem;
        line-height: 1.5;
    }

    .custom-input {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 0.95rem;
        color: #334155;
    }

    .custom-input:focus {
        background-color: #ffffff;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .custom-input::placeholder {
        color: #94a3b8;
    }

    .btn-send-message {
        background-color: #2563eb;
        color: #ffffff;
        font-weight: 600;
        font-size: 1rem;
        padding: 12px;
        border-radius: 8px;
        border: none;
        width: 100%;
        transition: background-color 0.2s ease;
    }

    .btn-send-message:hover {
        background-color: #1d4ed8;
    }
</style>

<div class="contact-page">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="main-heading">Contact Us</h1>
        </div>

        <div class="contact-card">
            <?php if (!empty($success_msg)): ?>
                <div class="alert alert-success py-2 mb-4 fs-6"><?php echo $success_msg; ?></div>
            <?php endif; ?>

            <?php if (!empty($error_msg)): ?>
                <div class="alert alert-danger py-2 mb-4 fs-6"><?php echo $error_msg; ?></div>
            <?php endif; ?>

            <div class="row g-4">
                <div class="col-lg-5 pe-lg-4">
                    <h3 class="get-in-touch-title">Get in Touch</h3>
                    
                    <div class="d-flex align-items-start gap-2 mb-3 info-text">
                        <span class="fs-6">📍</span>
                        <span>Shop No.11 ,crystal-A,vanthli highway, Junagadh</span>
                    </div>

                    <div class="d-flex align-items-center gap-2 info-text">
                        <i class="fa-solid fa-phone-flip text-danger"></i>
                        <a href="tel:+919913550501" class="text-decoration-none text-secondary">+91 99135 50501</a>
                    </div>
                </div>

                <div class="col-lg-7">
                    <form action="contact.php" method="POST">
                        <div class="mb-3">
                            <input type="text" name="name" class="form-control custom-input" placeholder="Enter your name" required>
                        </div>

                        <div class="mb-3">
                            <input type="tel" name="phone" class="form-control custom-input" placeholder="Enter 10-digit number" pattern="[0-9]{10}" required>
                        </div>

                        <div class="mb-3">
                            <input type="email" name="email" class="form-control custom-input" placeholder="Enter your email" required>
                        </div>

                        <div class="mb-4">
                            <textarea name="message" rows="4" class="form-control custom-input" placeholder="Write your message here..." required></textarea>
                        </div>

                        <button type="submit" class="btn-send-message">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>