 
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
/*  this */
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
        transition: transform 0.3s ease;
    }

    .product-img-box:hover img {
        transform: scale(1.05);
    }

    .product-info-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 4px;
    }

    .product-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        text-decoration: none;
        line-height: 1.3;
    }

    .product-title:hover {
        color: #2563eb;
    }

    .product-price {
        font-size: 1.15rem;
        font-weight: 800;
        color: #2563eb;
        white-space: nowrap;
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

    /* Add To Cart Button */
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
        display: block;
        text-align: center;
        cursor: pointer;
    }

    .btn-add-cart:hover {
        background-color: #0f172a;
        color: #ffffff;
    }

    .btn-add-cart:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    /* Success Toast */
    #cartToast {
        position: fixed;
        top: 85px;
        right: 25px;
        z-index: 99999;
        min-width: 280px;
        max-width: calc(100vw - 40px);
        background: #ffffff;
        color: #0f172a;
        border-radius: 10px;
        padding: 13px 16px;
        box-shadow: 0 10px 35px rgba(0, 0, 0, 0.15);
        border-left: 4px solid #16a34a;
        display: flex;
        align-items: center;
        gap: 11px;
        opacity: 0;
        visibility: hidden;
        transform: translateX(120%);
        transition: all 0.35s ease;
    }

    #cartToast.show {
        opacity: 1;
        visibility: visible;
        transform: translateX(0);
    }

    .toast-icon {
        width: 30px;
        height: 30px;
        min-width: 30px;
        border-radius: 50%;
        background: #dcfce7;
        color: #16a34a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
    }

    .toast-content {
        display: flex;
        flex-direction: column;
        gap: 2px;
        line-height: 1.2;
    }

    .toast-title {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
    }

    .toast-message {
        font-size: 12px;
        color: #64748b;
    }

    .toast-close {
        margin-left: auto;
        border: none;
        background: transparent;
        color: #94a3b8;
        font-size: 18px;
        cursor: pointer;
        padding: 0 2px;
        line-height: 1;
    }

    .toast-close:hover {
        color: #0f172a;
    }

    /* Mobile Responsive */
    @media (max-width: 767px) {
        .products-container {
            padding: 30px 0 60px;
        }

        .main-title {
            font-size: 1.8rem;
        }

        .sub-title {
            font-size: 0.9rem;
            margin-bottom: 25px;
        }

        .category-filter-nav {
            gap: 18px;
            margin-bottom: 30px;
        }

        .filter-link {
            font-size: 0.8rem;
        }

        .product-img-box {
            height: 210px;
        }

        .product-title {
            font-size: 1rem;
        }

        .product-price {
            font-size: 1rem;
        }

        #cartToast {
            top: 75px;
            right: 15px;
            left: 15px;
            min-width: auto;
            max-width: none;
            width: auto;
        }
    }

    @media (max-width: 575px) {
        .products-container {
            padding: 25px 0 50px;
        }

        .category-filter-nav {
            gap: 14px;
        }

        .filter-link {
            font-size: 0.75rem;
        }

        .product-card {
            padding: 12px;
            border-radius: 12px;
        }

        .product-img-box {
            height: 190px;
            border-radius: 10px;
        }

        .product-info-head {
            flex-direction: column;
            gap: 5px;
        }

        .product-price {
            font-size: 1.05rem;
        }

        .product-category-text {
            margin-bottom: 15px;
        }

        .btn-add-cart {
            padding: 11px 0;
        }
    }
</style>

<!-- Success Toast -->
<div id="cartToast">
    <div class="toast-icon">
        <i class="fa-solid fa-check"></i>
    </div>

    <div class="toast-content">
        <div class="toast-title">Product Added</div>
        <div class="toast-message" id="toastMessage">
            Product added successfully!
        </div>
    </div>

    <button type="button" class="toast-close" onclick="hideCartToast()">
        &times;
    </button>
</div>


