<?php
include '../db.php';

// Handle Add or Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $origin = $_POST['origin_country'];

    if ($id) {
        $stmt = $conn->prepare("UPDATE brands SET name=?, description=?, origin_country=? WHERE id=?");
        $stmt->bind_param("sssi", $name, $desc, $origin, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO brands (name, description, origin_country) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $desc, $origin);
    }

    $stmt->execute();
    $stmt->close();
    header("Location: brand.php");
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM brands WHERE id=$id");
    header("Location: brand.php");
    exit();
}
$brands = $conn->query("SELECT * FROM brands ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Brand Management</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <link rel="stylesheet" href="style.css"?v=<?php echo time(); ?>/>
  <link rel="stylesheet" href="brand.css">
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
        <li><a href="brand.php" class="active"><i class="fas fa-spray-can"></i>Brand</a></li>
        <li><a href="#"><i class="fas fa-shopping-bag"></i> Orders</a></li>
        <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
        <li><a href="inventory.php"><i class="fas fa-boxes"></i> Inventory</a></li>
        <li><a href="#"><i class="fas fa-headset"></i> Support</a></li>
        <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
      </ul>
    </aside>

<h2>Brand Management</h2>
<div style="text-align:center;">
  <button class="btn" onclick="openForm()">+ Add New Brand</button>
</div>


<div class="form" id="brandForm">
  <form method="POST" action="brand.php">
    <input type="hidden" name="id" id="brandId">
    <div class="form-group">
      <label>Brand Name:</label>
      <input type="text" name="name" id="brandName" required>
    </div>
    <div class="form-group">
      <label>Description:</label>
      <textarea name="description" id="brandDesc" rows="3"></textarea>
    </div>
    <div class="form-group">
      <label>Country of Origin:</label>
      <input type="text" name="origin_country" id="brandOrigin">
    </div>
    <button type="submit" class="btn">Save Brand</button>
    <button type="button" class="btn" onclick="closeForm()">Cancel</button>
  </form>
</div>

<div style="margin-left: 250px; padding: 40px; overflow-x:auto;">
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Brand</th>
        <th>Description</th>
        <th>Origin</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $brands->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['description']) ?></td>
        <td><?= htmlspecialchars($row['origin_country']) ?></td>
        <td class="action-btns">
          <a href="#" onclick="editBrand(<?= $row['id'] ?>, '<?= addslashes($row['name']) ?>', '<?= addslashes($row['description']) ?>', '<?= addslashes($row['origin_country']) ?>')">Edit</a>
          <a href="brand.php?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this brand?')">Delete</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>


<script>
function openForm() {
  document.getElementById('brandForm').style.display = 'block';
  document.getElementById('brandId').value = '';
  document.getElementById('brandName').value = '';
  document.getElementById('brandDesc').value = '';
  document.getElementById('brandOrigin').value = '';
}

function closeForm() {
  document.getElementById('brandForm').style.display = 'none';
}

function editBrand(id, name, desc, origin) {
  document.getElementById('brandForm').style.display = 'block';
  document.getElementById('brandId').value = id;
  document.getElementById('brandName').value = name;
  document.getElementById('brandDesc').value = desc;
  document.getElementById('brandOrigin').value = origin;
}
</script>

</body>
</html>
