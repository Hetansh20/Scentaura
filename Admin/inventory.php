<?php
include '../db.php';
if (!isset($_SESSION['user_id'])) {
      header("Location: admin_login.php");
      exit;
  }

$query = "SELECT * FROM products"; // assuming table name is 'products'
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
    <head>
    <title>Inventory | ScentAura Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="inventory.css">
</head>
<body>

    <aside class="sidebar">
      <div class="sidebar-logo">
        <img src="../Components/Logo/logo.png" alt="ScentAura Logo">
      </div>
      <ul class="nav-links">
        <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="product.php"><i class="fas fa-spray-can"></i> Products</a></li>
        <li><a href="featured_products.php"><i class="fas fa-spray-can"></i>Featued Products</a></li>
        <li><a href="#"><i class="fas fa-shopping-bag"></i> Orders</a></li>
        <li><a href="brand.php"><i class="fas fa-spray-can"></i>Brand</a></li>
        <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
        <li><a href="inventory.php" class="active"><i class="fas fa-boxes"></i> Inventory</a></li>
        <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
      </ul>
    </aside>

    <div class="main-content">
        <h2>ScentAura Inventory</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Brand ID</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Category</th>
                <th>Scent</th>
                <th>Size</th>
                <th>Pack Size</th>
                <th>Gender</th>
                <th>Occasion</th>
                <th>Concentration</th>
                <th>Image</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['description'] ?></td>
                    <td><?= $row['brand_id'] ?></td>
                    <td>₹<?= $row['price'] ?></td>
                    <td><?= $row['stock'] ?></td>
                    <td><?= $row['category'] ?></td>
                    <td><?= $row['scent'] ?></td>
                    <td><?= $row['size'] ?></td>
                    <td><?= $row['pack_size'] ?></td>
                    <td><?= $row['gender'] ?></td>
                    <td><?= $row['occasion'] ?></td>
                    <td><?= $row['concentration'] ?></td>
                    <td><img src="<?= $row['image_path'] ?>" alt="Image"></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

</body>
</html>
