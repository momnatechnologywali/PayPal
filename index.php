<?php
// index.php - Homepage
session_start();
if (isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'dashboard.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayPal Clone - Home</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: linear-gradient(135deg, #0070f3, #ffffff); color: #333; }
        header { background: #0070f3; color: white; padding: 20px; text-align: center; }
        .container { max-width: 1200px; margin: auto; padding: 20px; }
        .features { display: flex; flex-wrap: wrap; justify-content: space-around; }
        .feature { background: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); padding: 20px; margin: 10px; width: 300px; text-align: center; transition: transform 0.3s; }
        .feature:hover { transform: translateY(-10px); }
        .btn { background: #0070f3; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; margin: 10px; transition: background 0.3s; }
        .btn:hover { background: #005bb5; }
        footer { text-align: center; padding: 10px; background: #f1f1f1; }
        @media (max-width: 768px) { .features { flex-direction: column; align-items: center; } .feature { width: 90%; } }
    </style>
</head>
<body>
    <header>
        <h1>Welcome to PayPal Clone</h1>
        <p>Securely send, receive, and manage your money online.</p>
    </header>
    <div class="container">
        <section class="features">
            <div class="feature">
                <h2>Send Money</h2>
                <p>Easily transfer funds to friends and family using email or username.</p>
            </div>
            <div class="feature">
                <h2>Receive Money</h2>
                <p>Get payments instantly and track them in your dashboard.</p>
            </div>
            <div class="feature">
                <h2>Manage Wallet</h2>
                <p>Store funds securely and view transaction history.</p>
            </div>
        </section>
        <div style="text-align: center;">
            <a href="signup.php" class="btn">Sign Up</a>
            <a href="login.php" class="btn">Log In</a>
        </div>
    </div>
    <footer>
        <p>&copy; 2025 PayPal Clone. All rights reserved.</p>
    </footer>
</body>
</html>
