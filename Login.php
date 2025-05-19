<?php
session_start();

include 'db.php';

// Signup form handler
if (isset($_POST['signup'])) {
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password !== $confirm) {
        echo "Passwords do not match.";
        exit;
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        echo "Email already exists.";
        exit;
    }

    $insert = $conn->query("INSERT INTO users (full_name, email, password) VALUES ('$fullname', '$email', '$hashed')");
    if ($insert) {
        $_SESSION['username'] = $user['full_name'];
        header("Location: index.php");
    } else {
        echo "Signup failed. Try again.";
    }
    exit;
}

// Login form handler
if (isset($_POST['login'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['full_name'];
            header("Location: index.php");
            exit;
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "No user found with this email.";
    }
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ScentAura - Login</title>
    <link rel="icon" type="image/png" href="Components/Logo/logo.png">
    <link rel="stylesheet" href="Components/CSS/login.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap">
</head>
<body>
    <div class="auth-container">
        <div class="brand-column">
            <img src="Components/Logo/logo.png" alt="ScentAura">
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
            <!-- Login Form -->
            <div id="login-form" class="active">
                <h2 class="form-heading">Welcome Back</h2>
                
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" placeholder="Enter your email address" name="email" required>
                    </div>
                    
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" placeholder="Enter your password" name="password" required>
                    </div>
                    
                    <div class="forgot-password">
                        <a href="#">Forgot Password?</a>
                    </div>
                    
                    <button type="submit" class="submit-btn" name="login">Sign In</button>
                    
                    <div class="alternate-auth">
                        <p>Don't have an account? <a href="#" id="show-signup">Sign Up</a></p>
                    </div>
                </form>
                
                <div class="or-divider">or continue with</div>
                
                <div class="social-auth">
                    <div class="social-btn facebook">
                        <i class="fab fa-facebook-f"></i>
                    </div>
                    <div class="social-btn google">
                        <i class="fab fa-google"></i>
                    </div>
                    <div class="social-btn twitter">
                        <i class="fab fa-twitter"></i>
                    </div>
                </div>
            </div>
            
            <!-- Signup Form -->
            <div id="signup-form">
                <h2 class="form-heading">Create Account</h2>
                
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="text" placeholder="Your full name" name="fullname" required >
                    </div>
                    
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" placeholder="Your email address" name="email" required>
                    </div>
                    
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" placeholder="Create a password" name="password" required>
                    </div>
                    
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" placeholder="Confirm your password" name="confirm_password" required>
                    </div>
                    
                    <button type="submit" class="submit-btn" name="signup">Sign Up</button>
                    
                    <div class="alternate-auth">
                        <p>Already have an account? <a href="#" id="show-login">Sign In</a></p>
                    </div>
                </form>
                
                <div class="or-divider">or sign up with</div>
                
                <div class="social-auth">
                    <div class="social-btn facebook">
                        <i class="fab fa-facebook-f"></i>
                    </div>
                    <div class="social-btn google">
                        <i class="fab fa-google"></i>
                    </div>
                    <div class="social-btn twitter">
                        <i class="fab fa-twitter"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const loginForm = document.getElementById('login-form');
            const signupForm = document.getElementById('signup-form');
            const showSignup = document.getElementById('show-signup');
            const showLogin = document.getElementById('show-login');
            
            showSignup.addEventListener('click', (e) => {
                e.preventDefault();
                loginForm.classList.remove('active');
                setTimeout(() => {
                    signupForm.classList.add('active');
                }, 50);
            });
            
            showLogin.addEventListener('click', (e) => {
                e.preventDefault();
                signupForm.classList.remove('active');
                setTimeout(() => {
                    loginForm.classList.add('active');
                }, 50);
            });
        });
    </script>
</body>
</html>