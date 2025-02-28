<?php
include('db.php');

// Only start session if it hasn't been started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_meal'])) {
    // Sanitize inputs
    $food_title = filter_input(INPUT_POST, 'food_title', FILTER_SANITIZE_STRING);
    $meal_type = filter_input(INPUT_POST, 'meal_type', FILTER_SANITIZE_STRING);
    $ingredients = filter_input(INPUT_POST, 'ingredients', FILTER_SANITIZE_STRING);
    $protein = filter_input(INPUT_POST, 'protein', FILTER_SANITIZE_NUMBER_FLOAT);
    $carbohydrate = filter_input(INPUT_POST, 'carbohydrate', FILTER_SANITIZE_NUMBER_FLOAT);
    $fats = filter_input(INPUT_POST, 'fats', FILTER_SANITIZE_NUMBER_FLOAT);
    $day_of_week = filter_input(INPUT_POST, 'day_of_week', FILTER_SANITIZE_STRING);

    $stmt = $pdo->prepare("INSERT INTO tbl_meals (food_title, meal_type, ingredients, protein, carbohydrate, fats, day_of_week) 
                          VALUES (:title, :type, :ingredients, :protein, :carbs, :fats, :day)");
    
    $stmt->bindParam(':title', $food_title);
    $stmt->bindParam(':type', $meal_type);
    $stmt->bindParam(':ingredients', $ingredients);
    $stmt->bindParam(':protein', $protein);
    $stmt->bindParam(':carbs', $carbohydrate);
    $stmt->bindParam(':fats', $fats);
    $stmt->bindParam(':day', $day_of_week);

    if ($stmt->execute()) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}
?>

<!-- Add Meal Modal -->
<div class="modal fade" id="addMealModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Meal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="functions/addMeal.php" method="POST">
                    <div class="mb-3">
                        <label for="food_title" class="form-label">Food Title</label>
                        <input type="text" class="form-control" name="food_title" required>
                    </div>
                    <div class="mb-3">
                        <label for="meal_type" class="form-label">Meal Type</label>
                        <select class="form-control" name="meal_type" required>
                            <option value="Breakfast">Breakfast</option>
                            <option value="Lunch">Lunch</option>
                            <option value="Dinner">Dinner</option>
                            <option value="Snack">Snack</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="day_of_week" class="form-label">Day of Week</label>
                        <select class="form-control" name="day_of_week" required>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="ingredients" class="form-label">Ingredients</label>
                        <textarea class="form-control" name="ingredients" rows="3" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="protein" class="form-label">Protein (g)</label>
                                <input type="number" class="form-control" name="protein" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="carbohydrate" class="form-label">Carbs (g)</label>
                                <input type="number" class="form-control" name="carbohydrate" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="fats" class="form-label">Fats (g)</label>
                                <input type="number" class="form-control" name="fats" required>
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="add_meal" class="btn btn-primary">Save Meal</button>
                </form>
            </div>
        </div>
    </div>
</div>
