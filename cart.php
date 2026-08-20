<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// POST Method (Forms/AJAX through Product Details page)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    if ($product_id > 0) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
    }
    header("Location: cart.php");
    exit();
}

// GET Method (Direct links)
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($action == 'add' && $product_id > 0) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]++;
        } else {
            $_SESSION['cart'][$product_id] = 1;
        }
        header("Location: cart.php");
        exit();
    }

    if ($action == 'increase' && $product_id > 0) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]++;
        }
        header("Location: cart.php");
        exit();
    }

    if ($action == 'decrease' && $product_id > 0) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]--;
            if ($_SESSION['cart'][$product_id] <= 0) {
                unset($_SESSION['cart'][$product_id]);
            }
        }
        header("Location: cart.php");
        exit();
    }

    if ($action == 'remove' && $product_id > 0) {
        unset($_SESSION['cart'][$product_id]);
        header("Location: cart.php");
        exit();
    }
}

include 'includes/header.php';

$cart_products = [];
$total_amount = 0;

if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
    $result = $conn->query("SELECT * FROM products WHERE id IN ($ids)");
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $qty = $_SESSION['cart'][$row['id']];
            $subtotal = $row['price'] * $qty;
            $total_amount += $subtotal;
            
            $row['qty'] = $qty;
            $row['subtotal'] = $subtotal;
            $cart_products[] = $row;
        }
    }
}
?>

<style>
    .cart-page {
        background-color: #f8fafc;
        padding: 40px 0 80px;
        min-height: calc(100vh - 75px - 300px);
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        position: relative;
        display: inline-block;
        padding-bottom: 8px;
        margin-bottom: 25px;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 35px;
        height: 3px;
        background-color: #2563eb;
        border-radius: 2px;
    }

    .cart-table-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }

    .cart-table {
        margin-bottom: 0;
        vertical-align: middle;
    }

    .cart-table th {
        background-color: #f1f5f9;
        color: #334155;
        font-weight: 600;
        border-bottom: none;
        padding: 14px 16px;
    }

    .cart-table td {
        padding: 16px;
        color: #1e293b;
    }

    .cart-img {
        width: 60px;
        height: 60px;
        object-fit: contain;
        border-radius: 6px;
        background-color: #f8fafc;
    }

    .qty-btn {
        width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #1e293b;
        text-decoration: none;
        font-weight: 600;
    }

    .qty-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .order-summary-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        padding: 24px;
    }

    .summary-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 20px;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 10px;
    }

    .btn-checkout {
        background-color: #2563eb;
        color: #ffffff;
        font-weight: 600;
        padding: 12px;
        border-radius: 8px;
        width: 100%;
        display: block;
        text-align: center;
        text-decoration: none;
        transition: background-color 0.2s;
    }

    .btn-checkout:hover {
        background-color: #1d4ed8;
        color: #ffffff;
    }

    .empty-cart {
        background: #ffffff;
        border-radius: 12px;
        padding: 60px 20px;
        text-align: center;
    }
</style>

<div class="cart-page">
    <div class="container">
        <h2 class="section-title">Shopping Cart</h2>

        <?php if (!empty($cart_products)): ?>
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="cart-table-card">
                        <div class="table-responsive">
                            <table class="table cart-table align-middle">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th class="text-center">Quantity</th>
                                        <th>Subtotal</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cart_products as $item): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <img src="uploads/<?php echo htmlspecialchars($item['image']); ?>" 
                                                         onerror="this.onerror=null; this.src='assets/images/hero-banner.jpg';" 
                                                         class="cart-img" 
                                                         alt="<?php echo htmlspecialchars($item['name']); ?>">
                                                    <span class="fw-semibold"><?php echo htmlspecialchars($item['name']); ?></span>
                                                </div>
                                            </td>
                                            <td>₹<?php echo number_format($item['price'], 2); ?></td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center justify-content-center gap-2">
                                                    <a href="cart.php?action=decrease&id=<?php echo $item['id']; ?>" class="qty-btn">-</a>
                                                    <span class="fw-bold px-2"><?php echo $item['qty']; ?></span>
                                                    <a href="cart.php?action=increase&id=<?php echo $item['id']; ?>" class="qty-btn">+</a>
                                                </div>
                                            </td>
                                            <td class="fw-bold">₹<?php echo number_format($item['subtotal'], 2); ?></td>
                                            <td class="text-center">
                                                <a href="cart.php?action=remove&id=<?php echo $item['id']; ?>" class="text-danger fs-5" title="Remove Item">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="order-summary-card">
                        <h3 class="summary-title">Order Summary</h3>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-semibold">₹<?php echo number_format($total_amount, 2); ?></span>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Shipping</span>
                            <span class="text-success fw-semibold">FREE</span>
                        </div>

                        <hr class="my-3">

                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold fs-5">Total</span>
                            <span class="fw-bold fs-5 text-primary">₹<?php echo number_format($total_amount, 2); ?></span>
                        </div>

                        <a href="checkout.php" class="btn-checkout">Proceed to Checkout</a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="empty-cart">
                <i class="fa-solid fa-cart-flatbed fs-1 text-muted mb-3"></i>
                <h4 class="fw-bold text-dark">Your cart is empty</h4>
                <p class="text-muted mb-4">Looks like you haven't added anything to your cart yet.</p>
                <a href="products.php" class="btn btn-primary px-4 py-2">Continue Shopping</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
include 'includes/footer.php';
?>