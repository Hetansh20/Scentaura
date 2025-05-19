<?php
include '../db.php';

$adminId = 1; // Assumes single admin for simplicity

// Fetch current admin details
$result = $conn->query("SELECT * FROM admin WHERE admin_id = $adminId");
$admin = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_account'])) {
        $name = $conn->real_escape_string($_POST['admin_name']);
        $email = $conn->real_escape_string($_POST['admin_email']);
        $password = !empty($_POST['admin_password']) ? password_hash($_POST['admin_password'], PASSWORD_DEFAULT) : $admin['password'];

        $conn->query("UPDATE admin SET admin_name='$name', admin_email='$email', admin_password='$password' WHERE admin_id=$adminId");
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
            WHERE admin_id=$adminId");

        echo "<script>alert('Preferences saved successfully.');</script>";
    }

    // Reload updated data
    $result = $conn->query("SELECT * FROM admin WHERE admin_id = $adminId");
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
        <img src="../Components/Logo/logo.png" alt="ScentAura Logo">
    </div>
    <ul class="nav-links">
        <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="product.php"><i class="fas fa-spray-can"></i> Products</a></li>
        <li><a href="featured_products.php"><i class="fas fa-spray-can"></i>Featued Products</a></li>
        <li><a href="brand.php"><i class="fas fa-spray-can"></i>Brand</a></li>
        <li><a href="#"><i class="fas fa-shopping-bag"></i> Orders</a></li>
        <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
        <li><a href="inventory.php"><i class="fas fa-boxes"></i> Inventory</a></li>
        <li><a href="#"><i class="fas fa-headset"></i> Support</a></li>
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

        <!-- Store Preferences -->
        <div class="section">
        <h2>Store Preferences</h2>
        <div class="form-group">
            <label for="currency">Default Currency</label>
            <select name="currency" id="currency">
            <option value="usd" <?= $admin['currency'] == 'usd' ? 'selected' : '' ?>>USD ($)</option>
            <option value="eur" <?= $admin['currency'] == 'eur' ? 'selected' : '' ?>>EUR (€)</option>
            <option value="inr" <?= $admin['currency'] == 'inr' ? 'selected' : '' ?>>INR (₹)</option>
            </select>
        </div>
        <div class="form-group">
            <label for="timezone">Timezone</label>
            <select name="timezone" id="timezone">
            <option value="utc" <?= $admin['timezone'] == 'utc' ? 'selected' : '' ?>>UTC</option>
            <option value="est" <?= $admin['timezone'] == 'est' ? 'selected' : '' ?>>EST</option>
            <option value="ist" <?= $admin['timezone'] == 'ist' ? 'selected' : '' ?>>IST</option>
            </select>
        </div>
        </div>

        <!-- Notification Settings -->
        <div class="section">
        <h2>Notification Preferences</h2>
        <div class="form-group checkbox">
            <label><input type="checkbox" name="notifications" <?= $admin['notifications'] ? 'checked' : '' ?>> Enable Email Notifications</label>
        </div>
        </div>

        <!-- UI Preferences -->
        <div class="section">
        <h2>UI Preferences</h2>
        <div class="form-group checkbox">
            <label><input type="checkbox" name="dark_mode" <?= $admin['dark_mode'] ? 'checked' : '' ?>> Enable Dark Mode</label>
        </div>
        </div>

        <!-- Payment Settings -->
        <div class="section">
        <h2>Payment Settings</h2>
        <div class="form-group">
            <label for="payment_gateway">Preferred Payment Gateway</label>
            <select name="payment_gateway" id="payment_gateway">
            <option value="paypal" <?= $admin['payment_gateway'] == 'paypal' ? 'selected' : '' ?>>PayPal</option>
            <option value="razorpay" <?= $admin['payment_gateway'] == 'razorpay' ? 'selected' : '' ?>>Razorpay</option>
            <option value="stripe" <?= $admin['payment_gateway'] == 'stripe' ? 'selected' : '' ?>>Stripe</option>
            </select>
        </div>
        </div>

        <!-- Shipping Settings -->
        <div class="section">
        <h2>Shipping Settings</h2>
        <div class="form-group">
            <label for="shipping_zone">Shipping Zone</label>
            <select name="shipping_zone" id="shipping_zone">
            <option value="domestic" <?= $admin['shipping_zone'] == 'domestic' ? 'selected' : '' ?>>Domestic</option>
            <option value="international" <?= $admin['shipping_zone'] == 'international' ? 'selected' : '' ?>>International</option>
            </select>
        </div>
        </div>

        <button type="submit" name="save_preferences">Save All Preferences</button>
    </form>
    </div>

</body>
</html>
