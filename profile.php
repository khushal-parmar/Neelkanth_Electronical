<?php
include 'includes/header.php';
require_once 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$user_stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_info = $user_stmt->get_result()->fetch_assoc();
?>

<style>
    .profile-page-container {
        background-color: #f8fafc;
        min-height: 85vh;
        padding: 50px 0 80px;
    }

    .profile-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        border: 1px solid #f1f5f9;
        height: 100%;
    }

    .card-head-title {
        font-size: 1.35rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-group {
        margin-bottom: 20px;
    }

    .info-label {
        font-size: 0.85rem;
        color: #94a3b8;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }

    .order-item-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        background: #ffffff;
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 4px;
    }

    .order-id {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
    }

    .order-status-badge {
        background-color: #dbeafe;
        color: #1d4ed8;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .order-date {
        color: #94a3b8;
        font-size: 0.88rem;
        margin-bottom: 12px;
    }

    .product-tag {
        display: inline-block;
        background-color: #f1f5f9;
        color: #475569;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 20px;
        margin-bottom: 18px;
    }

    .order-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .order-total {
        font-size: 1.2rem;
        font-weight: 800;
        color: #0f172a;
    }

    .btn-view-details {
        background-color: #f1f5f9;
        color: #1e293b;
        font-weight: 600;
        font-size: 0.88rem;
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-view-details:hover {
        background-color: #e2e8f0;
        color: #0f172a;
    }

    .btn-download-pdf {
        background-color: #2563eb;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.88rem;
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-download-pdf:hover {
        background-color: #1d4ed8;
        color: #ffffff;
    }
</style>

<div class="profile-page-container">
    <div class="container">
        <div class="row g-4">
            
            <div class="col-lg-4">
                <div class="profile-card">
                    <h2 class="card-head-title">
                        <i class="fa-solid fa-user text-primary"></i> My Profile
                    </h2>

                    <div class="info-group">
                        <div class="info-label">Full Name</div>
                        <p class="info-value"><?php echo htmlspecialchars($user_info['name'] ?? 'kishan'); ?></p>
                    </div>

                    <div class="info-group">
                        <div class="info-label">Email Address</div>
                        <p class="info-value"><?php echo htmlspecialchars($user_info['email'] ?? 'kishan@gmail.com'); ?></p>
                    </div>

                    <div class="info-group">
                        <div class="info-label">Phone Number</div>
                        <p class="info-value"><?php echo htmlspecialchars($user_info['phone'] ?? '9876543211'); ?></p>
                    </div>

                    <div class="info-group mb-0">
                        <div class="info-label">Member Since</div>
                        <p class="info-value"><?php echo date('d M, Y', strtotime($user_info['created_at'] ?? '2026-08-19')); ?></p>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="profile-card">
                    <h2 class="card-head-title">
                        <i class="fa-solid fa-box text-primary"></i> My Orders
                    </h2>

                    <?php
                    $order_query = "SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC";
                    $order_stmt = $conn->prepare($order_query);
                    $order_stmt->bind_param("i", $user_id);
                    $order_stmt->execute();
                    $orders_result = $order_stmt->get_result();

                    if ($orders_result && $orders_result->num_rows > 0) {
                        while ($order = $orders_result->fetch_assoc()) {
                            $order_id = $order['id'];
                            
                            $order_number = !empty($order['order_number']) ? $order['order_number'] : '#' . strtoupper(substr(md5($order_id), 0, 8));

                            $item_stmt = $conn->prepare("SELECT p.name AS product_name, oi.quantity 
                                                         FROM order_items oi 
                                                         JOIN products p ON oi.product_id = p.id 
                                                         WHERE oi.order_id = ? LIMIT 1");
                            $item_stmt->bind_param("i", $order_id);
                            $item_stmt->execute();
                            $item_res = $item_stmt->get_result()->fetch_assoc();

                            $item_tag = $item_res ? $item_res['product_name'] . ' x' . $item_res['quantity'] : 'Products Purchased';
                    ?>
                            <div class="order-item-card">
                                <div class="order-header">
                                    <span class="order-id">Order <?php echo htmlspecialchars($order_number); ?></span>
                                    <span class="order-status-badge"><?php echo htmlspecialchars($order['status'] ?? 'PLACED'); ?></span>
                                </div>
                                
                                <div class="order-date">
                                    <?php echo date('n/j/Y, g:i:s a', strtotime($order['created_at'])); ?>
                                </div>

                                <div>
                                    <span class="product-tag"><?php echo htmlspecialchars($item_tag); ?></span>
                                </div>

                                <div class="order-footer">
                                    <div class="order-total">
                                        Total: ₹<?php echo number_format($order['total_amount'], 0); ?>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="order-details.php?id=<?php echo $order['id']; ?>" class="btn-view-details">View Details</a>
                                        <a href="download-invoice.php?id=<?php echo $order['id']; ?>" target="_blank" class="btn-download-pdf">Download Invoice PDF</a>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo '<p class="text-muted">No order history found.</p>';
                    }
                    ?>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>