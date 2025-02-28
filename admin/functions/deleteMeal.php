<?php
include('db.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('HTTP/1.1 401 Unauthorized');
    echo 'error';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['meal_id'])) {
    try {
        $meal_id = filter_var($_POST['meal_id'], FILTER_SANITIZE_NUMBER_INT);
            
        $stmt = $pdo->prepare("DELETE FROM tbl_meals WHERE id = :id");
        $stmt->bindParam(':id', $meal_id);
        
        if ($stmt->execute()) {
            echo 'success';
        } else {
            echo 'error';
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        echo 'error';
    }
} else {
    header('HTTP/1.1 400 Bad Request');
    echo 'error';
}
?>
