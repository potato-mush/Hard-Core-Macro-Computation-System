<?php
$page = isset($_GET['meal_page']) ? (int)$_GET['meal_page'] : 1;
$per_page = isset($_GET['meal_per_page']) ? (int)$_GET['meal_per_page'] : 5;
$offset = ($page - 1) * $per_page;

// Get total count
$stmt = $pdo->query("SELECT COUNT(*) FROM tbl_meals");
$total_records = $stmt->fetchColumn();
$total_pages = ceil($total_records / $per_page);

// Fetch meals with pagination - fixed query
$stmt = $pdo->query("SELECT * FROM tbl_meals LIMIT " . (int)$per_page . " OFFSET " . (int)$offset);
$meals = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div id="meals-section" class="<?php echo $active_section != 'meals' ? 'd-none' : ''; ?>">
    <div class="section-header d-flex justify-content-between align-items-center">
        <h3>Meals Management</h3>
        <button class="btn btn-blue-no-radius text-white" data-bs-toggle="modal" data-bs-target="#addMealModal">
            Add New Meal
        </button>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Food Title</th>
                <th>Meal Type</th>
                <th>Day of Week</th>
                <th>Macros (P/C/F)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($meals as $meal): ?>
            <tr>
                <td><?php echo htmlspecialchars($meal['food_title']); ?></td>
                <td><?php echo htmlspecialchars($meal['meal_type']); ?></td>
                <td><?php echo htmlspecialchars($meal['day_of_week']); ?></td>
                <td>
                    P: <?php echo htmlspecialchars($meal['protein']); ?>g / 
                    C: <?php echo htmlspecialchars($meal['carbohydrate']); ?>g / 
                    F: <?php echo htmlspecialchars($meal['fats']); ?>g
                </td>
                <td>
                    <button class="btn btn-sm btn-primary" onclick="editMeal(<?php echo $meal['id']; ?>)">Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="deleteMeal(<?php echo $meal['id']; ?>)">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Add pagination controls -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="per-page-select">
            <select class="form-select" onchange="changeMealsPerPage(this.value)">
                <option value="5" <?php echo $per_page == 5 ? 'selected' : ''; ?>>5 per page</option>
                <option value="10" <?php echo $per_page == 10 ? 'selected' : ''; ?>>10 per page</option>
                <option value="25" <?php echo $per_page == 25 ? 'selected' : ''; ?>>25 per page</option>
            </select>
        </div>
        <nav>
            <ul class="pagination">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?section=meals&meal_page=<?php echo ($page-1); ?>&meal_per_page=<?php echo $per_page; ?>">Previous</a>
                    </li>
                <?php endif; ?>
                
                <?php
                $start = max(1, $page - 2);
                $end = min($total_pages, $start + 4);
                if ($end - $start < 4) {
                    $start = max(1, $end - 4);
                }
                
                if ($start > 1): ?>
                    <li class="page-item"><a class="page-link" href="?section=meals&meal_page=1&meal_per_page=<?php echo $per_page; ?>">1</a></li>
                    <?php if ($start > 2): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>
                <?php endif; ?>

                <?php for($i = $start; $i <= $end; $i++): ?>
                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                        <a class="page-link" href="?section=meals&meal_page=<?php echo $i; ?>&meal_per_page=<?php echo $per_page; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($end < $total_pages): ?>
                    <?php if ($end < $total_pages - 1): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>
                    <li class="page-item"><a class="page-link" href="?section=meals&meal_page=<?php echo $total_pages; ?>&meal_per_page=<?php echo $per_page; ?>"><?php echo $total_pages; ?></a></li>
                <?php endif; ?>

                <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?section=meals&meal_page=<?php echo ($page+1); ?>&meal_per_page=<?php echo $per_page; ?>">Next</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteMealModal" tabindex="-1" aria-labelledby="deleteMealModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteMealModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this meal?
                </div>  
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    let deleteModal;
    let mealToDelete = null;

    // Initialize after DOM is fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        deleteModal = new bootstrap.Modal(document.getElementById('deleteMealModal'));
        
        // Add event listener for delete confirmation
        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (mealToDelete) {
                fetch('functions/deleteMeal.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'meal_id=' + mealToDelete
                })
                .then(response => response.text())
                .then(result => {
                    deleteModal.hide();
                    if (result === 'success') {
                        location.reload();
                    } else {
                        alert('Error deleting meal');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    deleteModal.hide();
                    alert('Error deleting meal');
                });
            }
        });
    });

    function deleteMeal(mealId) {
        mealToDelete = mealId;
        if (deleteModal) {
            deleteModal.show();
        }
    }
    </script>
</div>
