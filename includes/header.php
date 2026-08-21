<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neelkanth Electricals</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Google Translate Top Header Bar Hide */
        .goog-te-banner-frame, .skiptranslate, iframe.goog-te-banner-frame { display: none !important; }
        body { top: 0px !important; }
        .goog-text-highlight { background-color: transparent !important; box-shadow: none !important; }
        
        /* Custom Select Style */
        .lang-select {
            border: 1px solid #ced4da;
            border-radius: 6px;
            padding: 4px 8px;
            font-weight: 600;
            font-size: 0.9rem;
            color: #333;
            background-color: #fff;
            cursor: pointer;
            outline: none;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top py-3">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3" href="index.php">Neelkanth</a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 fw-semibold">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
                <li class="nav-item"><a class="nav-link" href="categories.php">Categories</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact Us</a></li>
            </ul>

            <div class="d-flex align-items-center gap-2">
                <a href="tel:+919913550501" class="text-decoration-none text-dark fw-semibold me-2 d-none d-xl-block">
                    <i class="fa-solid fa-phone text-danger me-1"></i> +91 99135 50501
                </a>

                <a href="cart.php" class="text-dark fs-5 position-relative me-3" title="View Cart">
                    <i class="fa-solid fa-cart-shopping"></i>
                </a>

                <!-- Custom Language Selector Dropdown -->
                <div class="me-2">
                    <select class="lang-select" onchange="changeLanguage(this.value)">
                        <option value="en">🌐 English</option>
                        <option value="gu">🌐 ગુજરાતી</option>
                    </select>
                </div>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="profile.php" class="btn btn-primary fw-semibold d-flex align-items-center gap-2">
                        <i class="fa-solid fa-user"></i> 
                        <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                    </a>
                    
                    <a href="user-logout.php" class="btn btn-outline-danger fw-semibold" title="Logout">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </a>
                <?php else: ?>
                    <a href="user-login.php" class="btn btn-light border fw-semibold px-4">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- Hidden Google Translate Element -->
<div id="google_translate_element" style="display:none;"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
                    
<script type="text/javascript">
function googleTranslateElementInit() {
    new google.translate.TranslateElement({
        pageLanguage: 'en',
        includedLanguages: 'en,gu',
        autoDisplay: false
    }, 'google_translate_element');
}

function changeLanguage(langCode) {
    document.cookie = "googtrans=/en/" + langCode + "; path=/";
    document.cookie = "googtrans=/en/" + langCode + "; domain=" + window.location.hostname + "; path=/";
    location.reload();
}

// Auto select existing language on page reload
window.addEventListener('DOMContentLoaded', () => {
    let cookies = document.cookie.split(';');
    for (let i = 0; i < cookies.length; i++) {
        let cookie = cookies[i].trim();
        if (cookie.startsWith("googtrans=")) {
            let selectBox = document.querySelector('.lang-select');
            if (cookie.includes("/gu") && selectBox) {
                selectBox.value = "gu";
            } else if (selectBox) {
                selectBox.value = "en";
            }
        }
    }
});
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>