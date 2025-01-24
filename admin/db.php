<?php
$host = 'localhost';  // Database host
$dbname = 'hardcore';  // Your database name
$username = 'root';  // Your database username
$password = '';  // Your database password

// Create a new PDO instance to connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}
?>
