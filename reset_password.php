<?php
session_start();
include 'db.php';

$message = "";
$messageType = ""; // "success" or "error"
$showForm = false;

// Read query parameters
$email = isset($_GET['email']) ? trim($_GET['email']) : '';
$token = isset($_GET['token']) ? trim($_GET['token']) : '';

if (empty($email) || empty($token)) {
    $message = "Invalid or missing password reset parameters.";
    $messageType = "error";
} else {
    // Validate email and token in the database
    $emailEscaped = $conn->real_escape_string($email);
    $tokenEscaped = $conn->real_escape_string($token);
    
    // Check if token exists and is not expired
    $currentDateTime = date('Y-m-d H:i:s');
    $checkToken = $conn->query("SELECT * FROM password_resets WHERE email='$emailEscaped' AND token='$tokenEscaped' AND expires_at > '$currentDateTime'");
    
    if ($checkToken->num_rows == 1) {
        $showForm = true;
    } else {
        $message = "This password reset link is invalid or has expired.";
        $messageType = "error";
    }
}

// Handle password update form submit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset_password']) && $showForm) {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if (strlen($newPassword) < 6) {
        $message = "Password must be at least 6 characters long.";
        $messageType = "error";
    } elseif ($newPassword !== $confirmPassword) {
        $message = "Passwords do not match.";
        $messageType = "error";
    } else {
        // Hash password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // Begin Transaction
        $conn->begin_transaction();
        
        try {
            // Update user password
            $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            $updateStmt->bind_param("ss", $hashedPassword, $email);
            $updateStmt->execute();
            $updateStmt->close();
            
            // Delete the consumed token
            $deleteStmt = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
            $deleteStmt->bind_param("s", $email);
            $deleteStmt->execute();
            $deleteStmt->close();
            
            $conn->commit();
            
            $message = "Your password has been successfully reset.";
            $messageType = "success";
            $showForm = false; // Hide form on success
        } catch (Exception $e) {
            $conn->rollback();
            $message = "An error occurred while updating your password. Please try again.";
            $messageType = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ScentAura - Reset Password</title>
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
                <h2 class="form-heading">Reset Password</h2>
                
                <?php if (!empty($message)): ?>
                    <div class="alert alert-<?php echo $messageType; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <?php if ($showForm): ?>
                    <form action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>" method="post">
                        <div class="input-group">
                            <i class="fas fa-lock"></i>
                            <input type="password" placeholder="Create new password" name="new_password" required minlength="6">
                        </div>
                        
                        <div class="input-group">
                            <i class="fas fa-lock"></i>
                            <input type="password" placeholder="Confirm new password" name="confirm_password" required minlength="6">
                        </div>
                        
                        <button type="submit" class="submit-btn" name="reset_password">Reset Password</button>
                    </form>
                <?php else: ?>
                    <div class="alternate-auth">
                        <p><a href="login.php">Proceed to Sign In</a></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
