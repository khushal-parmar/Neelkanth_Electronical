<?php 
require_once '../config/db.php';
include 'sidebar.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    
    // Image Handling
    $image_name = $_FILES['image']['name'];
    if(!empty($image_name)){
        $target = "../assets/images/" . basename($image_name);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    } else {
        $image_name = "default.jpg";
    }

    $stmt = $conn->prepare("INSERT INTO products (name, category, price, description, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdss", $name, $category, $price, $description, $image_name);
    $stmt->execute();
    $stmt->close();
}

$products = $conn->query("SELECT * FROM products ORDER BY id DESC LIMIT 10");
?>

<h2 class="fw-bold mb-4">Manage Electronics</h2>

<div class="card border-0 shadow-sm p-4 mb-4 rounded-3">
    <h5 class="mb-3"><i class="fas fa-plus me-2"></i>Add New Product</h5>
    <form action="index.php" method="POST" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-4">
                <input type="text" name="name" class="form-control" placeholder="e.g. Bajaj 500W Mixer" required>
            </div>
            <div class="col-md-4">
                <select name="category" class="form-select">
                    <option value="Blender">Blender</option>
                    <option value="Wiring">Wiring</option>
                    <option value="Lighting">Lighting</option>
                    <option value="Appliances">Appliances</option>
                </select>
            </div>
            <div class="col-md-4">
                <input type="number" step="0.01" name="price" class="form-control" placeholder="e.g. 1500" required>
            </div>
            <div class="col-12">
                <textarea name="description" class="form-control" rows="3" placeholder="Enter product details..."></textarea>
            </div>
            <div class="col-md-8">
                <input type="file" name="image" class="form-control">
            </div>
            <div class="col-md-4">
                <div class="border rounded p-2 text-center text-muted bg-light">No Image</div>
            </div>
            <div class="col-12">
                <button type="submit" name="add_product" class="btn btn-primary px-4">Add Product</button>
            </div>
        </div>
    </form>
</div>

<div class="card border-0 shadow-sm p-4 rounded-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Product Inventory</h5>
        <div>
            <span class="me-2 text-muted">Show</span>
            <select class="form-select d-inline-block w-auto"><option>Latest 10</option></select>
        </div>
    </div>
    
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($products && $products->num_rows > 0): ?>
                <?php while($row = $products->fetch_assoc()): ?>
                    <tr>
                        <td><img src="../assets/images/<?php echo $row['image']; ?>" width="50" class="rounded"></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['category'] ?? 'General'; ?></td>
                        <td>₹<?php echo $row['price']; ?></td>
                        <td><button class="btn btn-sm btn-outline-danger">Delete</button></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5" class="text-center text-muted py-4">No products found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</div></div></body></html>