<?php
include 'includes/header.php';
require_once 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: profile.php');
    exit();
}

$order_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    echo "<div class='container my-5'><h3>Order not found.</h3></div>";
    include 'includes/footer.php';
    exit();
}

$order_number = !empty($order['order_number']) ? $order['order_number'] : '#' . strtoupper(substr(md5($order_id), 0, 8));

$item_stmt = $conn->prepare("SELECT oi.*, p.name AS product_name, p.image 
                             FROM order_items oi 
                             JOIN products p ON oi.product_id = p.id 
                             WHERE oi.order_id = ?");
$item_stmt->bind_param("i", $order_id);
$item_stmt->execute();
$items = $item_stmt->get_result();
?>

<style>
    .order-details-container {
        background-color: #f8fafc;
        min-height: 85vh;
        padding: 50px 0 80px;
    }

    .details-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        border: 1px solid #f1f5f9;
        margin-bottom: 25px;
    }

    .order-top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 15px;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .order-title {
        font-size: 1.4rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
    }

    .order-status-badge {
        background-color: #dbeafe;
        color: #1d4ed8;
        font-size: 0.8rem;
        font-weight: 700;
        padding: 6px 16px;
        border-radius: 20px;
        text-transform: uppercase;
    }

    .info-label {
        font-size: 0.85rem;
        color: #94a3b8;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 1rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 15px;
    }

    .item-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .item-row:last-child {
        border-bottom: none;
    }

    .item-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }

    .item-name {
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }

    .item-qty {
        font-size: 0.85rem;
        color: #64748b;
    }

    .item-price {
        font-size: 1.05rem;
        font-weight: 800;
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
        display: inline-block;
        transition: all 0.2s ease;
    }

    .btn-download-pdf:hover {
        background-color: #1d4ed8;
        color: #ffffff;
    }

    .btn-back {
        background-color: #f1f5f9;
        color: #334155;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        display: inline-block;
    }
</style>

<div class="order-details-container">
    <div class="container" style="max-width: 900px;">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="profile.php" class="btn-back"><i class="fa-solid fa-arrow-left me-1"></i> Back to Profile</a>
            <a href="download-invoice.php?id=<?php echo $order['id']; ?>" target="_blank" class="btn-download-pdf">
                <i class="fa-solid fa-file-pdf me-1"></i> Download Invoice PDF
            </a>
        </div>

        <div class="details-card">
            <div class="order-top-bar">
                <div>
                    <h1 class="order-title">Order <?php echo htmlspecialchars($order_number); ?></h1>
                    <small class="text-muted">Placed on <?php echo date('F j, Y, g:i a', strtotime($order['created_at'])); ?></small>
                </div>
                <span class="order-status-badge"><?php echo htmlspecialchars($order['status'] ?? 'PLACED'); ?></span>
            </div>

            <h5 class="fw-bold mb-3">Order Items</h5>
            <div class="mb-4">
                <?php while ($item = $items->fetch_assoc()): 
                    $img_src = (!empty($item['image']) && file_exists('uploads/' . $item['image'])) ? 'uploads/' . $item['image'] : 'https://via.placeholder.com/60';
                ?>
                    <div class="item-row">
                        <div class="d-flex align-items-center gap-3">
                            <img src="<?php echo $img_src; ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" class="item-img">
                            <div>
                                <p class="item-name"><?php echo htmlspecialchars($item['product_name']); ?></p>
                                <span class="item-qty">Quantity: <?php echo $item['quantity']; ?></span>
                            </div>
                        </div>
                        <div class="item-price">
                            ₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="row pt-3 border-top">
                <div class="col-md-6">
                    <div class="info-label">Payment Mode</div>
                    <div class="info-value"><?php echo strtoupper($order['payment_method'] ?? 'COD'); ?></div>

                    <div class="info-label">Shipping Address</div>
                    <div class="info-value" style="font-weight: 500;">
                        <?php echo nl2br(htmlspecialchars($order['address'] ?? 'Address details not specified')); ?>
                    </div>
                </div>

                <div class="col-md-6 text-md-end">
                    <div class="info-label">Total Amount Paid</div>
                    <div class="fs-3 fw-bold text-primary">₹<?php echo number_format($order['total_amount'], 2); ?></div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include 'includes/footer.php'; ?>