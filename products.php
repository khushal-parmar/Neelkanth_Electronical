<?php
include 'includes/header.php';
require_once 'config/db.php';

$selected_category = isset($_GET['category']) ? strtolower(trim($_GET['category'])) : 'all';
?>

<style>
    .products-container {
        background-color: #f8fafc;
        min-height: 80vh;
        padding: 50px 0 80px;
    }

    .main-title {
        font-size: 2.2rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 5px;
    }

    .sub-title {
        color: #64748b;
        font-size: 1rem;
        margin-bottom: 30px;
    }

    .category-filter-nav {
        display: flex;
        gap: 25px;
        border-bottom: 1px solid #e2e8f0;
        margin-bottom: 40px;
        padding-bottom: 10px;
        flex-wrap: wrap;
    }

    .filter-link {
        color: #64748b;
        font-weight: 700;
        font-size: 0.95rem;
        text-transform: uppercase;
        text-decoration: none;
        letter-spacing: 0.5px;
        padding-bottom: 10px;
        position: relative;
        transition: all 0.2s ease;
    }

    .filter-link:hover {
        color: #0f172a;
    }

    .filter-link.active {
        color: #0f172a;
    }

    .filter-link.active::after {
        content: '';
        position: absolute;
        bottom: -11px;
        left: 0;
        width: 100%;
        height: 3px;
        background-color: #0f172a;
        border-radius: 2px;
    }

    .product-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 16px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.06);
    }

    .product-img-box {
        width: 100%;
        height: 240px;
        background-color: #f8fafc;
        border-radius: 12px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
    }

    .product-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-info-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 4px;
    }

    .product-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }

    .product-price {
        font-size: 1.15rem;
        font-weight: 800;
        color: #2563eb;
    }

    .product-category-text {
        color: #94a3b8;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 20px;
        display: block;
    }

    .btn-add-cart {
        width: 100%;
        background: transparent;
        border: 1.5px solid #0f172a;
        color: #0f172a;
        font-weight: 700;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        padding: 10px 0;
        border-radius: 8px;
        text-transform: uppercase;
        transition: all 0.2s ease;
        text-decoration: none;
        display: text-center;
        text-align: center;
    }

    .btn-add-cart:hover {
        background-color: #0f172a;
        color: #ffffff;
    }
</style>

<div class="products-container">
    <div class="container">
        <h1 class="main-title">Latest Collection</h1>
        <p class="sub-title">Discover our carefully curated appliances.</p>

        <div class="category-filter-nav">
            <a href="products.php?category=all" class="filter-link <?php echo ($selected_category == 'all') ? 'active' : ''; ?>">ALL</a>
            <a href="products.php?category=mixer-grinder" class="filter-link <?php echo ($selected_category == 'mixer-grinder' || $selected_category == 'mixer grinder') ? 'active' : ''; ?>">MIXER GRINDER</a>
            <a href="products.php?category=iron" class="filter-link <?php echo ($selected_category == 'iron') ? 'active' : ''; ?>">IRON</a>
            <a href="products.php?category=fan" class="filter-link <?php echo ($selected_category == 'fan') ? 'active' : ''; ?>">FAN</a>
            <a href="products.php?category=blender" class="filter-link <?php echo ($selected_category == 'blender') ? 'active' : ''; ?>">BLENDER</a>
        </div>

        <div class="row g-4">
            <?php
            if ($selected_category != 'all') {
                $cat_search = str_replace('-', ' ', $selected_category);
                $query = "SELECT * FROM products WHERE LOWER(category) = LOWER(?) ORDER BY id DESC";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $cat_search);
                $stmt->execute();
                $result = $stmt->get_result();
            } else {
                $query = "SELECT * FROM products ORDER BY id DESC";
                $result = $conn->query($query);
            }

            if ($result && $result->num_rows > 0) {
                while ($product = $result->fetch_assoc()) {
                    if (!empty($product['image']) && file_exists('uploads/' . $product['image'])) {
                        $p_img = 'uploads/' . $product['image'];
                    } else {
                        $p_img = 'https://via.placeholder.com/300x300?text=' . urlencode($product['name']);
                    }
            ?>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                        <div class="product-card">
                            <div>
                                <div class="product-img-box">
                                    <img src="<?php echo $p_img; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                </div>
                                <div class="product-info-head">
                                    <h3 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h3>
                                    <span class="product-price">₹<?php echo htmlspecialchars($product['price']); ?></span>
                                </div>
                                <span class="product-category-text"><?php echo htmlspecialchars($product['category']); ?></span>
                            </div>
                            <a href="cart.php?action=add&id=<?php echo $product['id']; ?>" class="btn-add-cart">ADD TO CART</a>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo '<div class="col-12"><p class="text-muted">No products found in this category.</p></div>';
            }
            ?>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>