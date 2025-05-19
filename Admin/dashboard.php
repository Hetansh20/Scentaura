<?php

  include '../db.php';
  session_start();

  if (!isset($_SESSION['user_id'])) {
      header("Location: admin_login.php");
      exit;
  }

  // Fetch total sales
  $sales_query = "SELECT SUM(total_amount) AS total_sales FROM orders WHERE payment_status = 'completed'";
  $sales_result = $conn->query($sales_query);
  $sales_data = $sales_result->fetch_assoc();
  $total_sales = $sales_data['total_sales'] ?? 0;


  // Fetch weekly revenue
  $weekly_query = "SELECT SUM(total_amount) AS weekly_revenue FROM orders WHERE order_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND payment_status = 'completed'";
  $weekly_result = $conn->query($weekly_query);
  $weekly_data = $weekly_result->fetch_assoc();
  $weekly_revenue = $weekly_data['weekly_revenue'] ?? 0;


  // Fetch total orders
  $orders_query = "SELECT COUNT(*) AS total_orders FROM orders";
  $orders_result = $conn->query($orders_query);
  $orders_data = $orders_result->fetch_assoc();
  $total_orders = $orders_data['total_orders'] ?? 0;

  // Fetch active users
  $users_query = "SELECT COUNT(*) AS active_users FROM users";
  $users_result = $conn->query($users_query);
  $users_data = $users_result->fetch_assoc();
  $active_users = $users_data['active_users'] ?? 0;

  $admin_id = $_SESSION['admin_id'] ?? 1; // fallback to ID 1 for testing

  // Fetch admin details
  $sql = "SELECT Admin_name FROM admin WHERE Admin_id = $admin_id";
  $result = $conn->query($sql);
  $admin = $result->fetch_assoc();

  // Handle fallback
  $admin_name = $admin['Admin_name'] ?? 'Admin';
  $admin_initial = strtoupper($admin_name[0]);
  
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
        <li><a href="orders.php"><i class="fas fa-shopping-bag"></i> Orders</a></li>
        <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
        <li><a href="inventory.php"><i class="fas fa-boxes"></i> Inventory</a></li>
        <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
      </ul>
    </aside>

    <main class="main-content">

      <header class="main-header">
        <h1>Admin Dashboard</h1>
        <div class="user-profile">
          <div class="avatar"><?= $admin_initial ?></div>
          <span><?= htmlspecialchars($admin_name) ?></span>
        </div>
      </header>

      <section class="stats-grid">
        <div class="card">
          <h3>Total Sales</h3>
          <p>$<?php echo number_format($total_sales, 2); ?></p>
        </div>
        <div class="card">
          <h3>Weekly Revenue</h3>
          <p>$<?php echo number_format($weekly_revenue, 2); ?></p>
        </div>
        <div class="card">
          <h3>Orders</h3>
          <p><?php echo $total_orders; ?></p>
        </div>
        <div class="card">
          <h3>Active Users</h3>
          <p><?php echo $active_users; ?></p>
        </div>
      </section>


      <section class="quick-access">
        <h2>Quick Access</h2>
        <div class="buttons">
          <button><a href="product.php">Manage Products</a></button>
          <button><a href="orders.php">Manage Orders</a></button>
          <button><a href="users.php">Manage Users</a></button>
        </div>
      </section>

      <section class="recent-activities">
        <h2>Recent Activities</h2>
        <ul>
            <?php
            // Latest product added
            $productQuery = mysqli_query($conn, "SELECT name FROM products ORDER BY id DESC LIMIT 1");
            if ($product = mysqli_fetch_assoc($productQuery)) {
                echo '<li><i class="fas fa-check-circle"></i> Product "' . htmlspecialchars($product['name']) . '" added</li>';
            }

            // Latest order placed
            $orderQuery = mysqli_query($conn, "SELECT order_id FROM orders ORDER BY order_date DESC LIMIT 1");
            if ($order = mysqli_fetch_assoc($orderQuery)) {
                echo '<li><i class="fas fa-check-circle"></i> Order #' . $order['order_id'] . ' marked as placed</li>';
            }

            // Latest user signed up
            $userQuery = mysqli_query($conn, "SELECT full_name FROM users ORDER BY created_at DESC LIMIT 1");
            if ($user = mysqli_fetch_assoc($userQuery)) {
                echo '<li><i class="fas fa-check-circle"></i> New user "' . htmlspecialchars($user['full_name']) . '" signed up</li>';
            }
            ?>
        </ul>
    </section>

    </main>
  </div>
</body>
</html>
