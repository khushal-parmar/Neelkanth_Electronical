<?php
include 'includes/header.php';
require_once 'config/db.php';  
?>

<style>
    .page-container {
        background-color: #f8fafc;
        min-height: calc(100vh - 75px - 300px);
        padding: 50px 0 80px;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        position: relative;
        display: inline-block;
        padding-bottom: 8px;
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

    .category-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 30px 20px 20px;
        text-align: center;
        border: 1px solid #f1f5f9;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .category-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
    }

    .category-img-wrapper {
        height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
    }

    .category-img-wrapper img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
    }

    .category-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 15px;
    }

    .btn-view-products {
        border: 1px solid #2563eb;
        color: #2563eb;
        background: transparent;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 8px 0;
        border-radius: 6px;
        width: 100%;
        text-decoration: none;
        display: block;
        transition: all 0.2s ease;
    }

    .btn-view-products:hover {
        background-color: #2563eb;
        color: #ffffff;
    }
</style>

<div class="page-container">
    <div class="container">
        <div class="mb-4">
            <h2 class="section-title">Shop By Category</h2>
        </div>

        <div class="row g-4">
            <?php
            $categories = ['Blender', 'Mixer Grinder', 'Fan', 'Iron'];

            foreach ($categories as $cat_name):
                $stmt = $conn->prepare("SELECT image FROM products WHERE category = ? AND image != '' ORDER BY id DESC LIMIT 1");
                $stmt->bind_param("s", $cat_name);
                $stmt->execute();
                $result = $stmt->get_result();
                $product = $result->fetch_assoc();

                if ($product && !empty($product['image']) && file_exists('uploads/' . $product['image'])) {
                    $cat_img = 'uploads/' . $product['image'];
                } else {
                    $cat_img = 'https://via.placeholder.com/200?text=' . urlencode($cat_name);
                }
            ?>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                    <div class="category-card">
                        <div class="category-img-wrapper">
                            <img src="<?php echo $cat_img; ?>" alt="<?php echo htmlspecialchars($cat_name); ?>" class="img-fluid">
                        </div>
                        <div>
                            <h3 class="category-name"><?php echo htmlspecialchars($cat_name); ?></h3>
                            <a href="products.php?category=<?php echo urlencode($cat_name); ?>" class="btn-view-products">View Products</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>