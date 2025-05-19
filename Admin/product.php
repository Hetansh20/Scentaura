<?php
include '../db.php';

// Handle form submission
$success = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $brand_id = $_POST['brand_id'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];
    $scent = $_POST['scent'];
    $size = $_POST['size'];
    $pack_size = $_POST['pack_size'];
    $gender = $_POST['gender'];
    $occasion = $_POST['occasion'];
    $concentration = $_POST['concentration'];

    // Image upload
    $image = $_FILES['image'];
    $imageName = basename($image['name']);
    $targetDir = "uploads/";
    $imagePath = $targetDir . time() . "_" . $imageName;

    if (move_uploaded_file($image["tmp_name"], $imagePath)) {
        $stmt = $conn->prepare("INSERT INTO products 
        (name, description, brand_id, price, stock, category, scent, size, pack_size, gender, occasion, concentration, image_path) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("ssidsisssssss", 
            $name, $desc, $brand_id, $price, $stock, $category, $scent, $size, $pack_size, $gender, $occasion, $concentration, $imagePath);

        $stmt->execute();
        $stmt->close();
        $success = "✅ Product added successfully!";
    } else {
        $success = "❌ Image upload failed!";
    }
}

// Fetch brands
$brands = $conn->query("SELECT id, name FROM brands ORDER BY name ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link rel="stylesheet" href="product.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
</head>
<body>

    <aside class="sidebar">
      <div class="sidebar-logo">
        <img src="../Components/Logo/logo.png" alt="ScentAura Logo">
      </div>
      <ul class="nav-links">
        <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="product.php" class="active"><i class="fas fa-spray-can"></i> Products</a></li>
        <li><a href="featured_products.php"><i class="fas fa-spray-can"></i>Featued Products</a></li>
        <li><a href="brand.php"><i class="fas fa-spray-can"></i>Brand</a></li>
        <li><a href="#"><i class="fas fa-shopping-bag"></i> Orders</a></li>
        <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
        <li><a href="inventory.php"><i class="fas fa-boxes"></i> Inventory</a></li>
        <li><a href="#"><i class="fas fa-headset"></i> Support</a></li>
        <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
      </ul>
    </aside>

<div class="container">
    <h2>Add New Product</h2>
    <?php if ($success): ?>
        <div class="message"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <label>Product Name:</label>
        <input type="text" name="name" required>

        <label>Description:</label>
        <textarea name="description" required></textarea>

        <label>Price ($):</label>
        <input type="number" name="price" step="0.01" required>

        <label>Brand:</label>
        <select name="brand_id" required>
            <option value="">-- Select Brand --</option>
            <?php while ($b = $brands->fetch_assoc()): ?>
                <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['name']) ?></option>
            <?php endwhile; ?>
        </select>

        <label>Stock:</label>
        <input type="number" name="stock" required>

        <label>Category:</label>
        <input type="text" name="category" placeholder="e.g. perfume, deodorant" required>

        <label>Scent:</label>
        <input type="text" name="scent" placeholder="e.g. Citrus, Lavender, Musk">

        <label>Size (ml):</label>
        <input type="text" name="size">

        <label>Pack Size:</label>
        <input type="text" name="pack_size" placeholder="e.g. single, gift pack, couple pack">

        <label>Gender:</label>
        <select name="gender" required>
            <option value="">-- Select Gender --</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="unisex">Unisex</option>
        </select>

        <label>Occasion:</label>
        <input type="text" name="occasion" placeholder="e.g. casual, daily, formal, special">

        <label>Concentration:</label>
        <input type="text" name="concentration" placeholder="e.g. EDP, EDT, Body Spray">

        <label>Product Image:</label>
        <input type="file" name="image" accept="image/*" required>

        <button type="submit">Add Product</button>
    </form>
</div>

</body>
</html>
