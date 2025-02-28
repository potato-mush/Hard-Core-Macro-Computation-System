<?php
ob_start();
$host = 'localhost';
$dbname = 'hardcore';
$username = 'root';
$password = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    ob_clean();
    header('Content-Type: application/json');
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed']));
}
?>
