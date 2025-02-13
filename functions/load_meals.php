<?php
include 'conn.php';
session_start();

header('Content-Type: application/json');

$day = isset($_GET['day']) ? $_GET['day'] : 'Monday';
$user_id = $_SESSION['user_id']; // Retrieve user_id from session

if (!$user_id) {
    echo json_encode(['error' => 'User ID is required']);
    exit();
}

// Get the user's status from tbl_status
$query = "SELECT protein, carbs, fats FROM tbl_status WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 1";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$status = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$status) {
    echo json_encode(['error' => 'No status found for the user']);
    exit();
}

// Get the meals for the specified day
$query = "SELECT * FROM tbl_meals WHERE day_of_week = :day";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':day', $day);
$stmt->execute();
$meals = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Select one item per meal type
$mealTypes = ['Breakfast', 'Snack (AM)', 'Lunch', 'Snack (PM)', 'Dinner', 'Optional Evening Snack'];
$selectedMeals = [];

foreach ($mealTypes as $type) {
    foreach ($meals as $meal) {
        if ($meal['meal_type'] === $type) {
            $selectedMeals[] = $meal;
            break; // Select only the first item of this type
        }
    }
}

// Calculate the total carbs, protein, and fats
$total_carbs = 0;
$total_protein = 0;
$total_fats = 0;

foreach ($selectedMeals as $meal) {
    $total_carbs += isset($meal['carbohydrate']) ? $meal['carbohydrate'] : 0;
    $total_protein += isset($meal['protein']) ? $meal['protein'] : 0;
    $total_fats += isset($meal['fats']) ? $meal['fats'] : 0;
}

$response = [
    'status' => $status,
    'meal_plan' => $selectedMeals, // Ensure meal_plan key is always included
    'total_carbohydrates' => $total_carbs,
    'total_proteins' => $total_protein,
    'total_fats' => $total_fats
];

echo json_encode($response);
?>
