<?php
require_once('db.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized access']);
    exit();
}

// Handle POST request for updating
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_workout'])) {
    $workout_id = $_POST['workout_id'];
    $workout_title = $_POST['workout_title'];
    $day_of_week = $_POST['day_of_week'];
    $fitness_level = $_POST['fitness_level'];
    $sets = $_POST['sets'];
    $reps = $_POST['reps'];

    $stmt = $pdo->prepare("UPDATE tbl_workouts SET 
        workout_title = :title,
        day_of_week = :day,
        fitness_level = :level,
        sets = :sets,
        reps = :reps
        WHERE id = :id");
    
    $stmt->bindParam(':id', $workout_id);
    $stmt->bindParam(':title', $workout_title);
    $stmt->bindParam(':day', $day_of_week);
    $stmt->bindParam(':level', $fitness_level);
    $stmt->bindParam(':sets', $sets);
    $stmt->bindParam(':reps', $reps);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Workout updated successfully!";
    } else {
        $_SESSION['error_message'] = "Error updating workout.";
    }
    
    header('Location: ../dashboard.php?section=workouts');
    exit();
}

// Handle GET request for fetching workout data
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    try {
        header('Content-Type: application/json');
        $workout_id = $_GET['id'];
        $stmt = $pdo->prepare("SELECT * FROM tbl_workouts WHERE id = :id");
        $stmt->bindParam(':id', $workout_id);
        $stmt->execute();
        $workout = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($workout) {
            echo json_encode($workout);
        } else {
            echo json_encode(['error' => 'Workout not found']);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => 'Database error']);
    }
    exit();
}
?>
