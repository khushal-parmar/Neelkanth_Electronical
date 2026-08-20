<?php
session_start();
require_once '../config/db.php';

$sql = "SELECT * FROM users ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Accounts - Admin Panel</title>
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
    </style>
</head>
<body>

<div class="admin-wrapper">
    <aside class="sidebar">
        <div>
            <a href="dashboard.php" class="sidebar-brand">
                <i class="fa-solid fa-bolt text-warning"></i> Neelkanth
            </a>
            <ul class="sidebar-menu">
                <li><a href="products.php"><i class="fa-solid fa-box"></i> Products</a></li>
                <li><a href="orders.php"><i class="fa-solid fa-cart-shopping"></i> Orders</a></li>
                <li><a href="users.php" class="active"><i class="fa-solid fa-users"></i> Users</a></li>
                <li><a href="inquiries.php"><i class="fa-solid fa-phone"></i> Inquiries</a></li>
            </ul>
        </div>
        <div class="px-3">
            <a href="logout.php" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </aside>

    <main class="main-content">
        <h2 class="fw-bold text-dark mb-4">User Accounts</h2>

        <div class="card-custom">
            <?php if ($result && $result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="fw-bold">#<?php echo $row['id']; ?></td>
                                    <td>
                                        <!-- name અથવા username જે ડેટાબેઝમાં મળે તે ડિસ્પ્લે કરશે -->
                                        <span class="fw-semibold text-dark">
                                            <?php 
                                                if (!empty($row['name'])) {
                                                    echo htmlspecialchars($row['name']);
                                                } elseif (!empty($row['username'])) {
                                                    echo htmlspecialchars($row['username']);
                                                } else {
                                                    echo "N/A";
                                                }
                                            ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['phone'] ?? 'N/A'); ?></td>
                                    <td><small class="text-muted"><?php echo date('Y-m-d H:i:s', strtotime($row['created_at'])); ?></small></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-4 text-muted">
                    <i class="fa-solid fa-user-slash fs-1 mb-2"></i>
                    <p class="mb-0">No user accounts found.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

</body>
</html>