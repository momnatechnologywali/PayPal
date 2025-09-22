<?php
// db.php - Database connection file
 
$host = 'localhost';  // Assuming localhost, change if different
$dbname = 'dbfualaexejid5';
$user = 'uws1gwyttyg2r';
$pass = 'k1tdlhq4qpsf';
 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES utf8mb4");
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
