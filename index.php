<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';

include 'db.php';

// Fetch featured products and join with product details
$sql = "SELECT p.name, p.gender, p.price, p.image_path
        FROM featured_products fp
        JOIN products p ON fp.product_id = p.id";
$result = $conn->query($sql);

$featuredProducts = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $featuredProducts[] = $row;
    }
}

$newsletterStatus = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $conn->prepare("INSERT IGNORE INTO newsletter_subscribers (email) VALUES (?)");
        $stmt->bind_param("s", $email);

        if ($stmt->execute()) {
            // Create admin and user mailers separately
            $adminMailer = new PHPMailer(true);
            $userMailer = new PHPMailer(true);

            try {
                // --- Admin Mailer Config ---
                $adminMailer->isSMTP();
                $adminMailer->Host = 'smtp.gmail.com';
                $adminMailer->SMTPAuth = true;
                $adminMailer->Username = 'scentaura25@gmail.com';
                $adminMailer->Password = 'ekcl ptfi fdal ujkz'; // Gmail App Password
                $adminMailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $adminMailer->Port = 587;
                $adminMailer->isHTML(true);
                $adminMailer->setFrom('scentaura25@gmail.com', 'ScentAura');

                // Admin Email Content
                $adminMailer->addAddress('scentaura25@gmail.com');
                $adminMailer->Subject = "New Newsletter Subscriber";
                $adminMailer->Body = "
                    <html>
                    <head><title>New Subscriber</title></head>
                    <body style='font-family: Arial, sans-serif;'>
                        <h2>New Subscriber</h2>
                        <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
                        <p><strong>Subscribed At:</strong> " . date("Y-m-d H:i:s") . "</p>
                    </body>
                    </html>
                ";

                // --- User Mailer Config ---
                $userMailer->isSMTP();
                $userMailer->Host = 'smtp.gmail.com';
                $userMailer->SMTPAuth = true;
                $userMailer->Username = 'scentaura25@gmail.com';
                $userMailer->Password = 'ekcl ptfi fdal ujkz';
                $userMailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $userMailer->Port = 587;
                $userMailer->isHTML(true);
                $userMailer->setFrom('scentaura25@gmail.com', 'ScentAura');

                // User Confirmation Email
                $userMailer->addAddress($email);
                $userMailer->Subject = "Thanks for subscribing to ScentAura!";
                $userMailer->Body = "
                    <html>
                    <body style='font-family: Arial, sans-serif;'>
                        <p>Hi there!</p>
                        <p>Thank you for subscribing to <strong>ScentAura</strong></p>
                        <p>You’ll now receive updates on new arrivals, exclusive offers, and more.</p>
                        <br>
                        <p>Stay scented!<br>ScentAura Team</p>
                    </body>
                    </html>
                ";

                // --- Send Emails ---
                $adminMailer->send(); // optional: check result separately
                $userMailer->send();

                $newsletterStatus = "success";
                // echo "Thank you for subscribing!";
            } catch (Exception $e) {
                $newsletterStatus = "email_failed";
                echo "Mailer Error: " . $e->getMessage();
            }
        } else {
            $newsletterStatus = "db_failed";
            echo "Database error. Please try again.";
        }

        $stmt->close();
    } else {
        $newsletterStatus = "invalid_email";
        echo "Invalid email address.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send'])) {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['contact_email']) ? trim($_POST['contact_email']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    if (!empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($message)) {
        $adminMailer = new PHPMailer(true);
        $userMailer = new PHPMailer(true);

        try {
            // Admin Mail
            $adminMailer->isSMTP();
            $adminMailer->Host = 'smtp.gmail.com';
            $adminMailer->SMTPAuth = true;
            $adminMailer->Username = 'scentaura25@gmail.com';
            $adminMailer->Password = 'ekcl ptfi fdal ujkz'; // App Password
            $adminMailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $adminMailer->Port = 587;
            $adminMailer->isHTML(true);
            $adminMailer->setFrom('scentaura25@gmail.com', 'ScentAura');
            $adminMailer->addAddress('scentaura25@gmail.com');
            $adminMailer->Subject = "New Contact Form Submission";
            $adminMailer->Body = "
                <h3>New Contact Message</h3>
                <p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>
                <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
                <p><strong>Message:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>
            ";

            // User Confirmation Mail
            $userMailer->isSMTP();
            $userMailer->Host = 'smtp.gmail.com';
            $userMailer->SMTPAuth = true;
            $userMailer->Username = 'scentaura25@gmail.com';
            $userMailer->Password = 'ekcl ptfi fdal ujkz';
            $userMailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $userMailer->Port = 587;
            $userMailer->isHTML(true);
            $userMailer->setFrom('scentaura25@gmail.com', 'ScentAura');
            $userMailer->addAddress($email);
            $userMailer->Subject = "Thanks for contacting ScentAura!";
            $userMailer->Body = "
                <p>Hi <strong>" . htmlspecialchars($name) . "</strong>,</p>
                <p>Thank you for reaching out to <strong>ScentAura</strong>. We've received your message:</p>
                <blockquote>" . nl2br(htmlspecialchars($message)) . "</blockquote>
                <p>We'll get back to you shortly.</p>
                <br>
                <p>Best Regards,<br>ScentAura Team</p>
            ";

            $adminMailer->send();
            $userMailer->send();

            // echo "<p style='color: green;'>Message sent successfully! Check your email for confirmation.</p>";
        } catch (Exception $e) {
            // echo "<p style='color: red;'>Message could not be sent. Mailer Error: {$e->getMessage()}</p>";
        }
    } else {
        // echo "<p style='color: red;'>Please fill in all fields correctly.</p>";
    }
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ScentAura</title>
    <link rel="icon" type="image/png" href="Components/Logo/logo.png">
    <link rel="stylesheet" href="Components/CSS/index.css?v=<?php echo time(); ?>">
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

    <!-- Hero Section -->
    <div class="image">
        <img src="Components/Images/1.jpg" alt="Hero Section Image">
    </div>
    
    <!-- Introduction Section -->
    <section class="intro">
        <div class="intro-content">
            <h1>Welcome to ScentAura</h1>
            <p>
                Discover the world of luxury fragrances. At ScentAura, we curate exquisite scents
                that define sophistication and elegance. Let your senses embark on a journey
                through our exclusive collection.
            </p>
            <a href="#featured-products"><button>Explore Now</button></a>
        </div>
    </section>
    
    <!-- Featured Products Section -->
    <section class="featured-products" id="featured-products">
        <h2>Featured Products</h2>
        <div class="card-wrapper">
            <?php foreach ($featuredProducts as $product): ?>
                <div class="perfume-card">
                    <div class="image-container">
                        <img src="admin/<?= htmlspecialchars($product['image_path']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    </div>
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p><strong>Gender:</strong> <?= htmlspecialchars($product['gender']) ?></p>
                    <p><strong>Price:</strong> $<?= htmlspecialchars($product['price']) ?></p>
                    <a href="#"><button>Shop Now</button></a>
                </div>
            <?php endforeach; ?>

            <?php if (empty($featuredProducts)): ?>
                <p style="text-align:center;">No featured products found.</p>
            <?php endif; ?>
        </div>

    </section>

    <!-- Testimonials Section -->
    <section class="testimonials">
        <h2>What Our Customers Say</h2>
        <div class="slider-container">
          <div class="slider">
            <div class="slide">
              <p>"ScentWave Classic is my go-to fragrance. It's sophisticated yet subtle!"</p>
              <span>- Maria J.</span>
            </div>
            <div class="slide">
              <p>"I can't get enough of ScentWave Modern. The citrus notes are so refreshing."</p>
              <span>- Liam K.</span>
            </div>
            <div class="slide">
              <p>"ScentWave Noir is the perfect evening scent. It's bold and daring."</p>
              <span>- Sophie L.</span>
            </div>
            <div class="slide">
              <p>"ScentWave Fresh is my daily pick. Light, airy, and perfect for work."</p>
              <span>- Noah M.</span>
            </div>
            <div class="slide">
              <p>"ScentWave Blossom is a floral dream. I get compliments all the time!"</p>
              <span>- Olivia R.</span>
            </div>
          </div>
          <button class="prev" aria-label="Previous">❮</button>
          <button class="next" aria-label="Next">❯</button>
        </div>
    </section>     

    <!-- Newsletter Signup -->
    <section class="newsletter">
        <h3>Stay Updated</h3>
        <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit">Subscribe</button>
        </form>

        <?php if ($newsletterStatus == "success"): ?>
            <p style="color: green;">Thank you for subscribing!</p>
        <?php elseif ($newsletterStatus == "email_failed"): ?>
            <p style="color: red;">Failed to send email. Try again later.</p>
        <?php elseif ($newsletterStatus == "invalid_email"): ?>
            <p style="color: red;">Please enter a valid email.</p>
        <?php endif; ?>
    </section>

    <!-- Contact Form -->
    <section id="contact" class="contact-form">
        <h2>Contact Us</h2>
        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="contact_email" placeholder="Your Email" required>
            <textarea name="message" placeholder="Your Message" required></textarea>
            <button type="submit" name="send">Send Message</button>
        </form>
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

    <!-- Back to Top Button -->
    <div id="backToTop" class="back-to-top" onclick="scrollToTop()">↑</div>

    <script src="Components/JS/index.js?v=<?php echo time(); ?>"></script>
</body>
</html>

<?php

    $conn->close();

?>