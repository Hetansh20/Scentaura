<?php

session_start();

include 'db.php';

$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
if ($product_id <= 0) {
    die("Invalid product.");
}

$sql = "SELECT * FROM products WHERE id = $product_id";
$result = $conn->query($sql);
if (!$result || $result->num_rows == 0) {
    die("Product not found.");
}
$product = $result->fetch_assoc();

// Fetch related products by scent and gender
$scent = $conn->real_escape_string($product['scent']);
$gender = $conn->real_escape_string($product['gender']);
$related_sql = "SELECT * FROM products 
                WHERE id != $product_id AND (scent = '$scent' OR gender = '$gender') 
                LIMIT 4";
$related_result = $conn->query($related_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['name']) ?> | ScentAura</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="components/css/product_des.css">
    <link rel="stylesheet" href="components/css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap">
</head>
<body>

    <nav>
        <div class="logo">
            <a href="index.php"><img src="Components/Logo/logo.png" alt="ScentAura"></a>
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
                    <span id="cart-count">0</span><span id="cart-count">
    <?php
        $cart_count = 0;
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];
            $user_id_query = "SELECT id FROM users WHERE full_name = '$username'";
            $user_id_res = mysqli_query($conn, $user_id_query);

            if ($user_id_res && mysqli_num_rows($user_id_res) > 0) {
                $user_id_row = mysqli_fetch_assoc($user_id_res);
                $user_id_val = $user_id_row['id'];

                $query = "SELECT COUNT(*) as total FROM cart WHERE user_id = $user_id_val";
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);
                echo $row['total'];
            } else {
                echo "0";
            }
        } else {
            echo "0";
        }
    ?>
                    </span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="product-card">
        <div class="product-image">
            <img src="admin/<?= htmlspecialchars($product['image_path']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
        </div>
        <div class="product-info">
            <h2><?= htmlspecialchars($product['name']) ?></h2>
            <p class="price">$<?= number_format($product['price'], 2) ?></p>
            <p class="desc">
                <?= nl2br(htmlspecialchars($product['description'])) ?>
            </p>
            <ul class="product-attributes">
                <li><strong>Size:</strong> <?= htmlspecialchars($product['size']) ?></li>
                <li><strong>Pack Size:</strong> <?= htmlspecialchars($product['pack_size']) ?></li>
                <li><strong>Gender:</strong> <span class="badge"><?= htmlspecialchars($product['gender']) ?></span></li>
                <li><strong>Occasion:</strong>
                    <?php foreach (explode(',', $product['occasion']) as $occasion): ?>
                        <span class="badge"><?= trim($occasion) ?></span>
                    <?php endforeach; ?>
                </li>
                <li><strong>Concentration:</strong> <?= htmlspecialchars($product['concentration']) ?></li>
                <li><strong>Scent:</strong> <?= htmlspecialchars($product['scent']) ?></li>
                <li><strong>Stock:</strong> <?= (int)$product['stock'] ?> available</li>
            </ul>
            <button class="add-to-cart">Add to Cart</button>
        </div>
    </div>

    <?php if ($related_result && $related_result->num_rows > 0): ?>
        <div class="suggestions">
            <h3>You might also like</h3>
            <div class="suggestion-grid">
                <?php while ($rel = $related_result->fetch_assoc()): ?>
                    <div class="suggestion-card">
                        <a href="product.php?product_id=<?= $rel['id'] ?>">
                            <img src="admin/<?= htmlspecialchars($rel['image_path']) ?>" alt="<?= htmlspecialchars($rel['name']) ?>">
                            <h4><?= htmlspecialchars($rel['name']) ?></h4>
                            <p>$<?= number_format($rel['price'], 2) ?></p>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    <?php endif; ?>

    <footer>
        <div class="footer-container">
            <div class="footer-section about">
                <h3>About ScentAura</h3>
                <p>
                    ScentAura brings you premium fragrances that blend luxury and sophistication.
                    Elevate your senses with our exclusive collection of perfumes.
                </p>
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

    <script src="Components/JS/index.js?v=<?php echo time(); ?>"></script>

</body>
</html>
