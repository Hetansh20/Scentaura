<?php
session_start();
require '../db.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        $email = $_POST['admin_email'];
        $password = $_POST['admin_password'];
        
        $stmt = $conn->prepare("SELECT Admin_id, Admin_password FROM admin WHERE Admin_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashed_password);
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                header("Location: dashboard.php");
                exit;
            } else {
                $login_error = "Invalid password.";
            }
        } else {
            $login_error = "No account found with that email.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ScentAura - Admin Login</title>
    <link rel="icon" type="image/png" href="../Components/Logo/logo.png">
    <link rel="stylesheet" href="../Components/CSS/login.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap">
</head>
<body>
    <div class="auth-container">
        <div class="brand-column">
            <img src="../Components/Logo/logo.png" alt="ScentAura">
            <h2>ScentAura</h2>
            <p>Discover the world of luxury fragrances. Let your senses embark on a journey through our exclusive collection.</p>
            <div class="decorative-dots">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
            </div>
        </div>
        <div class="auth-form">
            <div id="login-form" class="active">
                <h2 class="form-heading">Welcome Back</h2>
                <?php if (isset($login_error)) echo "<p style='color: red;'>$login_error</p>"; ?>
                <form action="" method="post">
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="admin_email" placeholder="Enter your email address" required>
                    </div>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="admin_password" placeholder="Enter your password" required>
                    </div>
                    <div class="forgot-password">
                        <a href="#">Forgot Password?</a>
                    </div>
                    <button type="submit" name="login" class="submit-btn">Sign In</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