<div class="products-container">
    <div class="container">

        <h1 class="main-title">Latest Collection</h1>

        <p class="sub-title">
            Discover our carefully curated appliances.
        </p>


        <!-- Category Filter -->
        <div class="category-filter-nav">

            <a href="products.php?category=all"
               class="filter-link <?php echo ($selected_category == 'all') ? 'active' : ''; ?>">
                ALL
            </a>

            <a href="products.php?category=mixer-grinder"
               class="filter-link <?php echo ($selected_category == 'mixer-grinder' || $selected_category == 'mixer grinder') ? 'active' : ''; ?>">
                MIXER GRINDER
            </a>

            <a href="products.php?category=iron"
               class="filter-link <?php echo ($selected_category == 'iron') ? 'active' : ''; ?>">
                IRON
            </a>

            <a href="products.php?category=fan"
               class="filter-link <?php echo ($selected_category == 'fan') ? 'active' : ''; ?>">
                FAN
            </a>

            <a href="products.php?category=blender"
               class="filter-link <?php echo ($selected_category == 'blender') ? 'active' : ''; ?>">
                BLENDER
            </a>

        </div>


        <!-- Products -->
        <div class="row g-4">

            <?php

            if ($selected_category != 'all') {

                $cat_search = str_replace('-', ' ', $selected_category);

                $query = "SELECT * FROM products
                          WHERE LOWER(category) = LOWER(?)
                          ORDER BY id DESC";

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

                    if (
                        !empty($product['image']) &&
                        file_exists('uploads/' . $product['image'])
                    ) {

                        $p_img = 'uploads/' . $product['image'];

                    } else {

                        $p_img =
                            'https://via.placeholder.com/300x300?text=' .
                            urlencode($product['name']);
                    }

            ?>

                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">

                        <div class="product-card">

                            <div>

                                <!-- Product Image -->
                                <a href="product_details.php?id=<?php echo $product['id']; ?>"
                                   class="d-block text-decoration-none">

                                    <div class="product-img-box">

                                        <img
                                            src="<?php echo htmlspecialchars($p_img); ?>"
                                            alt="<?php echo htmlspecialchars($product['name']); ?>"
                                        >

                                    </div>

                                </a>


                                <!-- Product Information -->
                                <div class="product-info-head">

                                    <a
                                        href="product_details.php?id=<?php echo $product['id']; ?>"
                                        class="product-title"
                                    >
                                        <?php echo htmlspecialchars($product['name']); ?>
                                    </a>

                                    <span class="product-price">
                                        ₹<?php echo htmlspecialchars($product['price']); ?>
                                    </span>

                                </div>


                                <span class="product-category-text">
                                    <?php echo htmlspecialchars($product['category']); ?>
                                </span>

                            </div>


                            <!-- Add To Cart -->
                            <button
                                type="button"
                                class="btn-add-cart"
                                data-product-id="<?php echo (int)$product['id']; ?>"
                                data-product-name="<?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>"
                                onclick="addToCart(this)"
                            >
                                ADD TO CART
                            </button>

                        </div>

                    </div>

            <?php

                }

            } else {

                echo '
                <div class="col-12">
                    <p class="text-muted">
                        No products found in this category.
                    </p>
                </div>';

            }

            ?>

        </div>

    </div>
</div>


<script>
    let toastTimer = null;


    /*
     * ADD TO CART
     * AJAX request thi cart.php par product add karse.
     * Page reload / redirect nahi thase.
     */
    function addToCart(button) {

        const productId = button.getAttribute('data-product-id');
        const productName = button.getAttribute('data-product-name');

        if (!productId) {
            return;
        }


        // Prevent multiple clicks while request is running
        if (button.disabled) {
            return;
        }


        const originalText = button.innerHTML;

        button.disabled = true;

        button.innerHTML =
            '<i class="fa-solid fa-spinner fa-spin"></i> ADDING...';


        /*
         * cart.php?action=add&id=PRODUCT_ID
         *
         * Existing cart.php ni GET add functionality
         * use kari rahya chhiye.
         */
        fetch(
            'cart.php?action=add&id=' +
            encodeURIComponent(productId),
            {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                cache: 'no-store'
            }
        )
        .then(function(response) {

            /*
             * cart.php AJAX request ma redirect kare to
             * fetch automatically response handle kare che.
             */
            if (!response.ok) {
                throw new Error('Unable to add product');
            }

            return response.text();
        })
        .then(function() {

            // Button normal state
            button.disabled = false;

            button.innerHTML =
                '<i class="fa-solid fa-check"></i> ADDED';

            /*
             * Success message
             */
            showCartToast(
                productName + ' added successfully!'
            );


            /*
             * Thoda time pachi button original state ma.
             * User same product fari add kari shake.
             */
            setTimeout(function() {

                button.innerHTML = originalText;

            }, 1200);

        })
        .catch(function(error) {

            console.error(error);

            button.disabled = false;

            button.innerHTML = originalText;

            showCartToast(
                'Unable to add product. Please try again.',
                true
            );

        });

    }


    /*
     * SHOW TOAST
     */
    function showCartToast(message, isError = false) {

        const toast = document.getElementById('cartToast');
        const toastMessage = document.getElementById('toastMessage');
        const icon = toast.querySelector('.toast-icon');

        if (!toast || !toastMessage) {
            return;
        }


        toastMessage.textContent = message;


        if (isError) {

            toast.style.borderLeftColor = '#dc2626';

            icon.style.background = '#fee2e2';

            icon.style.color = '#dc2626';

            icon.innerHTML =
                '<i class="fa-solid fa-xmark"></i>';

        } else {

            toast.style.borderLeftColor = '#16a34a';

            icon.style.background = '#dcfce7';

            icon.style.color = '#16a34a';

            icon.innerHTML =
                '<i class="fa-solid fa-check"></i>';
        }


        // Existing timer clear
        if (toastTimer) {
            clearTimeout(toastTimer);
        }


        // Show
        toast.classList.add('show');


        // Automatically hide after 2.5 seconds
        toastTimer = setTimeout(function() {

            hideCartToast();

        }, 2500);

    }


    /*
     * HIDE TOAST
     */
    function hideCartToast() {

        const toast = document.getElementById('cartToast');

        if (!toast) {
            return;
        }

        toast.classList.remove('show');

    }
</script>


<?php
include 'includes/footer.php';
?>