<?php
// dashboard.php - User Dashboard
session_start();
require 'db.php';
 
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
 
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
 
$stmt = $pdo->prepare("SELECT t.*, u.username as receiver_name FROM transactions t LEFT JOIN users u ON t.receiver_id = u.id WHERE t.sender_id = ? UNION SELECT t.*, u.username as receiver_name FROM transactions t LEFT JOIN users u ON t.sender_id = u.id WHERE t.receiver_id = ? ORDER BY created_at DESC LIMIT 10");
$stmt->execute([$user_id, $user_id]);
$transactions = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; color: #333; margin: 0; padding: 0; }
        header { background: #0070f3; color: white; padding: 20px; text-align: center; }
        .container { max-width: 1200px; margin: auto; padding: 20px; }
        .balance { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); text-align: center; margin-bottom: 20px; }
        .actions { display: flex; justify-content: space-around; flex-wrap: wrap; }
        .action { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 200px; text-align: center; margin: 10px; transition: transform 0.3s; }
        .action:hover { transform: translateY(-10px); }
        .transactions { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #f1f1f1; }
        .btn { background: #0070f3; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; transition: background 0.3s; }
        .btn:hover { background: #005bb5; }
        @media (max-width: 768px) { .actions { flex-direction: column; align-items: center; } .action { width: 90%; } table { font-size: 12px; } }
    </style>
</head>
<body>
    <header>
        <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?></h1>
    </header>
    <div class="container">
        <div class="balance">
            <h2>Your Balance: $<?php echo number_format($user['balance'], 2); ?></h2>
        </div>
        <div class="actions">
            <div class="action">
                <a href="send_money.php" class="btn">Send Money</a>
            </div>
            <div class="action">
                <a href="add_funds.php" class="btn">Add Funds</a>
            </div>
            <div class="action">
                <a href="logout.php" class="btn">Logout</a>
            </div>
        </div>
        <div class="transactions">
            <h2>Recent Transactions</h2>
            <table>
                <tr><th>Date</th><th>To/From</th><th>Amount</th><th>Status</th></tr>
                <?php foreach ($transactions as $tx): ?>
                    <tr>
                        <td><?php echo $tx['created_at']; ?></td>
                        <td><?php echo ($tx['sender_id'] == $user_id ? 'To: ' : 'From: ') . htmlspecialchars($tx['receiver_name'] ?? 'Unknown'); ?></td>
                        <td>$<?php echo number_format($tx['amount'], 2); ?></td>
                        <td><?php echo $tx['status']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</body>
</html>
