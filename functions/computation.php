<?php
session_start();
include('conn.php'); // Include the database connection

function calculateMacros($gender, $age, $fitness_goal, $fitness_level, $weight, $height, $muscle_group) {
    // Calculate BMR
    if ($gender === 'male') {
        $bmr = 88.362 + (13.397 * $weight) + (4.799 * $height) - (5.677 * $age);
    } else {
        $bmr = 447.593 + (9.247 * $weight) + (3.098 * $height) - (4.330 * $age);
    }

    // Calculate TDEE based on fitness level
    switch ($fitness_level) {
        case 'Novice':
            $tdee = $bmr * 1.2;
            break;
        case 'Beginner':
            $tdee = $bmr * 1.375;
            break;
        case 'Intermediate':
            $tdee = $bmr * 1.55;
            break;
        case 'Advanced':
            $tdee = $bmr * 1.725;
            break;
        default:
            $tdee = $bmr;
    }

    // Adjust TDEE based on fitness goal
    switch ($fitness_goal) {
        case 'Lose Weight':
            $target_calories = $tdee * 0.8;
            break;
        case 'Gain Strength':
            $target_calories = $tdee * 1.1;
            break;
        case 'Gain Muscle':
            $target_calories = $tdee * 1.2;
            break;
        default:
            $target_calories = $tdee;
    }

    // Calculate macronutrient distribution
    $protein_grams = $weight * 2.2; // Example: 2.2g per kg for muscle gain
    $protein_calories = $protein_grams * 4;

    $fat_calories = $target_calories * 0.25; // Example: 25% of total calories for fats
    $fat_grams = $fat_calories / 9;

    $carb_calories = $target_calories - $protein_calories - $fat_calories;
    $carb_grams = $carb_calories / 4;

    return [
        'protein' => round($protein_grams),
        'carbs' => round($carb_grams),
        'fats' => round($fat_grams)
    ];
}

function saveComputation($user_id, $macros, $pdo, $gender, $age, $fitness_goal, $fitness_level, $weight, $height, $muscle_group) {
    // Check if the user ID exists in tbl_status
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_status WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $exists = $stmt->fetchColumn();

    if ($exists) {
        // Update the existing record
        $stmt = $pdo->prepare("UPDATE tbl_status SET protein = :protein, carbs = :carbs, fats = :fats, gender = :gender, age = :age, fitness_goal = :fitness_goal, fitness_level = :fitness_level, weight = :weight, height = :height, muscle_group = :muscle_group WHERE user_id = :user_id");
    } else {
        // Insert a new record
        $stmt = $pdo->prepare("INSERT INTO tbl_status (user_id, protein, carbs, fats, gender, age, fitness_goal, fitness_level, weight, height, muscle_group) VALUES (:user_id, :protein, :carbs, :fats, :gender, :age, :fitness_goal, :fitness_level, :weight, :height, :muscle_group)");
    }

    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':protein', $macros['protein']);
    $stmt->bindParam(':carbs', $macros['carbs']);
    $stmt->bindParam(':fats', $macros['fats']);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':age', $age);
    $stmt->bindParam(':fitness_goal', $fitness_goal);
    $stmt->bindParam(':fitness_level', $fitness_level);
    $stmt->bindParam(':weight', $weight);
    $stmt->bindParam(':height', $height);
    $stmt->bindParam(':muscle_group', $muscle_group);
    $stmt->execute();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $fitness_goal = $_POST['fitness_goal'];
    $fitness_level = $_POST['fitness_level'];
    $weight = $_POST['weight'];
    $height = $_POST['height'];
    $muscle_group = $_POST['muscle_group'];
    $user_id = $_SESSION['user_id']; // Retrieve user_id from session

    if (!$user_id) {
        echo json_encode(['error' => 'User ID is required']);
        exit();
    }

    $macros = calculateMacros($gender, $age, $fitness_goal, $fitness_level, $weight, $height, $muscle_group);
    saveComputation($user_id, $macros, $pdo, $gender, $age, $fitness_goal, $fitness_level, $weight, $height, $muscle_group);
    echo json_encode($macros);
}
?>
