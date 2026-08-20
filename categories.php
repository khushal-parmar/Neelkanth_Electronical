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
        padding: 25px 15px 15px;
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
        color: #ffffff !important;
    }

    @media (max-width: 767px) {
        .page-container {
            padding: 35px 0 !important;
        }
        .category-img-wrapper {
            height: 120px !important;
        }
        .category-card {
            padding: 15px 10px 12px !important;
        }
        .category-name {
            font-size: 0.95rem !important;
            margin-bottom: 10px !important;
        }
        .btn-view-products {
            font-size: 0.8rem !important;
            padding: 6px 0 !important;
        }
    }
</style>

<div class="page-container">
    <div class="container">
        <div class="mb-4 text-sm-start text-center">
            <h2 class="section-title">Shop By Category</h2>
        </div>

        <div class="row g-3 g-md-4">
            <?php
            $categories = [
                [
                    'name' => 'Blender', 
                    'file' => 'blender',
                    'fallback' => 'https://m.media-amazon.com/images/I/61S3yB9S7LL._SL1500_.jpg'
                ],
                [
                    'name' => 'Mixer Grinder', 
                    'file' => 'mixer',
                    'fallback' => 'https://m.media-amazon.com/images/I/61NfTee8x-L._SL1200_.jpg'
                ],
                [
                    'name' => 'Fan', 
                    'file' => 'fan',
                    'fallback' => 'https://m.media-amazon.com/images/I/61aMvFClc1L._SL1500_.jpg'
                ],
                [
                    'name' => 'Iron', 
                    'file' => 'iron',
                    'fallback' => 'https://m.media-amazon.com/images/I/71P4qZ1wQXL._SL1500_.jpg'
                ]
            ];

            foreach ($categories as $cat):
                $cat_name = $cat['name'];
                $cat_file = $cat['file'];
                $cat_fallback = $cat['fallback'];

                $cat_img = $cat_fallback;
                if (file_exists("assets/images/{$cat_file}.png")) {
                    $cat_img = "assets/images/{$cat_file}.png";
                } elseif (file_exists("assets/images/{$cat_file}.jpg")) {
                    $cat_img = "assets/images/{$cat_file}.jpg";
                } elseif (file_exists("assets/images/{$cat_file}.jpeg")) {
                    $cat_img = "assets/images/{$cat_file}.jpeg";
                }
            ?>
                <div class="col-xl-3 col-lg-3 col-md-6 col-6">
                    <div class="category-card">
                        <div class="category-img-wrapper">
                            <img src="<?php echo $cat_img; ?>" 
                                 onerror="this.onerror=null; this.src='<?php echo $cat_fallback; ?>';" 
                                 alt="<?php echo htmlspecialchars($cat_name); ?>" 
                                 class="img-fluid">
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