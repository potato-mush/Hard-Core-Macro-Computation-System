<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$section = isset($_GET['section']) ? $_GET['section'] : 'profile';

// Include the database connection
require 'functions/conn.php';

// Fetch user details from the database
$user_id = $_SESSION['user_id'];

$user_query = $pdo->prepare("SELECT image, email FROM tbl_users WHERE id = ?");
$user_query->execute([$user_id]);
$user_data = $user_query->fetch(PDO::FETCH_ASSOC);

$status_query = $pdo->prepare("SELECT fitness_level, age, carbs, protein, fats FROM tbl_status WHERE user_id = ?");
$status_query->execute([$user_id]);
$status_data = $status_query->fetch(PDO::FETCH_ASSOC);

// Calculate additional nutritional information
$sugar = $status_data['carbs'] * 0.2; // Example calculation
$saturated_fat = $status_data['fats'] * 0.4; // Example calculation
$food_energy_calories = ($status_data['protein'] * 4) + ($status_data['carbs'] * 4) + ($status_data['fats'] * 9);
$food_energy_kj = $food_energy_calories * 4.184;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="assets/styles/profile.css">
</head>

<body>
    <?php include 'profileNavbar.php'; ?>
    <div class="profile-container">
        <div class="profile-info-container">
            <?php if ($section == 'profile'): ?>
                <div class="profile-info">
                    <img src="admin/uploads/<?php echo htmlspecialchars($user_data['image']); ?>" alt="Profile Picture" class="profile-picture">
                    <div class="user-info">
                        <h1><?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
                        <p>Email: <?php echo htmlspecialchars($user_data['email']); ?></p>
                        <p>Fitness Level: <?php echo htmlspecialchars($status_data['fitness_level']); ?></p>
                        <p>Age: <?php echo htmlspecialchars($status_data['age']); ?></p>
                    </div>
                </div>
                <div class="nutrition-info">
                    <div class="nutrition-row">
                        <div class="nutrition-label">Protein</div>
                        <div class="nutrition-data">
                            <p><?php echo htmlspecialchars($status_data['protein']); ?> grams/day</p>
                            <p class="nutrition-range">Range: 65 - 207 grams/day</p>
                        </div>
                    </div>
                    <div class="nutrition-row">
                        <div class="nutrition-label">Carbs</div>
                        <div class="nutrition-data">
                            <p><?php echo htmlspecialchars($status_data['carbs']); ?> grams/day</p>
                            <p class="nutrition-range">Range: 259 - 446 grams/day</p>
                        </div>
                    </div>
                    <div class="nutrition-row">
                        <div class="nutrition-label">Fat</div>
                        <div class="nutrition-data">
                            <p><?php echo htmlspecialchars($status_data['fats']); ?> grams/day</p>
                            <p class="nutrition-range">Range: 55 - 96 grams/day</p>
                        </div>
                    </div>
                    <div class="nutrition-row">
                        <div class="nutrition-label">Sugar</div>
                        <div class="nutrition-data">
                            <p>
                                < <?php echo htmlspecialchars($sugar); ?> grams/day</p>
                        </div>
                    </div>
                    <div class="nutrition-row">
                        <div class="nutrition-label">Saturated Fat</div>
                        <div class="nutrition-data">
                            <p>
                                < <?php echo htmlspecialchars($saturated_fat); ?> grams/day</p>
                        </div>
                    </div>
                    <div class="nutrition-row">
                        <div class="nutrition-label">Food Energy</div>
                        <div class="nutrition-data">
                            <p><?php echo htmlspecialchars($food_energy_calories); ?> Calories/day</p>
                            <p class="nutrition-range">or <?php echo htmlspecialchars($food_energy_kj); ?> kJ/day</p>
                        </div>
                    </div>
                </div>
            <?php elseif ($section == 'meal_plan'): ?>
                <div class="meal-plan-section">
                    <div class="meal-plan-dropdown">
                        <select id="day-select" onchange="loadMeals()">
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                        </select>
                    </div>
                    <div id="meal-plan-content" class="meal-plan-content">
                        <!-- Meal types will be loaded here -->
                    </div>
                </div>
            <?php elseif ($section == 'workouts'): ?>
                <div class="meal-plan-section">
                    <div class="meal-plan-dropdown">
                        <select id="workout-day-select" onchange="loadWorkouts()">
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                        </select>
                    </div>
                    <div id="workout-plan-content" class="meal-plan-content">
                        <!-- Workouts will be loaded here -->
                    </div>
                </div>
            <?php elseif ($section == 'calculate'): ?>
                <div class="calculate-section">
                    <h2>Calculate</h2>
                    <!-- Add calculate content here -->
                </div>
            <?php endif; ?>
        </div>
        <div class="real-time-info">
            <div class="meal-today">
                <h2>Meal Today</h2>
                <div id="meal-today-content">
                    <!-- Meal today content will be loaded here -->
                </div>
            </div>
            <div class="workout-today">
                <h2>Workout Today</h2>
                <div id="workout-today-content">
                    <!-- Workout today content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
    <script>
        function loadMeals() {
            const daySelect = document.getElementById('day-select');
            if (!daySelect) return; // Ensure the element exists

            const day = daySelect.value;
            fetch(`functions/load_meals.php?day=${day}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text(); // Get the response as text
                })
                .then(text => {
                    console.log('Response Text:', text); // Log the response text
                    const data = JSON.parse(text); // Parse the text as JSON
                    if (data.error) {
                        console.error('Error:', data.error);
                        return;
                    }

                    const mealPlanContent = document.getElementById('meal-plan-content');
                    mealPlanContent.innerHTML = `<h3>${day}</h3>`;
                    const mealTypes = ['Breakfast', 'Snack (AM)', 'Lunch', 'Snack (PM)', 'Dinner', 'Optional Evening Snack'];
                    mealTypes.forEach(type => {
                        const mealTypeDiv = document.createElement('div');
                        mealTypeDiv.classList.add('meal-type');
                        mealTypeDiv.innerHTML = `<h4>${type}</h4>`;
                        const meals = (data.meal_plan || []).filter(meal => meal.meal_type === type);
                        meals.forEach(meal => {
                            const mealItem = document.createElement('p');
                            mealItem.textContent = `${meal.food_title}: ${meal.ingredients}`;
                            mealTypeDiv.appendChild(mealItem);
                        });
                        mealPlanContent.appendChild(mealTypeDiv);
                    });

                    // Display the remaining carbs, protein, and fats
                    const totalsDiv = document.createElement('div');
                    totalsDiv.classList.add('totals');
                    totalsDiv.innerHTML = `
                        <h4>Remaining Daily Totals</h4>
                        <p>Carbs: ${data.total_carbohydrates} grams</p>
                        <p>Protein: ${data.total_proteins} grams</p>
                        <p>Fats: ${data.total_fats} grams</p>
                    `;
                    mealPlanContent.appendChild(totalsDiv);
                })
                .catch(error => {
                    console.error('Error loading meals:', error);
                });
        }

        function loadMealToday() {
            fetch(`functions/load_meals.php?day=${new Date().toLocaleDateString('en-US', { weekday: 'long' })}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(text => {
                    console.log('Response Text:', text);
                    const data = JSON.parse(text);
                    if (data.error) {
                        console.error('Error:', data.error);
                        return;
                    }

                    const mealTodayContent = document.getElementById('meal-today-content');
                    mealTodayContent.innerHTML = '';
                    const mealTypes = ['Breakfast', 'Snack (AM)', 'Lunch', 'Snack (PM)', 'Dinner', 'Optional Evening Snack'];
                    mealTypes.forEach(type => {
                        const mealTypeDiv = document.createElement('div');
                        mealTypeDiv.classList.add('meal-type');
                        mealTypeDiv.innerHTML = `<h4>${type}</h4>`;
                        const meals = (data.meal_plan || []).filter(meal => meal.meal_type === type);
                        meals.forEach(meal => {
                            const mealItem = document.createElement('p');
                            mealItem.textContent = `${meal.food_title}: ${meal.ingredients}`;
                            mealTypeDiv.appendChild(mealItem);
                        });
                        mealTodayContent.appendChild(mealTypeDiv);
                    });
                })
                .catch(error => {
                    console.error('Error loading meal today:', error);
                });
        }

        function loadWorkouts() {
            const daySelect = document.getElementById('workout-day-select');
            if (!daySelect) return; // Ensure the element exists

            const day = daySelect.value;
            fetch(`functions/load_workouts.php?day=${day}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text(); // Get the response as text
                })
                .then(text => {
                    console.log('Response Text:', text); // Log the response text
                    const data = JSON.parse(text); // Parse the text as JSON
                    if (data.error) {
                        console.error('Error:', data.error);
                        return;
                    }

                    const workoutPlanContent = document.getElementById('workout-plan-content');
                    workoutPlanContent.innerHTML = `<h3>${day}</h3>`;
                    workoutPlanContent.innerHTML += `
                        <h3>Warm-up</h3>
                        <p>5-10 minutes of light cardio (jogging, cycling, or dynamic stretching)</p>
                        <h3>Workout</h3>
                    `;

                    if (data.workouts.length === 0) {
                        workoutPlanContent.innerHTML += `<p>No workouts found for your current status.</p>`;
                    } else {
                        data.workouts.forEach(workout => {
                            const workoutItem = document.createElement('p');
                            workoutItem.textContent = `${workout.workout_title} – ${workout.sets} sets x ${workout.reps} reps`;
                            workoutPlanContent.appendChild(workoutItem);
                        });
                    }

                    workoutPlanContent.innerHTML += `
                        <h3>Cool-down</h3>
                        <p>5-10 minutes of stretching (focus on ${data.status.muscle_group})</p>
                    `;
                })
                .catch(error => {
                    console.error('Error loading workouts:', error);
                });
        }

        function loadWorkoutToday() {
            fetch(`functions/load_workouts.php?day=${new Date().toLocaleDateString('en-US', { weekday: 'long' })}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(text => {
                    console.log('Response Text:', text);
                    const data = JSON.parse(text);
                    if (data.error) {
                        console.error('Error:', data.error);
                        return;
                    }

                    const workoutTodayContent = document.getElementById('workout-today-content');
                    workoutTodayContent.innerHTML = '';
                    workoutTodayContent.innerHTML += `
                        <h3>Warm-up</h3>
                        <p>5-10 minutes of light cardio (jogging, cycling, or dynamic stretching)</p>
                        <h3>Workout</h3>
                    `;

                    if (data.workouts.length === 0) {
                        workoutTodayContent.innerHTML += `<p>No workouts found for your current status.</p>`;
                    } else {
                        data.workouts.forEach(workout => {
                            const workoutItem = document.createElement('p');
                            workoutItem.textContent = `${workout.workout_title} – ${workout.sets} sets x ${workout.reps} reps`;
                            workoutTodayContent.appendChild(workoutItem);
                        });
                    }

                    workoutTodayContent.innerHTML += `
                        <h3>Cool-down</h3>
                        <p>5-10 minutes of stretching (focus on ${data.status.muscle_group})</p>
                    `;
                })
                .catch(error => {
                    console.error('Error loading workout today:', error);
                });
        }

        // Load meals and workouts for today on page load
        document.addEventListener('DOMContentLoaded', () => {
            loadMealToday();
            loadWorkoutToday();
        });

        // Load meals for the default day (Monday) on page load if the section is meal_plan
        if (window.location.search.includes('section=meal_plan')) {
            document.addEventListener('DOMContentLoaded', loadMeals);
        }

        // Load workouts on page load if the section is workouts
        if (window.location.search.includes('section=workouts')) {
            document.addEventListener('DOMContentLoaded', loadWorkouts);
        }
    </script>
</body>

</html>