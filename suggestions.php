<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/styles/suggestions-style.css">
    <title>Meal Plan & Workouts</title>
</head>
<style>

    .content-section {
        display: none;
        padding: 20px;
        text-align: center;
    }

    .content-section.active {
        display: block;
    }
</style>

<body>

    <div class="container">
        <div class="navbar">
            <div class="nav-buttons">
                <a href="#" id="mealPlanButton" class="active">Meal Plan</a>
                <a href="#" id="workoutPlanButton">Workouts</a>
            </div>
        </div>

        <div id="mealPlanSection" class="content-section active">
            <?php include 'meal-plan.php'; ?>
        </div>

        <div id="workoutPlanSection" class="content-section">
            <?php include 'workout-plan.php'; ?>
        </div>
    </div>

    <script>
        const mealPlanButton = document.getElementById('mealPlanButton');
        const workoutPlanButton = document.getElementById('workoutPlanButton');
        const mealPlanSection = document.getElementById('mealPlanSection');
        const workoutPlanSection = document.getElementById('workoutPlanSection');

        // Add click event for Meal Plan button
        mealPlanButton.addEventListener('click', function(e) {
            e.preventDefault();
            mealPlanSection.classList.add('active');
            workoutPlanSection.classList.remove('active');
            mealPlanButton.classList.add('active');
            workoutPlanButton.classList.remove('active');
        });

        // Add click event for Workouts button
        workoutPlanButton.addEventListener('click', function(e) {
            e.preventDefault();
            workoutPlanSection.classList.add('active');
            mealPlanSection.classList.remove('active');
            workoutPlanButton.classList.add('active');
            mealPlanButton.classList.remove('active');
        });
    </script>
</body>

</html>