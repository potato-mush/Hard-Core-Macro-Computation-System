<?php
include('functions/conn.php');
session_start();

// Check if user exists in tbl_status
$showBackButton = false;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT id FROM tbl_status WHERE user_id = :user_id LIMIT 1");
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();
    $showBackButton = $stmt->fetch() ? true : false;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hard Core Macro Computation System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/styles/calculate.css">
</head>

<body>
    <div class="header">
        <?php if ($showBackButton): ?>
        <a href="index.php" class="back-button">
            <i class="fas fa-arrow-left"></i>
        </a>
        <?php endif; ?>
        <h1>Hard Core Macro Computation System</h1>
    </div>

    <!-- Progress Navigation -->
    <div class="progress-navigation">
        <!-- Progress Bar -->
        <div class="progress-bar-container">
            <div class="progress-bar" id="progressBar"></div>
        </div>

        <!-- Progress Steps -->
        <div class="progress-steps">
            <button><i class="fas fa-venus-mars"></i></button>
            <button><i class="fas fa-birthday-cake"></i></button>
            <button><i class="fas fa-bullseye"></i></button>
            <button><i class="fas fa-running"></i></button>
            <button><i class="fas fa-weight"></i></button>
            <button><i class="fas fa-dumbbell"></i></button>
        </div>
    </div>

    <!-- Gender Section -->
    <div class="section-container">
        <div class="section active">
            <h2>Gender</h2>
            <div class="gender-buttons">
                <button onclick="selectGender('male')">Male</button>
                <button onclick="selectGender('female')">Female</button>
            </div>
        </div>

        <!-- Birthdate Section -->
        <div class="section">
            <h2>Birthdate</h2>
            <div class="age-container">
                <p>Age: <span id="ageValue">18</span></p>
                <input type="range" min="18" max="75" value="18" class="age-slider" id="ageSlider">
            </div>
            <button class="confirmBtn" onclick="confirmAge()">Confirm</button>
        </div>

        <!-- Fitness Goal Section -->
        <div class="section">
            <h2>Fitness Goal</h2>
            <div class="fitness-goal-buttons">
                <button onclick="selectFitnessGoal('Lose Weight')">Lose Weight</button>
                <button onclick="selectFitnessGoal('Gain Strength')">Gain Strength</button>
                <button onclick="selectFitnessGoal('Gain Muscle')">Gain Muscle</button>
            </div>
        </div>

        <!-- Fitness Level Section -->
        <div class="section">
            <h2>Fitness Level</h2>
            <div class="fitness-level-buttons">
                <button onclick="selectFitnessLevel('Novice')">Novice</button>
                <button onclick="selectFitnessLevel('Beginner')">Beginner</button>
                <button onclick="selectFitnessLevel('Intermediate')">Intermediate</button>
                <button onclick="selectFitnessLevel('Advanced')">Advanced</button>
            </div>
        </div>

        <!-- Weight and Height Section -->
        <div class="section">
            <h2>Weight and Height</h2>
            <div class="weight-height-inputs">
                <input type="number" placeholder="Weight (kg)" id="weightInput" required>
                <input type="number" placeholder="Height (cm)" id="heightInput" required>
            </div>
            <button class="confirmBtn" onclick="confirmWeightHeight()" disabled>Confirm</button>
        </div>

        <!-- Macro Ratio and Muscle Group Section -->
        <div class="section">
            <h2>Muscle Group Selection</h2>
            <div class="muscle-group-buttons">
                <button onclick="selectMuscleGroup('Upper Body')">Upper Body</button>
                <button onclick="selectMuscleGroup('Lower Body')">Lower Body</button>
                <button onclick="selectMuscleGroup('Core')">Core</button>
                <button onclick="selectMuscleGroup('Full Body')">Full Body</button>
            </div>
            <button class="confirmBtn" onclick="calculateAndShowMacros()">Calculate Macros</button>
        </div>
    </div>

    <!-- Results Modal -->
    <div class="modal" id="muscleGroupModal">
        <div class="modal-content">
            <button class="close-modal" onclick="closeModal()"><i class="fas fa-times"></i></button>
            <h3>Your Daily Macros</h3>
            <div class="macro-results">
                <input type="text" placeholder="Carbohydrate grams/day" id="carbInput" readonly>
                <input type="text" placeholder="Protein grams/day" id="proteinInput" readonly>
                <input type="text" placeholder="Fats grams/day" id="fatsInput" readonly>
            </div>
            <button class="confirm-macros-btn" onclick="confirmAndRedirect()">Confirm & Continue</button>
        </div>
    </div>

    <script>
        let currentSection = 0;
        const totalSections = 6;
        const sections = document.querySelectorAll('.section');
        const progressBar = document.getElementById('progressBar');
        const ageSlider = document.getElementById('ageSlider');
        const ageValue = document.getElementById('ageValue');
        const muscleGroupModal = document.getElementById('muscleGroupModal');
        const weightInput = document.getElementById('weightInput');
        const heightInput = document.getElementById('heightInput');
        const weightHeightConfirmBtn = document.querySelector('.section:nth-child(5) .confirmBtn');

        function showSection(index) {
            sections.forEach((section, i) => {
                section.classList.toggle('active', i === index);
            });
            currentSection = index;
            updateProgressBar();
            updateActiveStep();
        }

        function updateProgressBar() {
            const progress = (currentSection / (totalSections - 1)) * 100;
            progressBar.style.width = `${progress}%`;
        }

        function updateActiveStep() {
            const steps = document.querySelectorAll('.progress-steps button');
            steps.forEach((step, i) => {
                step.classList.toggle('active', i === currentSection);
            });
        }

        function selectGender(gender) {
            document.querySelectorAll('.gender-buttons button').forEach(button => button.classList.remove('active'));
            event.target.classList.add('active');
            console.log('Selected Gender:', gender);
            localStorage.setItem('gender', gender);
            showSection(currentSection + 1);
        }

        function selectFitnessGoal(goal) {
            document.querySelectorAll('.fitness-goal-buttons button').forEach(button => button.classList.remove('active'));
            event.target.classList.add('active');
            console.log('Selected Fitness Goal:', goal);
            localStorage.setItem('fitness_goal', goal);
            showSection(currentSection + 1);
        }

        function selectFitnessLevel(level) {
            document.querySelectorAll('.fitness-level-buttons button').forEach(button => button.classList.remove('active'));
            event.target.classList.add('active');
            console.log('Selected Fitness Level:', level);
            localStorage.setItem('fitness_level', level);
            showSection(currentSection + 1);
        }

        function confirmAge() {
            console.log('Confirmed Age:', ageSlider.value);
            localStorage.setItem('age', ageSlider.value);
            showSection(currentSection + 1);
        }

        function confirmWeightHeight() {
            const weight = weightInput.value;
            const height = heightInput.value;
            console.log('Weight:', weight, 'Height:', height);
            localStorage.setItem('weight', weight);
            localStorage.setItem('height', height);
            showSection(currentSection + 1);
        }

        function openModal() {
            muscleGroupModal.style.display = 'flex';
        }

        function closeModal() {
            muscleGroupModal.style.display = 'none';
        }

        function selectMuscleGroup(group) {
            document.querySelectorAll('.muscle-group-buttons button').forEach(button => {
                button.classList.remove('active');
                if (button.textContent === group) {
                    button.classList.add('active');
                }
            });
            localStorage.setItem('muscle_group', group);
        }

        function calculateAndShowMacros() {
            computeMacros();
            openModal();
        }

        function confirmAndRedirect() {
            // Save the final calculations if needed
            window.location.href = 'profile.php';
        }

        function computeMacros() {
            const gender = localStorage.getItem('gender');
            const age = localStorage.getItem('age');
            const fitnessGoal = localStorage.getItem('fitness_goal');
            const fitnessLevel = localStorage.getItem('fitness_level');
            const weight = localStorage.getItem('weight');
            const height = localStorage.getItem('height');
            const muscleGroup = localStorage.getItem('muscle_group');
            const userId = localStorage.getItem('user_id'); // Assuming user_id is stored in localStorage

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'functions/computation.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        try {
                            console.log('Response Text:', xhr.responseText); // Log the response text
                            const response = JSON.parse(xhr.responseText);
                            document.getElementById('carbInput').value = `${response.carbs} grams/day`;
                            document.getElementById('proteinInput').value = `${response.protein} grams/day`;
                            document.getElementById('fatsInput').value = `${response.fats} grams/day`;
                        } catch (e) {
                            console.error('Error parsing JSON response:', e);
                            alert('An error occurred while processing your request. Please try again.');
                        }
                    } else {
                        console.error('Request failed with status:', xhr.status);
                        alert('An error occurred while processing your request. Please try again.');
                    }
                }
            };
            xhr.send(`gender=${gender}&age=${age}&fitness_goal=${fitnessGoal}&fitness_level=${fitnessLevel}&weight=${weight}&height=${height}&muscle_group=${muscleGroup}&user_id=${userId}`);
        }

        function validateWeightHeightInputs() {
            if (weightInput.value && heightInput.value) {
                weightHeightConfirmBtn.disabled = false;
            } else {
                weightHeightConfirmBtn.disabled = true;
            }
        }

        weightInput.addEventListener('input', validateWeightHeightInputs);
        heightInput.addEventListener('input', validateWeightHeightInputs);

        ageSlider.addEventListener('input', () => {
            ageValue.textContent = ageSlider.value;
        });

        // Initialize progress bar and active step
        updateProgressBar();
        updateActiveStep();
        validateWeightHeightInputs();
    </script>
</body>

</html>