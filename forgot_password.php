<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include 'db.php';

$message = "";
$messageType = ""; // "success" or "error"

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['request_reset'])) {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Escaping input
        $emailEscaped = $conn->real_escape_string($email);

        // Check if the user is registered in the users table
        $checkUser = $conn->query("SELECT * FROM users WHERE email='$emailEscaped'");
        if ($checkUser->num_rows == 1) {
            // Generate a secure random token
            $token = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Insert or replace token in database
            $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?) 
                                    ON DUPLICATE KEY UPDATE token = VALUES(token), expires_at = VALUES(expires_at)");
            $stmt->bind_param("sss", $email, $token, $expiresAt);

            if ($stmt->execute()) {
                // Determine host and protocol dynamically to support local (localhost / docker-compose) vs production
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                $host = $_SERVER['HTTP_HOST'];
                // Get directory path and clean up potential duplicate slashes
                $dir = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                $resetLink = $protocol . $host . $dir . '/reset_password.php?email=' . urlencode($email) . '&token=' . $token;

                // Send email using PHPMailer
                $mail = new PHPMailer(true);

                try {
                    // SMTP configuration
                    $mail->isSMTP();
                    $mail->Host = getenv('SMTP_HOST');
                    $mail->SMTPAuth = true;
                    $mail->Username = getenv('SMTP_USER');
                    $mail->Password = getenv('SMTP_PASS');
                    $mail->SMTPSecure = getenv('SMTP_SECURE') === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = getenv('SMTP_PORT') ?: 587;

                    $mail->isHTML(true);
                    $mail->setFrom(getenv('SMTP_USER'), 'ScentAura');
                    $mail->addAddress($email);
                    $mail->Subject = "Reset Your ScentAura Password";

                    // Premium Styled HTML Email Body
                    $mail->Body = "
                        <html>
                        <head>
                            <style>
                                body { font-family: 'Playfair Display', Georgia, serif; background-color: #f7f7f7; padding: 20px; }
                                .container { max-width: 600px; margin: 0 auto; background: #000000; color: #ffffff; padding: 40px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
                                .logo { text-align: center; margin-bottom: 30px; }
                                .logo img { width: 100px; }
                                h2 { text-align: center; font-size: 26px; margin-bottom: 20px; font-weight: bold; letter-spacing: 1px; }
                                p { line-height: 1.8; font-size: 16px; margin-bottom: 30px; text-align: center; color: #cccccc; }
                                .btn-container { text-align: center; margin-bottom: 30px; }
                                .btn { display: inline-block; padding: 15px 35px; background: linear-gradient(135deg, #ffffff 0%, #cccccc 100%); color: #000000 !important; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 16px; transition: transform 0.3s; }
                                .footer { text-align: center; font-size: 12px; color: #666666; margin-top: 40px; border-top: 1px solid #222; padding-top: 20px; }
                            </style>
                        </head>
                        <body>
                            <div class='container'>
                                <div class='logo'>
                                    <h2>ScentAura</h2>
                                </div>
                                <h2>Password Reset Request</h2>
                                <p>We received a request to reset the password associated with this email address. Please click the button below to secure a new password. This reset link is valid for 1 hour.</p>
                                <div class='btn-container'>
                                    <a href='{$resetLink}' class='btn'>Reset Password</a>
                                </div>
                                <p style='font-size: 14px; color: #888888;'>If you did not request this reset, you can safely ignore this email.</p>
                                <div class='footer'>
                                    &copy; " . date("Y") . " ScentAura. All rights reserved.
                                </div>
                            </div>
                        </body>
                        </html>
                    ";

                    $mail->send();
                    $message = "A secure reset link has been dispatched to your email.";
                    $messageType = "success";
                } catch (Exception $e) {
                    $message = "Failed to send reset email. Mailer Error: {$mail->ErrorInfo}";
                    $messageType = "error";
                }
            } else {
                $message = "Failed to write database session. Please try again.";
                $messageType = "error";
            }
            $stmt->close();
        } else {
            $message = "No account is registered under this email address.";
            $messageType = "error";
        }
    } else {
        $message = "Please enter a valid email address.";
        $messageType = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ScentAura - Forgot Password</title>
    <link rel="icon" type="image/png" href="components/logo/logo.png">
    <link rel="stylesheet" href="components/css/login.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap">
    <style>
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 15px;
            text-align: center;
        }
        .alert-success {
            background-color: rgba(46, 204, 113, 0.15);
            color: #2ecc71;
            border: 1px solid #2ecc71;
        }
        .alert-error {
            background-color: rgba(231, 76, 60, 0.15);
            color: #e74c3c;
            border: 1px solid #e74c3c;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="brand-column">
            <img src="components/logo/logo.png" alt="ScentAura">
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
        
        <div class="auth-form active">
            <div class="active">
                <h2 class="form-heading">Forgot Password</h2>
                
                <?php if (!empty($message)): ?>
                    <div class="alert alert-<?php echo $messageType; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" placeholder="Enter your email address" name="email" required>
                    </div>
                    
                    <button type="submit" class="submit-btn" name="request_reset">Send Reset Link</button>
                    
                    <div class="alternate-auth">
                        <p>Remembered your password? <a href="login.php">Sign In</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
