<?php
include 'includes/header.php';
require_once 'config/db.php';

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<div class='container my-5'><h3>Product not found!</h3><a href='products.php' class='btn btn-primary mt-3'>Back to Products</a></div>";
    include 'includes/footer.php';
    exit();
}

$product = $result->fetch_assoc();
?>

<div class="container my-5">
    <!-- Success Alert Notification -->
    <div id="cart-alert" class="alert alert-success alert-dismissible fade show d-none" role="alert">
        <strong>Success!</strong> Product added to cart successfully.
        <a href="cart.php" class="alert-link ms-2">View Cart</a>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <div class="row g-4 align-items-center">
        <!-- Product Image -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-3 rounded-4 text-center">
                <img src="uploads/<?php echo htmlspecialchars($product['image'] ?? ''); ?>" 
                     onerror="this.onerror=null; this.src='assets/images/hero-banner.jpg';" 
                     alt="<?php echo htmlspecialchars($product['name'] ?? ''); ?>" 
                     class="img-fluid rounded-3" 
                     style="max-height: 400px; object-fit: contain;">
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-md-6">
            <span class="badge bg-secondary mb-2"><?php echo htmlspecialchars($product['category'] ?? 'General'); ?></span>
            <h1 class="fw-bold mb-2"><?php echo htmlspecialchars($product['name'] ?? ''); ?></h1>
            <h3 class="text-primary fw-bold mb-3">₹<?php echo number_format($product['price'] ?? 0, 2); ?></h3>
            <p class="text-muted mb-4"><?php echo nl2br(htmlspecialchars($product['description'] ?? 'No description available for this product.')); ?></p>

            <!-- Quantity & Add to Cart Form -->
            <form id="addToCartForm" class="mt-4">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <input type="hidden" name="add_to_cart" value="1">
                
                <div class="d-flex align-items-center gap-3 mb-4">
                    <label class="fw-bold">Quantity:</label>
                    <div class="input-group" style="width: 130px;">
                        <button type="button" class="btn btn-outline-secondary" onclick="decreaseQty()">-</button>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" class="form-control text-center">
                        <button type="button" class="btn btn-outline-secondary" onclick="increaseQty()">+</button>
                    </div>
                </div>

                <button type="submit" id="cartBtn" class="btn btn-primary btn-lg px-4 rounded-3">
                    <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function increaseQty() {
    let qtyInput = document.getElementById('quantity');
    qtyInput.value = parseInt(qtyInput.value) + 1;
}

function decreaseQty() {
    let qtyInput = document.getElementById('quantity');
    if (parseInt(qtyInput.value) > 1) {
        qtyInput.value = parseInt(qtyInput.value) - 1;
    }
}

// Handle Add to Cart Form Submit without page reload
document.getElementById('addToCartForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    let formData = new FormData(this);

    fetch('cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        let alertBox = document.getElementById('cart-alert');
        alertBox.classList.remove('d-none');
        
        // Auto scroll to alert
        window.scrollTo({ top: 0, behavior: 'smooth' });
    })
    .catch(error => {
        console.error('Error:', error);
    });
});
</script>

<?php include 'includes/footer.php'; ?>