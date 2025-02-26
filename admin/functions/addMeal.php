<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_meal'])) {
    $food_title = $_POST['food_title'];
    $meal_type = $_POST['meal_type'];
    $ingredients = $_POST['ingredients'];
    $protein = $_POST['protein'];
    $carbohydrate = $_POST['carbohydrate'];
    $fats = $_POST['fats'];
    $day_of_week = $_POST['day_of_week'];

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
