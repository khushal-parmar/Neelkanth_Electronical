<?php
session_start();
require_once '../config/db.php';

// Handle Status Update AJAX or POST Request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    $update_stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $update_stmt->bind_param("si", $new_status, $order_id);
    
    if ($update_stmt->execute()) {
        $_SESSION['success_msg'] = "Order #{$order_id} status updated successfully!";
    } else {
        $_SESSION['error_msg'] = "Failed to update order status.";
    }
    header("Location: orders.php");
    exit();
}

// Fetch Orders with Customer Details
$sql = "SELECT orders.*, users.name as customer_name, users.phone as customer_phone, users.email as customer_email 
        FROM orders 
        LEFT JOIN users ON orders.user_id = users.id 
        ORDER BY orders.id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Orders - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f8fafc;
            overflow-x: hidden;
        }

        .admin-wrapper {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        .sidebar {
            width: 260px;
            background-color: #1e293b;
            color: #ffffff;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 20px 0;
        }

        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: #ffffff;
            padding: 0 24px 20px;
            border-bottom: 1px solid #334155;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 20px 12px;
            margin: 0;
        }

        .sidebar-menu li {
            margin-bottom: 6px;
        }

        .sidebar-menu a {
            color: #94a3b8;
            text-decoration: none;
            padding: 12px 16px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .sidebar-menu a:hover, .sidebar-menu a.active {
            background-color: #2563eb;
            color: #ffffff;
        }

        .main-content {
            flex-grow: 1;
            overflow-y: auto;
            padding: 30px;
            height: 100vh;
        }

        .card-custom {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            padding: 24px;
        }

        .table th { background-color: #f8fafc; color: #475569; font-weight: 600; }
        
        .status-select {
            font-size: 0.85rem;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .btn-print {
            background-color: #2563eb;
            color: #ffffff;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
        }

        .btn-print:hover {
            background-color: #1d4ed8;
            color: #ffffff;
        }
    </style>
</head>
<body>

<div class="admin-wrapper">
    <!-- Static Sidebar -->
    <aside class="sidebar">
        <div>
            <a href="dashboard.php" class="sidebar-brand">
                <i class="fa-solid fa-bolt text-warning"></i> Neelkanth
            </a>
            <ul class="sidebar-menu">
                <li><a href="products.php"><i class="fa-solid fa-box"></i> Products</a></li>
                <li><a href="orders.php" class="active"><i class="fa-solid fa-cart-shopping"></i> Orders</a></li>
                <li><a href="users.php"><i class="fa-solid fa-users"></i> Users</a></li>
                <li><a href="inquiries.php"><i class="fa-solid fa-phone"></i> Inquiries</a></li>
            </ul>
        </div>
        <div class="px-3">
            <a href="logout.php" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <h2 class="fw-bold text-dark mb-4">Customer Orders</h2>

        <?php if (isset($_SESSION['success_msg'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php 
                    echo $_SESSION['success_msg']; 
                    unset($_SESSION['success_msg']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card-custom">
            <?php if ($result && $result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer Name</th>
                                <th>Contact Info</th>
                                <th>Address</th>
                               .
                                <th>Status</th>
                                <th>Date</th>
                                <th>Invoice</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="fw-bold">#<?php echo $row['id']; ?></td>
                                    <td>
                                        <span class="fw-semibold text-dark"><?php echo htmlspecialchars($row['customer_name'] ?? 'N/A'); ?></span>
                                    </td>
                                    <td>
                                        <small class="d-block text-muted"><i class="fa-solid fa-phone me-1"></i><?php echo htmlspecialchars($row['customer_phone'] ?? 'N/A'); ?></small>
                                        <small class="d-block text-muted"><i class="fa-solid fa-envelope me-1"></i><?php echo htmlspecialchars($row['customer_email'] ?? 'N/A'); ?></small>
                                    </td>
                                    <td>
                                        <small><?php echo htmlspecialchars($row['address']); ?>,<br>
                                        <?php echo htmlspecialchars($row['city'] ?? ''); ?> - <?php echo htmlspecialchars($row['pincode'] ?? ''); ?></small>
                                    </td>
                             
                                    <td>
                                        <form action="orders.php" method="POST" class="d-flex align-items-center gap-1">
                                            <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                            <select name="status" class="form-select form-select-sm status-select">
                                                <option value="Pending" <?php echo ($row['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                                <option value="Confirmation" <?php echo ($row['status'] == 'Confirmation') ? 'selected' : ''; ?>>Confirmation</option>
                                                <option value="Accepted" <?php echo ($row['status'] == 'Accepted') ? 'selected' : ''; ?>>Accepted</option>
                                                <option value="Delivered" <?php echo ($row['status'] == 'Delivered') ? 'selected' : ''; ?>>Delivered</option>
                                            </select>
                                            <button type="submit" name="update_status" class="btn btn-sm btn-dark" title="Update Status">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        </form>
                                    </td>

                                    <td><small class="text-muted"><?php echo date('d M Y, h:i A', strtotime($row['created_at'])); ?></small></td>
                                    
                                    <!-- Print Invoice Button -->
                                    <td>
                                        <a href="../download-invoice.php?id=<?php echo $row['id']; ?>" target="_blank" class="btn-print">
                                            <i class="fa-solid fa-file-pdf"></i> Print Invoice
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-4 text-muted">
                    <i class="fa-solid fa-box-open fs-1 mb-2"></i>
                    <p class="mb-0">No orders found.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>