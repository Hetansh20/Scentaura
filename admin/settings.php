<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: admin_login.php");
    exit;
}

$adminId = $_SESSION['user_id'];

// Fetch current admin details
$result = $conn->query("SELECT * FROM admin WHERE Admin_id = $adminId");
$admin = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_account'])) {
        $name = $conn->real_escape_string($_POST['admin_name']);
        $email = $conn->real_escape_string($_POST['admin_email']);
        $password = !empty($_POST['admin_password']) ? password_hash($_POST['admin_password'], PASSWORD_DEFAULT) : $admin['Admin_password'];

        $conn->query("UPDATE admin SET Admin_name='$name', Admin_email='$email', Admin_password='$password' WHERE Admin_id=$adminId");
        echo "<script>alert('Account settings updated successfully.');</script>";
    }

    if (isset($_POST['save_preferences'])) {
        $currency = $conn->real_escape_string($_POST['currency']);
        $timezone = $conn->real_escape_string($_POST['timezone']);
        $notifications = isset($_POST['notifications']) ? 1 : 0;
        $dark_mode = isset($_POST['dark_mode']) ? 1 : 0;
        $payment_gateway = $conn->real_escape_string($_POST['payment_gateway']);
        $shipping_zone = $conn->real_escape_string($_POST['shipping_zone']);

        $conn->query("UPDATE admin SET 
            currency='$currency',
            timezone='$timezone',
            notifications=$notifications,
            dark_mode=$dark_mode,
            payment_gateway='$payment_gateway',
            shipping_zone='$shipping_zone'
            WHERE Admin_id=$adminId");

        echo "<script>alert('Preferences saved successfully.');</script>";
    }

    // Reload updated data
    $result = $conn->query("SELECT * FROM admin WHERE Admin_id = $adminId");
    $admin = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Settings | ScentAura Admin</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="settings.css">
</head>
<body>
    <aside class="sidebar">
    <div class="sidebar-logo">
        <img src="../components/logo/logo.png" alt="ScentAura Logo">
    </div>
    <ul class="nav-links">
        <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="product.php"><i class="fas fa-spray-can"></i> Products</a></li>
        <li><a href="featured_products.php"><i class="fas fa-spray-can"></i>Featued Products</a></li>
        <li><a href="brand.php"><i class="fas fa-spray-can"></i>Brand</a></li>
        <li><a href="orders.php"><i class="fas fa-shopping-bag"></i> Orders</a></li>
        <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
        <li><a href="inventory.php"><i class="fas fa-boxes"></i> Inventory</a></li>
        <li><a href="settings.php" class="active"><i class="fas fa-cog"></i> Settings</a></li>
    </ul>
    </aside>

    <div class="main">
    <h1>Settings</h1>
    <form method="POST">
        <!-- Account Settings -->
        <div class="section">
        <h2>Account Settings</h2>
        <div class="form-group">
            <label for="admin_name">Admin Name</label>
            <input type="text" name="admin_name" value="<?= isset($admin['Admin_name']) ? $admin['Admin_name'] : '' ?>">
        </div>
        <div class="form-group">
            <label for="admin_email">Email</label>
            <input type="email" name="admin_email" value="<?= isset($admin['Admin_email']) ? $admin['Admin_email'] : '' ?>">
        </div>
        <div class="form-group">
            <label for="admin_password">Password</label>
            <input type="password" id="admin_password" name="admin_password" placeholder="Leave blank to keep current">
        </div>
        <button type="submit" name="update_account">Update Account</button>
        </div>

    </form>
    </div>

</body>
</html>
