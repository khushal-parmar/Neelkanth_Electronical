<?php 
require_once 'auth_check.php'; 
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Neelkanth</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { 
            background-color: #f8f9fa; 
        }
        .sidebar { 
            min-height: 100vh; 
            background-color: #1e293b; 
            color: #fff; 
            width: 250px; 
            position: fixed; 
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 20px 0;
            z-index: 1000;
        }
        .sidebar .nav-link { 
            color: #94a3b8; 
            padding: 12px 20px; 
            font-weight: 500; 
            border-radius: 8px; 
            margin: 4px 12px; 
            transition: all 0.2s ease;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { 
            background-color: #2563eb; 
            color: #fff; 
        }
        .sidebar .logout-link {
            color: #ef4444;
            border: 1px solid #ef4444;
            text-align: center;
        }
        .sidebar .logout-link:hover {
            background-color: #ef4444;
            color: #ffffff;
        }
        .main-content { 
            margin-left: 250px; 
            padding: 30px; 
            width: calc(100% - 250px);
        }
    </style>
</head>
<body>

<div class="d-flex">
    <div class="sidebar">
        <div>
            <h4 class="text-white mb-4 px-3 fw-bold">
                <i class="fas fa-bolt text-warning me-2"></i>Neelkanth
            </h4>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="products.php" class="nav-link <?php echo ($current_page == 'products.php' || $current_page == 'index.php') ? 'active' : ''; ?>">
                        <i class="fas fa-box me-2"></i> Products
                    </a>
                </li>
                <li>
                    <a href="orders.php" class="nav-link <?php echo ($current_page == 'orders.php') ? 'active' : ''; ?>">
                        <i class="fas fa-shopping-cart me-2"></i> Orders
                    </a>
                </li>
                <li>
                    <a href="users.php" class="nav-link <?php echo ($current_page == 'users.php') ? 'active' : ''; ?>">
                        <i class="fas fa-users me-2"></i> Users
                    </a>
                </li>
                <li>
                    <a href="inquiries.php" class="nav-link <?php echo ($current_page == 'inquiries.php') ? 'active' : ''; ?>">
                        <i class="fas fa-phone me-2"></i> Inquiries
                    </a>
                </li>
            </ul>
        </div>

        <div class="px-3">
            <a href="logout.php" class="nav-link logout-link">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </div>
    </div>

    <div class="main-content">