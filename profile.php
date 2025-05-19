<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit();
}

$username = $_SESSION['username'];
$message = "";

// Get user data
$sql = "SELECT * FROM users WHERE full_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo "User not found.";
    exit();
}
$user = $result->fetch_assoc();

// Handle update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE users SET full_name = ?, email = ?, password = ? WHERE id = ?");
        $update->bind_param("sssi", $full_name, $email, $hashed, $user['id']);
    } else {
        $update = $conn->prepare("UPDATE users SET full_name = ?, email = ? WHERE id = ?");
        $update->bind_param("ssi", $full_name, $email, $user['id']);
    }

    if ($update->execute()) {
        $_SESSION['username'] = $full_name;
        $message = "Profile updated successfully.";
        // Refresh user data
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $full_name);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
    } else {
        $message = "Failed to update profile.";
    }
}

$edit_mode = isset($_GET['edit']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile - ScentAura</title>
    <link rel="stylesheet" href="components/css/profile.css">
</head>
<body>
    <div class="profile-container">
        <div class="profile-card">
            <?php if ($edit_mode): ?>
                <h2>Edit Profile</h2>
                <?php if ($message): ?>
                    <p class="status-message"><?= htmlspecialchars($message) ?></p>
                <?php endif; ?>
                <form method="POST" class="edit-form">
                    <label>Full Name</label>
                    <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required>

                    <label>Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

                    <label>New Password <small>(leave blank to keep current)</small></label>
                    <input type="password" name="password">

                    <button type="submit" class="btn">Update</button>
                    <a href="profile.php" class="btn logout">Cancel</a>
                </form>
            <?php else: ?>
                <div class="profile-header">
                    <h2><?= htmlspecialchars($user['full_name']) ?></h2>
                    <p class="email"><?= htmlspecialchars($user['email']) ?></p>
                </div>
                <div class="profile-body">
                    <p><strong>Role:</strong> <?= htmlspecialchars($user['role']) ?></p>
                    <p><strong>Member Since:</strong> <?= date("F d, Y", strtotime($user['created_at'])) ?></p>
                </div>
                <div class="profile-footer">
                    <a href="profile.php?edit=true" class="btn">Edit Profile</a>
                    <a href="logout.php" class="btn logout">Logout</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
