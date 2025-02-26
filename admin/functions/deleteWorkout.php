<?php
include('db.php');
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    die('Unauthorized access');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['workout_id'])) {
    $workout_id = $_POST['workout_id'];
    
    $stmt = $pdo->prepare("DELETE FROM tbl_workouts WHERE workout_id = :id");
    $stmt->bindParam(':id', $workout_id);
    
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
