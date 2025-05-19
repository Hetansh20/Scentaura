<?php
session_start();
include '../db.php';

// Fetch user data from the database
$sql = "SELECT id, full_name, email, role FROM users";
$result = $conn->query($sql);

// Check if there are any users
if ($result->num_rows > 0) {
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
} else {
    $users = [];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin User Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link rel="stylesheet" href="user.css">
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
        <li><a href="users.php" class="active"><i class="fas fa-users"></i> Users</a></li>
        <li><a href="inventory.php"><i class="fas fa-boxes"></i> Inventory</a></li>
        <li><a href="#"><i class="fas fa-headset"></i> Support</a></li>
        <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
      </ul>
    </aside>

    <div class="main-content">
        <h1>Users</h1>
        <table class="user-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><?= $user['full_name'] ?></td>
                            <td><?= $user['email'] ?></td>
                            <td><?= $user['role'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
