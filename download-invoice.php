<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    die("Unauthorized Access");
}

$order_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Fetch Order Details
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    die("Order not found");
}

// Fetch Order Items
$item_stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
$item_stmt->bind_param("i", $order_id);
$item_stmt->execute();
$items = $item_stmt->get_result();

$order_number = !empty($order['order_number']) ? $order['order_number'] : '#' . strtoupper(substr(md5($order_id), 0, 8));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - <?php echo $order_number; ?></title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; margin: 0; padding: 40px; background: #fff; }
        .invoice-box { max-width: 800px; margin: auto; border: 1px solid #eee; padding: 30px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.05); }
        .invoice-header { display: flex; justify-content: space-between; border-bottom: 2px solid #2563eb; padding-bottom: 20px; margin-bottom: 20px; }
        .company-title { font-size: 24px; font-weight: bold; color: #2563eb; }
        .invoice-title { font-size: 20px; font-weight: bold; text-align: right; }
        .details-table { width: 100%; margin-bottom: 30px; }
        .details-table td { vertical-align: top; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .items-table th { background: #f8fafc; border: 1px solid #e2e8f0; padding: 10px; text-align: left; }
        .items-table td { border: 1px solid #e2e8f0; padding: 10px; }
        .total-box { text-align: right; font-size: 18px; font-weight: bold; }
        .print-btn { background: #2563eb; color: #fff; border: none; padding: 10px 20px; font-size: 14px; border-radius: 5px; cursor: pointer; margin-bottom: 20px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>

<div class="no-print" style="text-align: center;">
    <button onclick="window.print()" class="print-btn">Print / Save as PDF</button>
</div>

<div class="invoice-box">
    <div class="invoice-header">
        <div>
            <div class="company-title">Neelkanth Electricals</div>
            <p style="margin: 5px 0; color: #666;">Quality Home Appliances</p>
        </div>
        <div>
            <div class="invoice-title">INVOICE</div>
            <p style="margin: 5px 0;">Order: <strong><?php echo htmlspecialchars($order_number); ?></strong></p>
            <p style="margin: 0; color: #666;">Date: <?php echo date('d M Y', strtotime($order['created_at'])); ?></p>
        </div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($item = $items->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                    <td>₹<?php echo number_format($item['price'], 2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="total-box">
        Total Amount: ₹<?php echo number_format($order['total_amount'], 2); ?>
    </div>
</div>

<script>
    // Auto open print dialog when page loads
    window.onload = function() {
        window.print();
    }
</script>

</body>
</html>