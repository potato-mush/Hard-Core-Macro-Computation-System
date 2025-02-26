<?php
include 'conn.php';
session_start();

header('Content-Type: application/json');

$user_id = $_SESSION['user_id']; // Retrieve user_id from session
$day = isset($_GET['day']) ? $_GET['day'] : 'Monday'; // Get the selected day

// If it's Sunday, return rest day message
if ($day === 'Sunday') {
    echo json_encode([
        'status' => ['fitness_level' => 'Rest Day'],
        'workouts' => [
            ['workout_title' => 'Rest Day - Take it easy!', 
             'sets' => 0, 
             'reps' => 0,
             'description' => 'Today is your rest day. Focus on recovery, light stretching, or gentle walking if desired.']
        ]
    ]);
    exit();
}

if (!$user_id) {
    echo json_encode(['error' => 'User ID is required']);
    exit();
}

// Get the user's fitness level from tbl_status
$query = "SELECT fitness_level FROM tbl_status WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 1";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$status = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$status) {
    echo json_encode(['error' => 'No status found for the user']);
    exit();
}

// Get workouts randomly based on fitness level and day
$query = "SELECT * FROM tbl_workouts WHERE fitness_level = :fitness_level AND day_of_week = :day ORDER BY RAND() LIMIT 6";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':fitness_level', $status['fitness_level']);
$stmt->bindParam(':day', $day);
$stmt->execute();
$workouts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$response = [
    'status' => $status,
    'workouts' => $workouts
];

echo json_encode($response);
?>
