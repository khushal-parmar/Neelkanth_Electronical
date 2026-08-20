<?php
ini_set('session.cookie_lifetime', 0);
ini_set('session.gc_maxlifetime', 0);
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

require_once '../config/db.php';
$sql = "SELECT * FROM products ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { background-color: #f8fafc; overflow-x: hidden; }

        .admin-wrapper { display: flex; height: 100vh; overflow: hidden; }

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

        .sidebar-menu { list-style: none; padding: 20px 12px; margin: 0; }
        .sidebar-menu li { margin-bottom: 6px; }

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

        .main-content { flex-grow: 1; overflow-y: auto; padding: 30px; height: 100vh; }

        .card-custom {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            padding: 24px;
        }

        .table th { background-color: #f8fafc; color: #475569; font-weight: 600; }
        .product-img { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; }
    </style>
</head>
<body>

<div class="admin-wrapper">
    <aside class="sidebar">
        <div>
            <a href="products.php" class="sidebar-brand">
                <i class="fa-solid fa-bolt text-warning"></i> Neelkanth
            </a>
            <ul class="sidebar-menu">
                <li><a href="products.php" class="active"><i class="fa-solid fa-box"></i> Products</a></li>
                <li><a href="orders.php"><i class="fa-solid fa-cart-shopping"></i> Orders</a></li>
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

    <main class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-dark m-0">Products List</h2>
            <a href="add-product.php" class="btn btn-primary fw-semibold">
                <i class="fa-solid fa-plus me-1"></i> Add New Product
            </a>
        </div>

        <div class="card-custom">
            <?php if ($result && $result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="fw-bold">#<?php echo $row['id']; ?></td>
                                    <td>
                                        <?php if(!empty($row['image'])): ?>
                                            <img src="../uploads/<?php echo htmlspecialchars($row['image']); ?>" class="product-img" alt="product">
                                        <?php else: ?>
                                            <div class="product-img bg-light d-flex align-items-center justify-content-center text-muted">No Img</div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="fw-semibold text-dark"><?php echo htmlspecialchars($row['title'] ?? $row['name'] ?? 'N/A'); ?></td>
                                    <td class="text-primary fw-bold">₹<?php echo number_format($row['price'], 2); ?></td>
                                    <td>
                                        <a href="edit-product.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fa-solid fa-pen"></i></a>
                                        <a href="delete-product.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?');"><i class="fa-solid fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-4 text-muted">
                    <i class="fa-solid fa-box-open fs-1 mb-2"></i>
                    <p class="mb-0">No products found.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

</body>
</html>