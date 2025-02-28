<?php
require_once('db.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_workout'])) {
    $workout_title = $_POST['workout_title'];
    $day_of_week = $_POST['day_of_week'];
    $fitness_level = $_POST['fitness_level'];
    $sets = $_POST['sets'];
    $reps = $_POST['reps'];

    $stmt = $pdo->prepare("INSERT INTO tbl_workouts (workout_title, day_of_week, fitness_level, sets, reps) VALUES (:title, :day, :level, :sets, :reps)");
    
    $stmt->bindParam(':title', $workout_title);
    $stmt->bindParam(':day', $day_of_week);
    $stmt->bindParam(':level', $fitness_level);
    $stmt->bindParam(':sets', $sets);
    $stmt->bindParam(':reps', $reps);

    if ($stmt->execute()) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}
?>

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
                    <button type="submit" name="add_workout" class="btn btn-primary">Save Workout</button>
                </form>
            </div>
        </div>
    </div>
</div>
