<?php
session_start();

// Redirect if user not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$username = $conn->real_escape_string($_SESSION['username']);

// Fetch user ID from users table using `username`
$user_query = $conn->query("SELECT id FROM users WHERE full_name = '$username'");
if ($user_query && $user_query->num_rows === 1) {
    $user_row = $user_query->fetch_assoc();
    $user_id = intval($user_row['id']);
} else {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Handle remove
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove'], $_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    $conn->query("DELETE FROM cart WHERE user_id = $user_id AND product_id = $product_id");
}

// Handle quantity update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'], $_POST['product_id'], $_POST['quantity'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = max(1, intval($_POST['quantity']));
    $conn->query("UPDATE cart SET quantity = $quantity WHERE user_id = $user_id AND product_id = $product_id");
}

// Fetch cart items
$sql = "SELECT c.quantity, p.* 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = $user_id";
$result = $conn->query($sql);

// Fetch cart count
$count_sql = "SELECT SUM(quantity) AS total FROM cart WHERE user_id = $user_id";
$count_result = $conn->query($count_sql);
$cart_count = 0;
if ($count_result && $count_row = $count_result->fetch_assoc()) {
    $cart_count = $count_row['total'] ?? 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>ScentAura Cart</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="components/css/index.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="components/css/cart.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap">
</head>
<body>

<!-- Navigation -->
<nav>
    <div class="logo">
        <a href="index.php"><img src="Components/Logo/logo.png" alt="ScentAura Logo"></a>
    </div>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="products.php">Products</a></li>
        <li><a href="blog.php">Blog</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li>
            <input type="text" placeholder="Search products...">
            <input type="button" value="Search">
        </li>
        <li class="profile-dropdown">
            <div class="dropdown">
                <button class="user-icon">
                    <i class="fa-solid fa-circle-user"></i>
                </button>
                <div class="dropdown-menu">
                    <?php if (isset($_SESSION['username'])): ?>
                        <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span><br>
                        <a href="profile.php">My Profile</a><br>
                        <a href="logout.php">Logout</a>
                    <?php else: ?>
                        <a href="login.php">LOGIN</a><br>
                        <a href="login.php#show-signup">SIGNUP</a>
                    <?php endif; ?>
                </div>
            </div>
        </li>
        <li class="cart">
            <a href="cart.php">
                <i class="fa-solid fa-cart-shopping"></i>
                <span id="cart-count"><?= $cart_count ?></span>
            </a>
        </li>
    </ul>
</nav>

<!-- Cart Section -->
<div class="cart-container">
    <h2>Your Cart</h2>
    <?php
    $total_price = 0;
    if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php
                $product_id = intval($row['id']);
                $name = htmlspecialchars($row['name']);
                $quantity = intval($row['quantity']);
                $price = floatval($row['price']);
                $total_item_price = $price * $quantity;
                $total_price += $total_item_price;
                $image_path = htmlspecialchars('admin/' . ($row['image_path'] ?: 'Components/Images/default.png'));
            ?>
            <div class="cart-item">
                <img src="<?= $image_path ?>" alt="<?= $name ?>">
                <div class="details">
                    <h3><?= $name ?></h3>
                    <p><strong>Price:</strong> $<?= number_format($price, 2) ?></p>

                    <!-- Quantity Update -->
                    <form method="POST" class="quantity-form">
                        <input type="hidden" name="product_id" value="<?= $product_id ?>">
                        <label>Qty:</label>
                        <input type="number" name="quantity" min="1" value="<?= $quantity ?>" required>
                        <button type="submit" name="update_quantity">Update</button>
                    </form>

                    <!-- Remove Button -->
                    <form method="POST" class="remove-form">
                        <input type="hidden" name="product_id" value="<?= $product_id ?>">
                        <button type="submit" name="remove" onclick="return confirm('Remove this item?')">Remove</button>
                    </form>

                    <p class="subtotal"><strong>Subtotal:</strong> $<?= number_format($total_item_price, 2) ?></p>
                </div>
            </div>
        <?php endwhile; ?>

        <div class="cart-total">
            <h3>Total: $<?= number_format($total_price, 2) ?></h3>
            <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
        </div>
    <?php else: ?>
        <p>No items in cart.</p>
    <?php endif; ?>
</div>

<!-- Footer -->
<footer>
    <div class="footer-container">
        <div class="footer-section about">
            <h3>About ScentAura</h3>
            <p>ScentAura brings you premium fragrances that blend luxury and sophistication. Elevate your senses with our exclusive collection of perfumes.</p>
        </div>
        <div class="footer-section links">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="blog.php">Blog</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </div>
        <div class="footer-section social">
            <h3>Follow Us</h3>
            <div class="social-icons">
                <a href="https://www.facebook.com/" target="_blank"><i class="fa-brands fa-facebook"></i></a>
                <a href="https://www.instagram.com/" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                <a href="https://x.com/" target="_blank"><i class="fa-brands fa-twitter"></i></a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2025 <span>ScentAura</span>. All rights reserved.</p>
    </div>
</footer>

<script src="Components/JS/index.js?v=<?= time(); ?>"></script>

</body>
</html>
