<?php
// reset_password.php - Reset Password
session_start();
require 'db.php';
 
if (!isset($_GET['token'])) {
    echo "<script>alert('Invalid token.'); window.location.href = 'login.php';</script>";
    exit;
}
 
$token = $_GET['token'];
 
$stmt = $pdo->prepare("SELECT * FROM verification_tokens WHERE token = ? AND type = 'reset' AND expires_at > NOW()");
$stmt->execute([$token]);
$token_data = $stmt->fetch();
 
if (!$token_data) {
    echo "<script>alert('Invalid or expired token.'); window.location.href = 'login.php';</script>";
    exit;
}
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
 
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$password, $token_data['user_id']]);
 
    $stmt = $pdo->prepare("DELETE FROM verification_tokens WHERE token = ?");
    $stmt->execute([$token]);
 
    echo "<script>alert('Password reset successful! Please login.'); window.location.href = 'login.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body { font-family: Arial, sans-serif; background: linear-gradient(135deg, #0070f3, #ffffff); color: #333; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .form-container { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 300px; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
        button { width: 100%; background: #0070f3; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; transition: background 0.3s; }
        button:hover { background: #005bb5; }
        @media (max-width: 768px) { .form-container { width: 90%; padding: 20px; } }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Reset Password</h2>
        <form method="POST">
            <input type="password" name="password" placeholder="New Password" required>
            <button type="submit">Reset</button>
        </form>
    </div>
</body>
</html>
