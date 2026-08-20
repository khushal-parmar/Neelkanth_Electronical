<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config/db.php';

if (!isset($_GET['id'])) {
    exit('Order ID is required.');
}

$order_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT orders.*, users.name as customer_name, users.phone as customer_phone, users.email as customer_email 
                       FROM orders 
                       LEFT JOIN users ON orders.user_id = users.id 
                       WHERE orders.id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    exit('Order not found.');
}

$order_number = !empty($order['order_number']) ? $order['order_number'] : '#ORD-' . strtoupper(substr(md5($order_id), 0, 8));

$item_stmt = $conn->prepare("SELECT oi.*, p.name AS product_name 
                             FROM order_items oi 
                             JOIN products p ON oi.product_id = p.id 
                             WHERE oi.order_id = ?");
$item_stmt->bind_param("i", $order_id);
$item_stmt->execute();
$items = $item_stmt->get_result();

$subtotal = 0;
$total_cgst = 0;
$total_sgst = 0;
$grand_total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - <?php echo htmlspecialchars($order_number); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
            background: #fff;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            border: 1px solid #eee;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }
        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .company-details h2 {
            margin: 0 0 5px 0;
            color: #0f172a;
            font-size: 24px;
        }
        .company-details p {
            margin: 2px 0;
            font-size: 13px;
            color: #555;
        }
        .title-box {
            text-align: right;
        }
        .title-box h3 {
            margin: 0 0 10px 0;
            font-size: 20px;
            color: #2563eb;
            text-transform: uppercase;
        }
        .title-box p {
            margin: 3px 0;
            font-size: 13px;
        }
        .billed-to {
            margin-bottom: 25px;
        }
        .billed-to h4 {
            margin: 0 0 5px 0;
            font-size: 14px;
            color: #888;
            text-transform: uppercase;
        }
        .billed-to p {
            margin: 2px 0;
            font-size: 14px;
            font-weight: bold;
        }
        .billed-to span {
            display: block;
            font-weight: normal;
            font-size: 13px;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th {
            background: #f8fafc;
            color: #475569;
            text-align: left;
            padding: 10px;
            font-size: 13px;
            border-bottom: 2px solid #e2e8f0;
        }
        table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }
        .summary-table {
            width: 300px;
            margin-left: auto;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 6px 10px;
            border: none;
        }
        .summary-table .grand-total {
            font-weight: bold;
            font-size: 16px;
            color: #0f172a;
            border-top: 2px solid #0f172a;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 13px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
        @media print {
            body { padding: 0; }
            .invoice-box { border: none; box-shadow: none; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<div class="no-print" style="text-align: center; margin-bottom: 20px;">
    <button onclick="window.print()" style="padding: 10px 20px; font-size: 14px; background: #2563eb; color: #fff; border: none; border-radius: 5px; cursor: pointer;">
        Print Invoice
    </button>
</div>

<div class="invoice-box">
    <div class="header">
        <div class="company-details">
            <h2>Neelkanth</h2>
            <p>123, Main Road, Jamnagar, Gujarat - 361001</p>
            <p>Phone: +91 98765 43210 | Email: neelkanth@gmail.com</p>
        </div>
        <div class="title-box">
            <h3>PURCHASE ORDER</h3>
            <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order_number); ?></p>
            <p><strong>Date:</strong> <?php echo date('j M Y, g:i a', strtotime($order['created_at'])); ?></p>
        </div>
    </div>

    <div class="billed-to">
        <h4>BILLED TO:</h4>
        <p><?php echo htmlspecialchars($order['customer_name'] ?? 'N/A'); ?></p>
        <span><?php echo htmlspecialchars($order['address'] ?? ''); ?></span>
        <span><?php echo htmlspecialchars($order['customer_email'] ?? ''); ?></span>
        <span>+91 <?php echo htmlspecialchars($order['customer_phone'] ?? ''); ?></span>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
                <th>CGST (9%)</th>
                <th>SGST (9%)</th>
                <th>Net Total</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($item = $items->fetch_assoc()): 
                $item_price = $item['price'];
                $qty = $item['quantity'];
                $item_total = $item_price * $qty;
                $cgst = $item_total * 0.09;
                $sgst = $item_total * 0.09;
                $net_total = $item_total + $cgst + $sgst;

                $subtotal += $item_total;
                $total_cgst += $cgst;
                $total_sgst += $sgst;
                $grand_total += $net_total;
            ?>
            <tr>
                <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                <td><?php echo $qty; ?></td>
                <td>Rs. <?php echo number_format($item_price, 0); ?></td>
                <td>Rs. <?php echo number_format($item_total, 0); ?></td>
                <td>Rs. <?php echo number_format($cgst, 0); ?></td>
                <td>Rs. <?php echo number_format($sgst, 0); ?></td>
                <td>Rs. <?php echo number_format($net_total, 0); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <table class="summary-table">
        <tr>
            <td>Subtotal</td>
            <td style="text-align: right;">Rs. <?php echo number_format($subtotal, 0); ?></td>
        </tr>
        <tr>
            <td>CGST (9%)</td>
            <td style="text-align: right;">Rs. <?php echo number_format($total_cgst, 2); ?></td>
        </tr>
        <tr>
            <td>SGST (9%)</td>
            <td style="text-align: right;">Rs. <?php echo number_format($total_sgst, 2); ?></td>
        </tr>
        <tr class="grand-total">
            <td>Grand Total</td>
            <td style="text-align: right;">Rs. <?php echo number_format($grand_total, 2); ?></td>
        </tr>
    </table>

    <div class="footer">
        Thank you for shopping with Neelkanth!
    </div>
</div>

</body>
</html>