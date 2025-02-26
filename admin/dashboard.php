<?php
include('functions/db.php');
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: index.php');
    exit();
}

// Get the active section from URL parameter, default to users
$active_section = isset($_GET['section']) ? $_GET['section'] : 'users';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Add these new styles */
        .nav-links {
            display: flex;
            gap: 20px;
            margin-left: 50px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 5px 15px;
            border-radius: 4px;
        }

        .nav-links a.active {
            background-color: #007bff;
        }

        .nav-links a:hover {
            background-color: #555;
        }

        /* Full-width container with margin */
        body {
            background-color: #f2f2f2;
        }

        .header {
            background-color: rgb(42, 42, 42);
            padding: 10px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            margin: 0;
        }

        .btn-logout {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 8px 16px;
        }

        .btn-logout:hover {
            background-color: #c82333;
        }

        .btn-blue-no-radius {
            background-color: #007bff;
            border-radius: 0;
        }

        .btn-trash {
            background-color: transparent;
            border: none;
            color: #aaa;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-trash i {
            font-size: 16px;
        }

        .btn-trash:hover {
            color: rgb(255, 67, 67);
        }

        .btn-search {
            background-color: transparent;
            border: #aaa 1px solid;
            color: #aaa;
            padding: 8px 12px;
        }

        .btn-search i {
            font-size: 18px;
        }

        .table th {
            background-color: #ccc;
            border: 1px solid white;
        }

        .table td {
            border-bottom: 1px solid #ccc;
        }

        .table tbody tr:nth-child(odd),
        .table tbody tr:nth-child(even) {
            background-color: transparent;
        }

        /* Adjust container to be full-width */
        .container {
            width: 100%;
            max-width: 1200px;
            /* Optional: Set a max width for larger screens */
            margin: 20px auto;
            /* Add margin on sides */
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Pagination controls */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .pagination a {
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .pagination a:hover {
            background-color: #0056b3;
        }

        .pagination .disabled {
            background-color: #ddd;
            pointer-events: none;
        }

        .section-header {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #007bff;
        }

        .see-all-link {
            color: #007bff;
            text-decoration: none;
        }

        .see-all-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <!-- Modified Header Section -->
    <div class="header">
        <div class="d-flex align-items-center">
            <h1>Admin Dashboard</h1>
            <div class="nav-links">
                <a href="?section=users" class="<?php echo $active_section == 'users' ? 'active' : ''; ?>">Users</a>
                <a href="?section=workouts" class="<?php echo $active_section == 'workouts' ? 'active' : ''; ?>">Workouts</a>
                <a href="?section=meals" class="<?php echo $active_section == 'meals' ? 'active' : ''; ?>">Meals</a>
            </div>
        </div>
        <a href="logout.php" class="btn btn-logout">Logout</a>
    </div>

    <div class="container mt-5">
        <?php
        // Include the appropriate section content
        switch ($active_section) {
            case 'workouts':
                include('sections/workouts_content.php');
                break;
            case 'meals':
                include('sections/meals_content.php');
                break;
            default:
                include('sections/users_content.php');
        }
        ?>
    </div>

    <!-- Only keep Add Workout and Add Meal modals, remove Add User modal -->
    <!-- Add Workout Modal -->
    <div class="modal fade" id="addWorkoutModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Workout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="functions/addWorkout.php" method="POST">
                        <div class="mb-3">
                            <label for="workout_title" class="form-label">Workout Title</label>
                            <input type="text" class="form-control" name="workout_title" required>
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
                            <label for="fitness_level" class="form-label">Fitness Level</label>
                            <select class="form-control" name="fitness_level" required>
                                <option value="Beginner">Beginner</option>
                                <option value="Intermediate">Intermediate</option>
                                <option value="Advanced">Advanced</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="sets" class="form-label">Sets</label>
                            <input type="number" class="form-control" name="sets" required>
                        </div>
                        <div class="mb-3">
                            <label for="reps" class="form-label">Reps</label>
                            <input type="number" class="form-control" name="reps" required>
                        </div>
                        <button type="submit" name="add_workout" class="btn btn-primary">Add Workout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="add_protein" class="form-label">Protein (g)</label>
                                    <input type="number" class="form-control" name="protein" id="add_protein" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="add_carbohydrate" class="form-label">Carbs (g)</label>
                                    <input type="number" class="form-control" name="carbohydrate" id="add_carbohydrate" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="add_fats" class="form-label">Fats (g)</label>
                                    <input type="number" class="form-control" name="fats" id="add_fats" required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" name="add_meal" class="btn btn-primary">Add Meal</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Include edit functions -->
    <?php
    require_once('functions/db.php');
    include('functions/editWorkout.php');
    include('functions/editMeal.php');
    ?>

    <!-- Bootstrap 5 JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function editMeal(mealId) {
            fetch(`functions/editMeal.php?id=${mealId}`)
                .then(response => response.json())
                .then(meal => {
                    document.getElementById('edit_meal_id').value = meal.id;
                    document.getElementById('edit_food_title').value = meal.food_title;
                    document.getElementById('edit_meal_type').value = meal.meal_type;
                    document.getElementById('edit_day_of_week').value = meal.day_of_week;
                    document.getElementById('edit_ingredients').value = meal.ingredients;
                    document.getElementById('edit_protein').value = meal.protein;
                    document.getElementById('edit_carbohydrate').value = meal.carbohydrate;
                    document.getElementById('edit_fats').value = meal.fats;

                    new bootstrap.Modal(document.getElementById('editMealModal')).show();
                })
                .catch(error => console.error('Error:', error));
        }

        function editWorkout(workoutId) {
            fetch(`functions/editWorkout.php?id=${workoutId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(workout => {
                    if (workout.error) {
                        throw new Error(workout.error);
                    }

                    document.getElementById('edit_workout_id').value = workout.id;
                    document.getElementById('edit_workout_title').value = workout.workout_title;
                    document.getElementById('edit_workout_day').value = workout.day_of_week;
                    document.getElementById('edit_fitness_level').value = workout.fitness_level;
                    document.getElementById('edit_sets').value = workout.sets;
                    document.getElementById('edit_reps').value = workout.reps;

                    const modal = new bootstrap.Modal(document.getElementById('editWorkoutModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading workout data. Please try again.');
                });
        }

        function changeWorkoutsPerPage(value) {
            window.location.href = `?section=workouts&workout_page=1&workout_per_page=${value}`;
        }

        function changeUsersPerPage(value) {
            window.location.href = `?section=users&user_page=1&user_per_page=${value}`;
        }

        function changeMealsPerPage(value) {
            window.location.href = `?section=meals&meal_page=1&meal_per_page=${value}`;
        }
    </script>
</body>

</html>