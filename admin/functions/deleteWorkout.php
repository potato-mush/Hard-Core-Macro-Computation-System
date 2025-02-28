<?php
require_once('db.php');
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['workout_id'])) {
    try {
        $workout_id = $_POST['workout_id'];
        
        $stmt = $pdo->prepare("DELETE FROM tbl_workouts WHERE workout_id = :id");
        $stmt->bindParam(':id', $workout_id);
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete workout']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
    }
    exit();
}
?>
