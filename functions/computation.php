<?php
function calculateMacros($gender, $age, $fitness_goal, $fitness_level, $weight, $height, $muscle_group) {
    // Calculate BMR
    if ($gender === 'male') {
        $bmr = 88.362 + (13.397 * $weight) + (4.799 * $height) - (5.677 * $age);
    } else {
        $bmr = 447.593 + (9.247 * $weight) + (3.098 * $height) - (4.330 * $age);
    }

    // Calculate TDEE based on fitness level
    switch ($fitness_level) {
        case 'novice':
            $tdee = $bmr * 1.2;
            break;
        case 'beginner':
            $tdee = $bmr * 1.375;
            break;
        case 'intermediate':
            $tdee = $bmr * 1.55;
            break;
        case 'advanced':
            $tdee = $bmr * 1.725;
            break;
        default:
            $tdee = $bmr;
    }

    // Adjust TDEE based on fitness goal
    switch ($fitness_goal) {
        case 'lose-weight':
            $target_calories = $tdee * 0.8;
            break;
        case 'gain-strength':
            $target_calories = $tdee * 1.1;
            break;
        case 'gain-muscle':
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $fitness_goal = $_POST['fitness_goal'];
    $fitness_level = $_POST['fitness_level'];
    $weight = $_POST['weight'];
    $height = $_POST['height'];
    $muscle_group = $_POST['muscle_group'];

    $macros = calculateMacros($gender, $age, $fitness_goal, $fitness_level, $weight, $height, $muscle_group);
    echo json_encode($macros);
}
?>
