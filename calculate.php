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
        <a href="index.php" class="back-button">
            <i class="fas fa-arrow-left"></i>
        </a>
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
            <button onclick="showSection(0)"><i class="fas fa-venus-mars"></i></button>
            <button onclick="showSection(1)"><i class="fas fa-birthday-cake"></i></button>
            <button onclick="showSection(2)"><i class="fas fa-bullseye"></i></button>
            <button onclick="showSection(3)"><i class="fas fa-running"></i></button>
            <button onclick="showSection(4)"><i class="fas fa-weight"></i></button>
            <button onclick="showSection(5)"><i class="fas fa-dumbbell"></i></button>
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
                <button onclick="selectFitnessGoal('lose-weight')">Lose Weight</button>
                <button onclick="selectFitnessGoal('gain-strength')">Gain Strength</button>
                <button onclick="selectFitnessGoal('gain-muscle')">Gain Muscle</button>
            </div>
        </div>

        <!-- Fitness Level Section -->
        <div class="section">
            <h2>Fitness Level</h2>
            <div class="fitness-level-buttons">
                <button onclick="selectFitnessLevel('novice')">Novice</button>
                <button onclick="selectFitnessLevel('beginner')">Beginner</button>
                <button onclick="selectFitnessLevel('intermediate')">Intermediate</button>
                <button onclick="selectFitnessLevel('advanced')">Advanced</button>
            </div>
        </div>

        <!-- Weight and Height Section -->
        <div class="section">
            <h2>Weight and Height</h2>
            <div class="weight-height-inputs">
                <input type="number" placeholder="Weight (kg)" id="weightInput">
                <input type="number" placeholder="Height (cm)" id="heightInput">
            </div>
            <button class="confirmBtn" onclick="confirmWeightHeight()">Confirm</button>
        </div>

        <!-- Macro Ratio and Muscle Group Section -->
        <div class="section">
            <h2>Macro Ratio and Muscle Group</h2>
            <div class="macro-ratio">
                <input type="number" placeholder="Carbohydrate (%)" id="carbInput">
                <input type="number" placeholder="Protein (%)" id="proteinInput">
                <input type="number" placeholder="Fats (%)" id="fatsInput">
            </div>
            <button class="muscle-group-button" onclick="openModal()">Select Muscle Group</button>
        </div>
    </div>

    <!-- Muscle Group Modal -->
    <div class="modal" id="muscleGroupModal">
        <div class="modal-content">
            <h3>Select Muscle Group</h3>
            <button onclick="selectMuscleGroup('Upper Body Push')">Upper Body Push</button>
            <button onclick="selectMuscleGroup('Upper Body Pull')">Upper Body Pull</button>
            <button onclick="selectMuscleGroup('Lower Body Push')">Lower Body Push</button>
            <button onclick="selectMuscleGroup('Lower Body Pull')">Lower Body Pull</button>
            <button onclick="selectMuscleGroup('Core')">Core</button>
            <button onclick="selectMuscleGroup('Shoulders')">Shoulders</button>
            <button onclick="selectMuscleGroup('Arms')">Arms</button>
            <button onclick="selectMuscleGroup('Full Body')">Full Body</button>
            <button onclick="closeModal()">Close</button>
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
        }

        function selectFitnessGoal(goal) {
            document.querySelectorAll('.fitness-goal-buttons button').forEach(button => button.classList.remove('active'));
            event.target.classList.add('active');
            console.log('Selected Fitness Goal:', goal);
        }

        function selectFitnessLevel(level) {
            document.querySelectorAll('.fitness-level-buttons button').forEach(button => button.classList.remove('active'));
            event.target.classList.add('active');
            console.log('Selected Fitness Level:', level);
        }

        function confirmAge() {
            console.log('Confirmed Age:', ageSlider.value);
        }

        function confirmWeightHeight() {
            const weight = document.getElementById('weightInput').value;
            const height = document.getElementById('heightInput').value;
            console.log('Weight:', weight, 'Height:', height);
        }

        function openModal() {
            muscleGroupModal.style.display = 'flex';
        }

        function closeModal() {
            muscleGroupModal.style.display = 'none';
        }

        function selectMuscleGroup(group) {
            console.log('Selected Muscle Group:', group);
            closeModal();
        }

        ageSlider.addEventListener('input', () => {
            ageValue.textContent = ageSlider.value;
        });

        // Initialize progress bar and active step
        updateProgressBar();
        updateActiveStep();
    </script>
</body>

</html>