<?php
include('db.php');

// Only start session if it hasn't been started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized access']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_meal'])) {
    $meal_id = $_POST['meal_id'];
    $food_title = $_POST['food_title'];
    $meal_type = $_POST['meal_type'];
    $ingredients = $_POST['ingredients'];
    $protein = $_POST['protein'];
    $carbohydrate = $_POST['carbohydrate'];
    $fats = $_POST['fats'];
    $day_of_week = $_POST['day_of_week'];

    $stmt = $pdo->prepare("UPDATE tbl_meals SET 
        food_title = :title,
        meal_type = :type,
        ingredients = :ingredients,
        protein = :protein,
        carbohydrate = :carbs,
        fats = :fats,
        day_of_week = :day
        WHERE id = :id");
    
    $stmt->bindParam(':id', $meal_id);
    $stmt->bindParam(':title', $food_title);
    $stmt->bindParam(':type', $meal_type);
    $stmt->bindParam(':ingredients', $ingredients);
    $stmt->bindParam(':protein', $protein);
    $stmt->bindParam(':carbs', $carbohydrate);
    $stmt->bindParam(':fats', $fats);
    $stmt->bindParam(':day', $day_of_week);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Meal updated successfully!";
    } else {
        $_SESSION['error_message'] = "Error updating meal.";
    }
    
    header('Location: ../dashboard.php?section=meals');
    exit();
}

// Get meal data for editing
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    header('Content-Type: application/json');
    $meal_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM tbl_meals WHERE id = :id");
    $stmt->bindParam(':id', $meal_id);
    $stmt->execute();
    $meal = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($meal);
    exit();
}
?>

<!-- Edit Meal Modal -->
<div class="modal fade" id="editMealModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Meal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="functions/editMeal.php" method="POST">
                    <input type="hidden" name="meal_id" id="edit_meal_id">
                    <div class="mb-3">
                        <label for="edit_food_title" class="form-label">Food Title</label>
                        <input type="text" class="form-control" name="food_title" id="edit_food_title" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_meal_type" class="form-label">Meal Type</label>
                        <select class="form-control" name="meal_type" id="edit_meal_type" required>
                            <option value="Breakfast">Breakfast</option>
                            <option value="Lunch">Lunch</option>
                            <option value="Dinner">Dinner</option>
                            <option value="Snack">Snack</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_day_of_week" class="form-label">Day of Week</label>
                        <select class="form-control" name="day_of_week" id="edit_day_of_week" required>
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
                        <label for="edit_ingredients" class="form-label">Ingredients</label>
                        <textarea class="form-control" name="ingredients" id="edit_ingredients" rows="3" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_protein" class="form-label">Protein (g)</label>
                                <input type="number" class="form-control" name="protein" id="edit_protein" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_carbohydrate" class="form-label">Carbs (g)</label>
                                <input type="number" class="form-control" name="carbohydrate" id="edit_carbohydrate" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_fats" class="form-label">Fats (g)</label>
                                <input type="number" class="form-control" name="fats" id="edit_fats" required>
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="edit_meal" class="btn btn-primary">Update Meal</button>
                </form>
            </div>
        </div>
    </div>
</div>
