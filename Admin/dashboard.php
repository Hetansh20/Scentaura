<?php

  include '../db.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>ScentAura Admin Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <link rel="stylesheet" href="style.css"?v=<?php echo time(); ?>/>
</head>
<body>
  <div class="admin-container">
    
    <aside class="sidebar">
      <div class="sidebar-logo">
        <img src="../Components/Logo/logo.png" alt="ScentAura Logo">
      </div>
      <ul class="nav-links">
        <li><a href="dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="product.php"><i class="fas fa-spray-can"></i> Products</a></li>
        <li><a href="featured_products.php"><i class="fas fa-spray-can"></i>Featued Products</a></li>
        <li><a href="brand.php"><i class="fas fa-spray-can"></i>Brand</a></li>
        <li><a href="#"><i class="fas fa-shopping-bag"></i> Orders</a></li>
        <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
        <li><a href="inventory.php"><i class="fas fa-boxes"></i> Inventory</a></li>
        <li><a href="#"><i class="fas fa-headset"></i> Support</a></li>
        <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
      </ul>
    </aside>

    <main class="main-content">
      <header class="main-header">
        <h1>Admin Dashboard</h1>
        <div class="user-profile">
          <div class="avatar">A</div>
          <span>Admin</span>
        </div>
      </header>

      <section class="stats-grid">
        <div class="card">
          <h3>Total Sales</h3>
          <p>$50,000</p>
        </div>
        <div class="card">
          <h3>Weekly Revenue</h3>
          <p>$8,200</p>
        </div>
        <div class="card">
          <h3>Orders</h3>
          <p>234</p>
        </div>
        <div class="card">
          <h3>Active Users</h3>
          <p>1,120</p>
        </div>
      </section>

      <section class="quick-access">
        <h2>Quick Access</h2>
        <div class="buttons">
          <button>Manage Products</button>
          <button>View Orders</button>
          <button>Manage Users</button>
        </div>
      </section>

      <section class="recent-activities">
        <h2>Recent Activities</h2>
        <ul>
          <li><i class="fas fa-check-circle"></i> Product "Lavender Bliss" added</li>
          <li><i class="fas fa-check-circle"></i> Order #1024 marked as shipped</li>
          <li><i class="fas fa-check-circle"></i> New user "john_doe" signed up</li>
        </ul>
      </section>
    </main>
  </div>
</body>
</html>
