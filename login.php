<?php
// login.php - User Login (2FA removed)
session_start();
require 'db.php';
 
if (isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'dashboard.php';</script>";
    exit;
}
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
 
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
 
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        echo "<script>window.location.href = 'dashboard.php';</script>";
    } else {
        echo "<script>alert('Invalid email or password.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <style>
        body { font-family: Arial, sans-serif; background: linear-gradient(135deg, #0070f3, #ffffff); color: #333; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .form-container { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 300px; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
        button { width: 100%; background: #0070f3; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; transition: background 0.3s; }
        button:hover { background: #005bb5; }
        a { text-align: center; display: block; margin-top: 10px; color: #0070f3; }
        @media (max-width: 768px) { .form-container { width: 90%; padding: 20px; } }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Log In</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Log In</button>
        </form>
        <a href="signup.php">Don't have an account? Sign Up</a>
        <a href="forgot_password.php">Forgot Password?</a>
    </div>
</body>
</html> 
