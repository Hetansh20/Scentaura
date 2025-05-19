<?php
    session_start();
    include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - ScentAura</title>
    <link rel="icon" type="image/png" href="Components/Logo/logo.png">
    <link rel="stylesheet" href="components/css/index.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="components/css/blog.css?v=<?php echo time(); ?>">
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
                <a href="cart.html">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span id="cart-count">
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

    <div id="popup-overlay" class="popup-overlay" onclick="closePopup('popup1'); closePopup('popup2'); closePopup('popup3');"></div>
    
    <header class="blog-header">
        <img src="components/images/blog_bg.png" alt="">
        <h1>Fragrance Journal</h1>
        <p class="subtitle">Discover the Art of Perfumery</p>
    </header>

    <section class="blog">
        <div class="container">
            <div class="featured-post">
                <div class="featured-image">
                    <img src="components/images/floral.png" alt="Floral Fragrance">
                    <div class="category">Featured</div>
                </div>
                <div class="featured-content">
                    <span class="date">February 15, 2025</span>
                    <h2>The Essence of Floral Fragrances</h2>
                    <p>Journey through the enchanting world of floral perfumery, from delicate rose to exotic jasmine. Discover how master perfumers capture nature's most beautiful scents in every bottle.</p>
                    <button class="read-more" onclick="openPopup('popup1')">Read Article <i class="fas fa-arrow-right"></i></button>
                </div>
            </div>

            <div class="blog-grid">
                <article class="post">
                    <div class="post-image">
                        <img src="components/images/woody.png" alt="Woody Fragrance">
                        <div class="category">Perfume Notes</div>
                    </div>
                    <div class="post-content">
                        <span class="date">February 14, 2025</span>
                        <h2>Woody Notes and Their Charm</h2>
                        <p>Explore the depth and warmth of woody fragrances, from sacred oud to mystical sandalwood.</p>
                        <button class="read-more" onclick="openPopup('popup2')">Read Article <i class="fas fa-arrow-right"></i></button>
                    </div>
                </article>

                <article class="post">
                    <div class="post-image">
                        <img src="components/images/citrus.png" alt="Citrus Fragrance">
                        <div class="category">Seasonal</div>
                    </div>
                    <div class="post-content">
                        <span class="date">February 13, 2025</span>
                        <h2>Citrus Scents for a Fresh Start</h2>
                        <p>Invigorate your senses with the zesty brilliance of citrus perfumes perfect for spring.</p>
                        <button class="read-more" onclick="openPopup('popup3')">Read Article <i class="fas fa-arrow-right"></i></button>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <div id="popup1" class="popup">
        <span class="close" onclick="closePopup('popup1')">&times;</span>
        <img src="components/images/floral.png" alt="Floral Fragrance">
        <span class="popup-date">February 15, 2025</span>
        <h2>The Essence of Floral Fragrances</h2>
        <p>Journey through the enchanting world of floral perfumery, from delicate rose to exotic jasmine. Discover how master perfumers capture nature's most beautiful scents in every bottle.</p>
    </div>

    <div id="popup2" class="popup">
        <span class="close" onclick="closePopup('popup2')">&times;</span>
        <img src="components/images/woody.png" alt="Floral Fragrance">
        <span class="popup-date">February 14, 2025</span>
        <h2>Woody Notes and Their Charm</h2>
        <p>Explore the depth and warmth of woody fragrances, from sacred oud to mystical sandalwood.</p>
    </div>

    <div id="popup3" class="popup">
        <span class="close" onclick="closePopup('popup3')">&times;</span>
        <img src="components/images/citrus.png" alt="Floral Fragrance">
        <span class="popup-date">February 13, 2025</span>
        <h2>Citrus Scents for a Fresh Start</h2>
        <p>Invigorate your senses with the zesty brilliance of citrus perfumes perfect for spring.</p>
    </div>

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
                    <li><a href="#">Products</a></li>
                    <li><a href="blog.php">Blog</a></li>
                    <li><a href="about.php">About</a></li>
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

    <script>
        function openPopup(id) {
            document.getElementById(id).style.display = 'block';
            document.getElementById('popup-overlay').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
        
        function closePopup(id) {
            document.getElementById(id).style.display = 'none';
            document.getElementById('popup-overlay').style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    </script>

    <script src="Components/JS/index.js?v=<?php echo time(); ?>"></script>

</body>
</html>