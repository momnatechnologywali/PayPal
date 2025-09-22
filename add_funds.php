<?php
// add_funds.php - Add Funds (Debugged and Improved, Fixed Foreign Key Issue)
session_start();
require 'db.php';
 
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to add funds.'); window.location.href = 'login.php';</script>";
    exit;
}
 
$user_id = $_SESSION['user_id'];
 
// Initialize error message
$error = '';
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
 
    // Validate amount
    if ($amount === false || $amount <= 0) {
        $error = 'Please enter a valid positive amount.';
    } else {
        try {
            // Begin transaction for database consistency
            $pdo->beginTransaction();
 
            // Update user balance
            $stmt = $pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
            $stmt->execute([$amount, $user_id]);
 
            // Check if the update was successful
            if ($stmt->rowCount() === 0) {
                throw new Exception('Failed to update balance. User not found.');
            }
 
            // Commit transaction (no transaction recording for system deposits)
            $pdo->commit();
 
            // Log success for debugging
            file_put_contents('debug.log', "Funds added: User ID $user_id, Amount $amount, Time: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
 
            echo "<script>alert('Funds added successfully!'); window.location.href = 'dashboard.php';</script>";
            exit;
        } catch (Exception $e) {
            // Rollback transaction on error
            $pdo->rollBack();
            $error = 'Error adding funds: ' . $e->getMessage();
            // Log error for debugging
            file_put_contents('debug.log', "Error adding funds: User ID $user_id, Error: " . $e->getMessage() . ", Time: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Funds</title>
    <style>
        body { font-family: Arial, sans-serif; background: linear-gradient(135deg, #0070f3, #ffffff); color: #333; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .form-container { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 300px; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
        button { width: 100%; background: #0070f3; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; transition: background 0.3s; }
        button:hover { background: #005bb5; }
        a { text-align: center; display: block; margin-top: 10px; color: #0070f3; }
        .error { color: red; text-align: center; margin: 10px 0; }
        @media (max-width: 768px) { .form-container { width: 90%; padding: 20px; } }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add Funds (Dummy)</h2>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="number" name="amount" placeholder="Amount" step="0.01" min="0.01" required>
            <button type="submit">Add</button>
        </form>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
