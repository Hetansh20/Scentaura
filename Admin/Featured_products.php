<?php
include '../db.php';
if (!isset($_SESSION['user_id'])) {
      header("Location: admin_login.php");
      exit;
  }
// Handle AJAX form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];


    $stmt = $conn->prepare("INSERT INTO featured_products (product_id) VALUES (?)");
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        echo "Product added to featured list.";
    } else {
        http_response_code(500);
        echo "Failed to add product.";
    }

    $stmt->close();
    $conn->close();
    exit; // End execution for AJAX
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Featured Products</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>"/>
  <link rel="stylesheet" href="featured_products.css?v=<?php echo time(); ?>"/>
</head>
<body>
  <aside class="sidebar">
    <div class="sidebar-logo">
      <img src="../Components/Logo/logo.png" alt="ScentAura Logo">
    </div>
    <ul class="nav-links">
      <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
      <li><a href="product.php"><i class="fas fa-spray-can"></i> Products</a></li>
      <li><a href="featured_products.php" class="active"><i class="fas fa-spray-can"></i> Featured Products</a></li>
      <li><a href="brand.php"><i class="fas fa-spray-can"></i> Brand</a></li>
      <li><a href="#"><i class="fas fa-shopping-bag"></i> Orders</a></li>
      <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
      <li><a href="inventory.php"><i class="fas fa-boxes"></i> Inventory</a></li>
      <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
    </ul>
  </aside>

  <main class="main-content">
    <h1>Manage Featured Products</h1>
    <form id="featuredForm" class="featured-form">
      <label for="product_id">Select Product:</label>
      <select id="product_id" name="product_id" required>
        <option value="">-- Select a Product --</option>
        <?php
          $conn = new mysqli("localhost", "root", "", "scentaura");
          if ($conn->connect_error) {
            echo '<option disabled>Database connection error</option>';
          } else {
            $sql = "SELECT id, name FROM products";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['name']) . '</option>';
              }
            } else {
              echo '<option disabled>No products found</option>';
            }
            $conn->close();
          }
        ?>
      </select>

      <button type="submit">Add Featured Product</button>
      <p id="statusMsg" style="color: green; margin-top: 10px;"></p>
    </form>
  </main>

  <script>
    document.getElementById('featuredForm').addEventListener('submit', function(event) {
      event.preventDefault();

      const formData = new FormData(this);

      fetch('', {
        method: 'POST',
        body: formData
      })
      .then(response => response.text())
      .then(result => {
        document.getElementById('statusMsg').textContent = result;
        document.getElementById('featuredForm').reset();
      })
      .catch(error => {
        document.getElementById('statusMsg').textContent = 'Error adding product.';
        console.error('Error:', error);
      });
    });
  </script>
</body>
</html>
