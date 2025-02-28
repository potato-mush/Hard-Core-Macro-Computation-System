<?php
$page = isset($_GET['workout_page']) ? (int)$_GET['workout_page'] : 1;
$per_page = isset($_GET['workout_per_page']) ? (int)$_GET['workout_per_page'] : 5;
$offset = ($page - 1) * $per_page;

// Get total count
$stmt = $pdo->query("SELECT COUNT(*) FROM tbl_workouts");
$total_records = $stmt->fetchColumn();
$total_pages = ceil($total_records / $per_page);

// Fetch workouts with pagination - fixed query
$stmt = $pdo->query("SELECT * FROM tbl_workouts LIMIT " . (int)$per_page . " OFFSET " . (int)$offset);
$workouts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div id="workouts-section" class="<?php echo $active_section != 'workouts' ? 'd-none' : ''; ?>">
    <div class="section-header d-flex justify-content-between align-items-center">
        <h3>Workouts Management</h3>
        <button class="btn btn-blue-no-radius text-white" data-bs-toggle="modal" data-bs-target="#addWorkoutModal">
            Add New Workout
        </button>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Workout Title</th>
                <th>Day of Week</th>
                <th>Fitness Level</th>
                <th>Sets/Reps</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($workouts as $workout): ?>
            <tr>
                <td><?php echo htmlspecialchars($workout['workout_title']); ?></td>
                <td><?php echo htmlspecialchars($workout['day_of_week']); ?></td>
                <td><?php echo htmlspecialchars($workout['fitness_level']); ?></td>
                <td><?php echo htmlspecialchars($workout['sets']); ?> sets / <?php echo htmlspecialchars($workout['reps']); ?> reps</td>
                <td>
                    <button class="btn btn-sm btn-primary edit-workout" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editWorkoutModal" 
                            data-id="<?php echo $workout['workout_id']; ?>">Edit</button>
                    <button class="btn btn-sm btn-danger delete-workout" 
                            data-id="<?php echo $workout['workout_id']; ?>">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Add pagination controls -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="per-page-select">
            <select class="form-select" onchange="changeWorkoutsPerPage(this.value)">
                <option value="5" <?php echo $per_page == 5 ? 'selected' : ''; ?>>5 per page</option>
                <option value="10" <?php echo $per_page == 10 ? 'selected' : ''; ?>>10 per page</option>
                <option value="25" <?php echo $per_page == 25 ? 'selected' : ''; ?>>25 per page</option>
            </select>
        </div>
        <nav>
            <ul class="pagination">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?section=workouts&workout_page=<?php echo ($page-1); ?>&workout_per_page=<?php echo $per_page; ?>">Previous</a>
                    </li>
                <?php endif; ?>
                
                <?php
                $start = max(1, $page - 2);
                $end = min($total_pages, $start + 4);
                if ($end - $start < 4) {
                    $start = max(1, $end - 4);
                }
                
                if ($start > 1): ?>
                    <li class="page-item"><a class="page-link" href="?section=workouts&workout_page=1&workout_per_page=<?php echo $per_page; ?>">1</a></li>
                    <?php if ($start > 2): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>
                <?php endif; ?>

                <?php for($i = $start; $i <= $end; $i++): ?>
                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                        <a class="page-link" href="?section=workouts&workout_page=<?php echo $i; ?>&workout_per_page=<?php echo $per_page; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($end < $total_pages): ?>
                    <?php if ($end < $total_pages - 1): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>
                    <li class="page-item"><a class="page-link" href="?section=workouts&workout_page=<?php echo $total_pages; ?>&workout_per_page=<?php echo $per_page; ?>"><?php echo $total_pages; ?></a></li>
                <?php endif; ?>

                <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?section=workouts&workout_page=<?php echo ($page+1); ?>&workout_per_page=<?php echo $per_page; ?>">Next</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</div>

<!-- Add Delete Confirmation Modal -->
<div class="modal fade" id="deleteWorkoutModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this workout?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Edit Workout Modal -->
<div class="modal fade" id="editWorkoutModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Workout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editWorkoutForm">
                    <input type="hidden" name="workout_id" id="edit_workout_id">
                    <div class="mb-3">
                        <label for="edit_workout_title" class="form-label">Workout Title</label>
                        <input type="text" class="form-control" name="workout_title" id="edit_workout_title" required>
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
                        <label for="edit_fitness_level" class="form-label">Fitness Level</label>
                        <select class="form-control" name="fitness_level" id="edit_fitness_level" required>
                            <option value="Beginner">Beginner</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Advanced">Advanced</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_sets" class="form-label">Sets</label>
                        <input type="number" class="form-control" name="sets" id="edit_sets" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_reps" class="form-label">Reps</label>
                        <input type="number" class="form-control" name="reps" id="edit_reps" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Workout</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let workoutIdToDelete = null;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteWorkoutModal'));

    // Delete workout button click
    document.querySelectorAll('.delete-workout').forEach(button => {
        button.addEventListener('click', function() {
            workoutIdToDelete = this.getAttribute('data-id');
            deleteModal.show();
        });
    });

    // Confirm delete button click
    document.getElementById('confirmDelete').addEventListener('click', function() {
        if (workoutIdToDelete) {
            const formData = new FormData();
            formData.append('workout_id', workoutIdToDelete);

            fetch('functions/deleteWorkout.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    location.reload();
                } else {
                    alert('Error deleting workout: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting workout');
            })
            .finally(() => {
                deleteModal.hide();
            });
        }
    });

    const editModal = new bootstrap.Modal(document.getElementById('editWorkoutModal'));

    // Edit workout functionality
    document.querySelectorAll('.edit-workout').forEach(button => {
        button.addEventListener('click', function() {
            const workoutId = this.getAttribute('data-id');
            
            // Fetch workout data
            fetch(`functions/editWorkout.php?id=${workoutId}`)
                .then(response => response.json())
                .then(data => {
                    if (!data.error) {
                        document.getElementById('edit_workout_id').value = data.workout_id;
                        document.getElementById('edit_workout_title').value = data.workout_title;
                        document.getElementById('edit_day_of_week').value = data.day_of_week;
                        document.getElementById('edit_fitness_level').value = data.fitness_level;
                        document.getElementById('edit_sets').value = data.sets;
                        document.getElementById('edit_reps').value = data.reps;
                    } else {
                        alert('Error loading workout data');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading workout data');
                });
        });
    });

    // Handle edit form submission
    document.getElementById('editWorkoutForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('edit_workout', '1');

        fetch('functions/editWorkout.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                location.reload();
            } else {
                alert('Error updating workout: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating workout');
        })
        .finally(() => {
            editModal.hide();
        });
    });
});
</script>
