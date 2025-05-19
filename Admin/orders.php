<?php
// Include your database connection
  include '../db.php';
  session_start();

  if (!isset($_SESSION['user_id'])) {
      header("Location: admin_login.php");
      exit;
  }

// Fetch orders from the database
$orderQuery = "SELECT * FROM orders ORDER BY order_date DESC";
$result = mysqli_query($conn, $orderQuery);
?>

<div class="admin-container">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="sidebar-logo">
        <img src="../Components/Logo/logo.png" alt="ScentAura Logo">
        <link rel="stylesheet" href="style.css"?v=<?php echo time(); ?>/>
        <link rel="stylesheet" href="orders.css"?v=<?php echo time(); ?>/>
      </div>
      <ul class="nav-links">
        <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="product.php"><i class="fas fa-spray-can"></i> Products</a></li>
        <li><a href="featured_products.php"><i class="fas fa-spray-can"></i>Featued Products</a></li>
        <li><a href="brand.php"><i class="fas fa-spray-can"></i>Brand</a></li>
        <li><a href="orders.php" class="active"><i class="fas fa-shopping-bag"></i> Orders</a></li>
        <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
        <li><a href="inventory.php"><i class="fas fa-boxes"></i> Inventory</a></li>
        <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
      </ul>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="main-header">
            <h1>Orders</h1>
            <div class="user-profile">
                <div class="avatar">A</div>
                <p>Admin</p>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="orders-table">
            <h2>Order List</h2>
            <table border="1" cellpadding="10">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User ID</th>
                        <th>Total Amount</th>
                        <th>Payment Status</th>
                        <th>Order Status</th>
                        <th>Order Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($order = mysqli_fetch_assoc($result)) {
                            echo '<tr>';
                            echo '<td>' . $order['order_id'] . '</td>';
                            echo '<td>' . $order['user_id'] . '</td>';
                            echo '<td>' . $order['total_amount'] . '</td>';
                            echo '<td>' . ucfirst($order['payment_status']) . '</td>';
                            echo '<td>' . ucfirst($order['order_status']) . '</td>';
                            echo '<td>' . $order['order_date'] . '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="6">No orders found</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

