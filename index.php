<?php
include 'includes/header.php';

require_once 'config/db.php';
?>

<style>
    .hero-section {
        background-color: #f1f3f6;
        min-height: 580px;
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
    }

    .hero-bg-img {
        position: absolute;
        top: 0;
        right: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center right;
        z-index: 1;
    }

    .hero-overlay {
        display: none;
    }

    .hero-content {
        position: relative;
        z-index: 3;
        max-width: 480px;
        padding-left: 15px;
    }

    .hero-title {
        color: #1e293b;
        font-size: 2.75rem;
        font-weight: 800;
        line-height: 1.25;
        letter-spacing: -0.5px;
    }

    .hero-subtitle {
        color: #64748b;
        font-size: 1.05rem;
        margin-top: 15px;
        margin-bottom: 25px;
    }

    .btn-shop-now {
        background-color: #2563eb;
        color: #ffffff !important;
        font-weight: 600;
        padding: 12px 32px;
        border-radius: 6px;
        text-decoration: none;
        display: inline-block;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        transition: all 0.2s ease;
    }

    .btn-shop-now:hover {
        background-color: #1d4ed8;
        color: #ffffff !important;
        transform: translateY(-2px);
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

    .section-title-center::after {
        left: 50%;
        transform: translateX(-50%);
    }

    .category-section {
        background-color: #f8fafc;
        padding: 60px 0;
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

    .why-us-section {
        background-color: #f8fafc;
        padding: 40px 0 80px;
    }

    .feature-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 35px 20px;
        text-align: center;
        border: 1px solid #f1f5f9;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        height: 100%;
    }

    .feature-icon-circle {
        width: 60px;
        height: 60px;
        background-color: #eff6ff;
        color: #2563eb;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 1.5rem;
    }

    .feature-title {
        font-size: 1.05rem;
        font-weight: 600;
        color: #2563eb;
        margin-bottom: 10px;
    }

    .feature-desc {
        color: #64748b;
        font-size: 0.88rem;
        line-height: 1.5;
        margin-bottom: 0;
    }

    /* Laptop Responsive */
    @media (min-width: 992px) and (max-width: 1400px) {
        .hero-section {
            min-height: 520px !important;
        }
        .hero-content {
            max-width: 420px !important;
            padding-left: 20px !important;
        }
        .hero-title {
            font-size: 2.3rem !important;
        }
        .hero-subtitle {
            font-size: 0.95rem !important;
        }
    }

    /* Mobile Responsive Customization */
    @media (max-width: 767px) {
        .hero-section { 
            min-height: 380px !important; 
            padding: 40px 0 !important; 
        }
        .hero-bg-img {
            object-position: center !important;
        }
        .hero-overlay {
            display: block !important;
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            background: rgba(255, 255, 255, 0.82) !important;
            z-index: 2 !important;
        }
        .hero-content {
            text-align: center !important;
            margin: 0 auto !important;
            max-width: 100% !important;
            padding-left: 0 !important;
        }
        .hero-title { 
            font-size: 1.8rem !important; 
        }
        .hero-subtitle {
            font-size: 0.9rem !important;
            margin-top: 10px !important;
            margin-bottom: 20px !important;
        }
        .category-section, .why-us-section {
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
        .feature-card {
            padding: 20px 15px !important;
        }
    }
</style>

<section class="hero-section">
    <img src="assets/images/hero-banner.png" alt="Quality Electronics" class="hero-bg-img" onerror="this.src='assets/images/hero-banner.jpg';">
    <div class="hero-overlay"></div>
    
    <div class="container position-relative">
        <div class="row">
            <div class="col-xl-5 col-lg-5 col-md-8">
                <div class="hero-content">
                    <h1 class="hero-title">Quality Electronics For Better Living</h1>
                    <p class="hero-subtitle">Find the best range of home appliances at affordable prices.</p>
                    <a href="products.php" class="btn-shop-now">Shop Now</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="category-section">
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
</section>

<section class="why-us-section">
    <div class="container">
        <div class="text-center mb-4 mb-md-5">
            <h2 class="section-title section-title-center">Why Choose Us?</h2>
        </div>

        <div class="row g-3 g-md-4">
            <div class="col-lg-3 col-md-6 col-12">
                <div class="feature-card">
                    <div class="feature-icon-circle">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h3 class="feature-title">Premium Quality</h3>
                    <p class="feature-desc">We only sell 100% original and certified appliances for your home.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12">
                <div class="feature-card">
                    <div class="feature-icon-circle">
                        <i class="fa-solid fa-indian-rupee-sign"></i>
                    </div>
                    <h3 class="feature-title">Affordable Prices</h3>
                    <p class="feature-desc">Get the best quality home appliances at the most competitive prices in the market.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12">
                <div class="feature-card">
                    <div class="feature-icon-circle">
                        <i class="fa-solid fa-wrench"></i>
                    </div>
                    <h3 class="feature-title">Expert After-Sales Service</h3>
                    <p class="feature-desc">We ensure the long-lasting performance of your products with our quick and reliable maintenance services.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12">
                <div class="feature-card">
                    <div class="feature-icon-circle">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <h3 class="feature-title">24/7 Support</h3>
                    <p class="feature-desc">Our dedicated customer service team is always here to assist you.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
include 'includes/footer.php';
?>