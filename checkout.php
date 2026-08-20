<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = 'checkout.php';
    header("Location: user-login.php?msg=login_required");
    exit();
}

if (empty($_SESSION['cart'])) {
    header("Location: products.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success_msg = "";
$error_msg = "";

$cart_products = [];
$total_amount = 0;

$ids = implode(',', array_keys($_SESSION['cart']));
$result = $conn->query("SELECT * FROM products WHERE id IN ($ids)");

while ($row = $result->fetch_assoc()) {
    $qty = $_SESSION['cart'][$row['id']];
    $subtotal = $row['price'] * $qty;
    $total_amount += $subtotal;
    
    $row['qty'] = $qty;
    $row['subtotal'] = $subtotal;
    $cart_products[] = $row;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $pincode = trim($_POST['pincode']);

    if (!empty($address) && !empty($city) && !empty($pincode)) {
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, address, city, pincode, status) VALUES (?, ?, ?, ?, ?, 'Pending')");
      $stmt->bind_param("idsss", $user_id, $total_amount, $address, $city, $pincode);
        
        if ($stmt->execute()) {
            $order_id = $stmt->insert_id;

            $item_stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            foreach ($cart_products as $item) {
                $item_stmt->bind_param("iiid", $order_id, $item['id'], $item['qty'], $item['price']);
                $item_stmt->execute();
            }
            $item_stmt->close();

            unset($_SESSION['cart']);
            $success_msg = "Order Placed Successfully! Your Order ID is #" . $order_id;
        } else {
            $error_msg = "Failed to place order. Please try again.";
        }
        $stmt->close();
    } else {
        $error_msg = "Please fill in all address details.";
    }
}

include 'includes/header.php';
?>

<style>
    .checkout-page {
        background-color: #f8fafc;
        padding: 40px 0 80px;
        min-height: calc(100vh - 75px - 300px);
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 25px;
    }

    .checkout-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        padding: 30px;
    }

    .custom-input {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 12px 16px;
    }

    .btn-place-order {
        background-color: #2563eb;
        color: #ffffff;
        font-weight: 600;
        padding: 12px;
        border-radius: 8px;
        border: none;
        width: 100%;
        transition: background-color 0.2s;
    }

    .btn-place-order:hover {
        background-color: #1d4ed8;
    }
</style>

<div class="checkout-page">
    <div class="container">
        <h2 class="section-title">Checkout</h2>

        <?php if (!empty($success_msg)): ?>
            <div class="checkout-card text-center py-5">
                <i class="fa-solid fa-circle-check text-success fs-1 mb-3"></i>
                <h3 class="fw-bold text-dark mb-2">Thank You!</h3>
                <p class="text-muted fs-5 mb-4"><?php echo $success_msg; ?></p>
                <a href="products.php" class="btn btn-primary px-4 py-2">Continue Shopping</a>
            </div>
        <?php else: ?>

            <?php if (!empty($error_msg)): ?>
                <div class="alert alert-danger py-2 mb-3 fs-6"><?php echo $error_msg; ?></div>
            <?php endif; ?>

            <form action="checkout.php" method="POST">
                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="checkout-card">
                            <h4 class="fw-bold mb-4 text-dark">Shipping Address</h4>

                            <div class="mb-3">
                                <label class="form-label text-secondary">Delivery Address</label>
                                <textarea name="address" class="form-control custom-input" rows="3" placeholder="House No, Street, Landmark..." required></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-secondary">City</label>
                                    <input type="text" name="city" class="form-control custom-input" placeholder="Junagadh" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-secondary">Pincode</label>
                                    <input type="text" name="pincode" class="form-control custom-input" placeholder="362001" pattern="[0-9]{6}" required>
                                </div>
                            </div>
 
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="checkout-card">
                            <h4 class="fw-bold mb-4 text-dark">Order Summary</h4>

                            <?php foreach ($cart_products as $item): ?>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="mb-0 fw-semibold"><?php echo htmlspecialchars($item['name']); ?></h6>
                                        <small class="text-muted">Qty: <?php echo $item['qty']; ?></small>
                                    </div>
                                    <span class="fw-bold">₹<?php echo number_format($item['subtotal'], 2); ?></span>
                                </div>
                            <?php endforeach; ?>

                            <hr class="my-3">

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal</span>
                                <span class="fw-semibold">₹<?php echo number_format($total_amount, 2); ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Shipping</span>
                                <span class="text-success fw-semibold">FREE</span>
                            </div>

                            <hr class="my-3">

                            <div class="d-flex justify-content-between mb-4">
                                <span class="fw-bold fs-5">Total Payble</span>
                                <span class="fw-bold fs-5 text-primary">₹<?php echo number_format($total_amount, 2); ?></span>
                            </div>

                            <button type="submit" class="btn-place-order">Place Order</button>
                        </div>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>