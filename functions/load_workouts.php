<?php
include 'conn.php';
session_start();

header('Content-Type: application/json');

$user_id = $_SESSION['user_id']; // Retrieve user_id from session
$day = isset($_GET['day']) ? $_GET['day'] : 'Monday'; // Get the selected day

if (!$user_id) {
    echo json_encode(['error' => 'User ID is required']);
    exit();
}

// Get the user's status from tbl_status
$query = "SELECT fitness_level, fitness_goal, muscle_group FROM tbl_status WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 1";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$status = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$status) {
    echo json_encode(['error' => 'No status found for the user']);
    exit();
}

// Get up to 6 workouts for the user's fitness level, goal, muscle group, and selected day
$query = "SELECT * FROM tbl_workouts WHERE fitness_level = :fitness_level AND fitness_goal = :fitness_goal AND muscle_group = :muscle_group AND day_of_week = :day LIMIT 6";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':fitness_level', $status['fitness_level']);
$stmt->bindParam(':fitness_goal', $status['fitness_goal']);
$stmt->bindParam(':muscle_group', $status['muscle_group']);
$stmt->bindParam(':day', $day);
$stmt->execute();
$workouts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$response = [
    'status' => $status,
    'workouts' => $workouts
];

echo json_encode($response);
?>
