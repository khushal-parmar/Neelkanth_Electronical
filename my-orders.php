<?php
include 'includes/header.php';
require_once 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
?>

<style>
    .orders-container {
        background-color: #f8fafc;
        min-height: 85vh;
        padding: 50px 0 80px;
    }

    .main-title {
        font-size: 2.2rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 25px;
    }

    .orders-card-wrapper {
        background: #ffffff;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    }

    .section-subtitle {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 20px;
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
        margin-bottom: 5px;
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
        margin-bottom: 15px;
    }

    .product-tag {
        display: inline-block;
        background-color: #f1f5f9;
        color: #475569;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 20px;
        margin-bottom: 20px;
    }

    .order-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .order-total {
        font-size: 1.25rem;
        font-weight: 800;
        color: #0f172a;
    }

    .btn-view-details {
        background-color: #f1f5f9;
        color: #1e293b;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 10px 20px;
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
        font-size: 0.9rem;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }

    .btn-download-pdf:hover {
        background-color: #1d4ed8;
        color: #ffffff;
    }
</style>

<div class="orders-container">
    <div class="container" style="max-width: 900px;">
        <h1 class="main-title">My Orders</h1>

        <div class="orders-card-wrapper">
            <h2 class="section-subtitle">Order History</h2>

            <?php
            $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $orders = $stmt->get_result();

            if ($orders && $orders->num_rows > 0) {
                while ($order = $orders->fetch_assoc()) {
                    $order_id = $order['id'];
                    $order_number = !empty($order['order_number']) ? $order['order_number'] : '#' . strtoupper(substr(md5($order_id), 0, 8));
                    
                    $item_stmt = $conn->prepare("SELECT product_name, quantity FROM order_items WHERE order_id = ? LIMIT 1");
                    $item_stmt->bind_param("i", $order_id);
                    $item_stmt->execute();
                    $item_res = $item_stmt->get_result()->fetch_assoc();
                    
                    $item_text = $item_res ? $item_res['product_name'] . ' x' . $item_res['quantity'] : 'Items';
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
                            <span class="product-tag"><?php echo htmlspecialchars($item_text); ?></span>
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
                echo '<p class="text-muted">No orders found.</p>';
            }
            ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>