<?php
    session_start();
    include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - ScentAura</title>
    <link rel="icon" type="image/png" href="Components/Logo/logo.png">
    <link rel="stylesheet" href="Components/CSS/index.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="Components/CSS/about.css?v=<?php echo time(); ?>">
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
            <li><a href="Contact.php">Contact</a></li>
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

    <header class="about-header">
        <img src="components/images/about.png" alt="">
        <div class="header-content">
            <h1>Our Story</h1>
            <p class="subtitle">Crafting Memories Through Scent</p>
            <p class="header-description">Since 2010, ScentAura has been creating exceptional fragrances that capture moments, 
            evoke emotions, and tell stories. Each scent is carefully crafted by our master perfumers, 
            blending traditional artistry with modern innovation.</p>
        </div>
    </header>

    <section class="about-intro">
        <div class="container">
            <div class="intro-content">
                <span class="accent-text">Est. 2010</span>
                <h2>Creating Timeless Fragrances</h2>
                <p>At ScentAura, we believe that a perfume is more than just a scent—it's an expression of individuality, a memory captured in a bottle, a story waiting to be told. Our journey began with a simple passion for creating extraordinary fragrances that speak to the soul.</p>
            </div>
            <div class="intro-image">
                <img src="components/images/about_sec.png" alt="Perfume Crafting">
            </div>
        </div>
    </section>

    <section class="values">
        <div class="container">
            <h2>Our Core Values</h2>
            <div class="values-grid">
                <div class="value-card">
                    <i class="fas fa-gem"></i>
                    <h3>Quality</h3>
                    <p>We source only the finest ingredients from around the world, ensuring each fragrance meets our exceptional standards.</p>
                </div>
                <div class="value-card">
                    <i class="fas fa-leaf"></i>
                    <h3>Sustainability</h3>
                    <p>Our commitment to the environment is reflected in our eco-friendly practices and responsible sourcing.</p>
                </div>
                <div class="value-card">
                    <i class="fas fa-heart"></i>
                    <h3>Passion</h3>
                    <p>Every scent is crafted with dedication and love, bringing artistry to modern perfumery.</p>
                </div>
                <div class="value-card">
                    <i class="fas fa-lightbulb"></i>
                    <h3>Innovation</h3>
                    <p>We blend traditional craftsmanship with modern techniques to create unique, lasting fragrances.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="journey">
        <div class="container">
            <h2>Our Journey</h2>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="year">2010</div>
                    <div class="content">
                        <h3>The Beginning</h3>
                        <p>ScentAura was founded with a vision to create unique, memorable fragrances.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="year">2015</div>
                    <div class="content">
                        <h3>Global Expansion</h3>
                        <p>Opened our first international boutiques in Paris and Dubai.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="year">2020</div>
                    <div class="content">
                        <h3>Innovation</h3>
                        <p>Launched our sustainable collection and digital scent experience.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="year">2025</div>
                    <div class="content">
                        <h3>Present Day</h3>
                        <p>Continuing to innovate and create exceptional fragrances for our global community.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="team">
        <div class="container">
            <h2>Meet Our Artisans</h2>
            <div class="team-grid">
                <div class="team-member">
                    <img src="components/images/2.jpg" alt="Master Perfumer">
                    <h3>Sophie Laurent</h3>
                    <p class="title">Master Perfumer</p>
                    <p class="bio">With over 20 years of experience in fine perfumery, Sophie brings artistry and innovation to every creation.</p>
                </div>
                <div class="team-member">
                    <img src="components/images/4.jpg" alt="Creative Director">
                    <h3>Marcus Chen</h3>
                    <p class="title">Creative Director</p>
                    <p class="bio">Marcus leads our creative vision, bringing fresh perspectives to traditional perfumery.</p>
                </div>
                <div class="team-member">
                    <img src="components/images/5.jpg" alt="Sustainability Lead">
                    <h3>Elena Silva</h3>
                    <p class="title">Sustainability Lead</p>
                    <p class="bio">Elena ensures our practices maintain harmony with nature while creating extraordinary scents.</p>
                </div>
            </div>
        </div>
    </section>

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
                    <li><a href="index.html">Home</a></li>
                    <li><a href="#">Products</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="about.html">About</a></li>
                    <li><a href="#">Contact</a></li>
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