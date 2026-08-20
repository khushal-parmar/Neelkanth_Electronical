<?php
session_start();
require_once '../config/db.php';

$error = '';
$product_id = $_GET['id'] ?? null;

if (!$product_id) {
    header("Location: products.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    header("Location: products.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['title']);
    $category = trim($_POST['category']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);
    $image_name = $product['image'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $img_name = $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];
        $img_ext = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($img_ext, $allowed_ext)) {
            $image_name = time() . '_' . $img_name;
            $upload_path = '../uploads/' . $image_name;
            
            if (!is_dir('../uploads/')) {
                mkdir('../uploads/', 0777, true);
            }
            move_uploaded_file($tmp_name, $upload_path);
        } else {
            $error = "Only JPG, JPEG, PNG, and WEBP image formats are allowed.";
        }
    }

    if (empty($error)) {
        $update_stmt = $conn->prepare("UPDATE products SET name = ?, category = ?, price = ?, description = ?, image = ? WHERE id = ?");
        $update_stmt->bind_param("ssdssi", $name, $category, $price, $description, $image_name, $product_id);

        if ($update_stmt->execute()) {
            header("Location: products.php");
            exit();
        } else {
            $error = "Failed to update product: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { background-color: #f8fafc; overflow-x: hidden; }
        .admin-wrapper { display: flex; height: 100vh; overflow: hidden; }
        .sidebar {
            width: 260px; background-color: #1e293b; color: #ffffff;
            flex-shrink: 0; display: flex; flex-direction: column;
            justify-content: space-between; padding: 20px 0;
        }
        .sidebar-brand {
            font-size: 1.5rem; font-weight: 700; color: #ffffff;
            padding: 0 24px 20px; border-bottom: 1px solid #334155;
            text-decoration: none; display: flex; align-items: center; gap: 10px;
        }
        .sidebar-menu { list-style: none; padding: 20px 12px; margin: 0; }
        .sidebar-menu li { margin-bottom: 6px; }
        .sidebar-menu a {
            color: #94a3b8; text-decoration: none; padding: 12px 16px;
            border-radius: 8px; display: flex; align-items: center; gap: 12px; font-weight: 500;
            transition: all 0.2s ease;
        }
        .sidebar-menu a:hover, .sidebar-menu a.active { background-color: #2563eb; color: #ffffff; }
        .main-content { flex-grow: 1; overflow-y: auto; padding: 30px; height: 100vh; }
        .card-custom {
            background: #ffffff; border-radius: 12px;
            border: 1px solid #e2e8f0; padding: 24px; max-width: 700px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .current-img { width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 1px solid #cbd5e1; }
    </style>
</head>
<body>

<div class="admin-wrapper">
    <aside class="sidebar">
        <div>
            <a href="dashboard.php" class="sidebar-brand">
                <i class="fa-solid fa-bolt text-warning"></i> Neelkanth
            </a>
            <ul class="sidebar-menu">
                <li><a href="products.php" class="active"><i class="fa-solid fa-box"></i> Products</a></li>
                <li><a href="orders.php"><i class="fa-solid fa-cart-shopping"></i> Orders</a></li>
                <li><a href="users.php"><i class="fa-solid fa-users"></i> Users</a></li>
                <li><a href="inquiries.php"><i class="fa-solid fa-phone"></i> Inquiries</a></li>
            </ul>
        </div>
        <div class="px-3">
            <a href="logout.php" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </aside>

    <main class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-dark m-0">Edit Product</h2>
            <a href="products.php" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Products
            </a>
        </div>

        <div class="card-custom">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Product Title / Name</label>
                    <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($product['name'] ?? $product['title'] ?? ''); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Category</label>
                    <?php $current_cat = $product['category'] ?? ''; ?>
                    <select name="category" class="form-select" required>
                        <option value="" disabled>Select Category</option>
                        <option value="Blender" <?php echo ($current_cat === 'Blender') ? 'selected' : ''; ?>>Blender</option>
                        <option value="Mixer Grinder" <?php echo ($current_cat === 'Mixer Grinder') ? 'selected' : ''; ?>>Mixer Grinder</option>
                        <option value="Fan" <?php echo ($current_cat === 'Fan') ? 'selected' : ''; ?>>Fan</option>
                        <option value="Iron" <?php echo ($current_cat === 'Iron') ? 'selected' : ''; ?>>Iron</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Price (₹)</label>
                    <input type="number" step="0.01" name="price" class="form-control" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Product Image</label>
                    <?php if (!empty($product['image'])): ?>
                        <div class="mb-2">
                            <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" class="current-img" alt="Product Image">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <small class="text-muted d-block mt-1">Leave blank if you do not want to change the current image.</small>
                </div>

                <button type="submit" class="btn btn-primary fw-semibold px-4">
                    <i class="fa-solid fa-pen me-1"></i> Update Product
                </button>
            </form>
        </div>
    </main>
</div>

</body>
</html>