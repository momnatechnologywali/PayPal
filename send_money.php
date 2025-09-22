<?php
// send_money.php - Send Money
session_start();
require 'db.php';
 
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
 
$user_id = $_SESSION['user_id'];
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $recipient = $_POST['recipient'];
    $amount = (float)$_POST['amount'];
    $description = $_POST['description'];
 
    // Find recipient by email or username
    $stmt = $pdo->prepare("SELECT id, email FROM users WHERE email = ? OR username = ?");
    $stmt->execute([$recipient, $recipient]);
    $rec_user = $stmt->fetch();
 
    if ($rec_user && $rec_user['id'] != $user_id) {
        // Check balance
        $stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $balance = $stmt->fetchColumn();
 
        if ($balance >= $amount) {
            // Transaction
            $pdo->beginTransaction();
            try {
                $stmt = $pdo->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
                $stmt->execute([$amount, $user_id]);
 
                $stmt = $pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
                $stmt->execute([$amount, $rec_user['id']]);
 
                $stmt = $pdo->prepare("INSERT INTO transactions (sender_id, receiver_id, amount, description) VALUES (?, ?, ?, ?)");
                $stmt->execute([$user_id, $rec_user['id'], $amount, $description]);
 
                $pdo->commit();
 
                // Send email notification
                $subject = "You Received Money";
                $message = "You received $$amount from user ID $user_id. Description: $description";
                mail($rec_user['email'], $subject, $message);
 
                echo "<script>alert('Money sent successfully!'); window.location.href = 'dashboard.php';</script>";
            } catch (Exception $e) {
                $pdo->rollBack();
                echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');</script>";
            }
        } else {
            echo "<script>alert('Insufficient balance.');</script>";
        }
    } else {
        echo "<script>alert('Recipient not found or invalid.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Money</title>
    <style>
        body { font-family: Arial, sans-serif; background: linear-gradient(135deg, #0070f3, #ffffff); color: #333; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .form-container { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 300px; }
        input, textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
        button { width: 100%; background: #0070f3; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; transition: background 0.3s; }
        button:hover { background: #005bb5; }
        a { text-align: center; display: block; margin-top: 10px; color: #0070f3; }
        @media (max-width: 768px) { .form-container { width: 90%; padding: 20px; } }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Send Money</h2>
        <form method="POST">
            <input type="text" name="recipient" placeholder="Recipient Email or Username" required>
            <input type="number" name="amount" placeholder="Amount" step="0.01" required>
            <textarea name="description" placeholder="Description (optional)"></textarea>
            <button type="submit">Send</button>
        </form>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
