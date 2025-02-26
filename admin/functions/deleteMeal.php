<?php
include('db.php');
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    die('Unauthorized access');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['meal_id'])) {
    $meal_id = $_POST['meal_id'];
    
    $stmt = $pdo->prepare("DELETE FROM tbl_meals WHERE id = :id");
    $stmt->bindParam(':id', $meal_id);
    
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
