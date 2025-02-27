<?php
$page = isset($_GET['user_page']) ? (int)$_GET['user_page'] : 1;
$per_page = isset($_GET['user_per_page']) ? (int)$_GET['user_per_page'] : 5;
$offset = ($page - 1) * $per_page;

// Get total count
$stmt = $pdo->query("SELECT COUNT(*) FROM tbl_users");
$total_records = $stmt->fetchColumn();
$total_pages = ceil($total_records / $per_page);

// Fetch users with pagination - fixed query
$stmt = $pdo->query("SELECT * FROM tbl_users LIMIT " . (int)$per_page . " OFFSET " . (int)$offset);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div id="users-section" class="<?php echo $active_section != 'users' ? 'd-none' : ''; ?>">
    <div class="section-header d-flex justify-content-between align-items-center">
        <h3>Users Management</h3>
        <button class="btn btn-blue-no-radius text-white" data-bs-toggle="modal" data-bs-target="#addUserModal">
            Add New User
        </button>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name / Email</th>
                <th>Role</th>
                <th>Last Login</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td>
                    <img src="uploads/<?php echo htmlspecialchars($user['image']); ?>" 
                         alt="Profile Image" class="rounded-circle" 
                         style="width: 40px; height: 40px; object-fit: cover;">
                </td>
                <td>
                    <?php echo htmlspecialchars($user['name']); ?><br>
                    <small><?php echo htmlspecialchars($user['email']); ?></small>
                </td>
                <td><?php echo htmlspecialchars($user['role']); ?></td>
                <td><?php echo htmlspecialchars($user['last_login']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Add pagination controls -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="per-page-select">
            <select class="form-select" onchange="changeUsersPerPage(this.value)">
                <option value="5" <?php echo $per_page == 5 ? 'selected' : ''; ?>>5 per page</option>
                <option value="10" <?php echo $per_page == 10 ? 'selected' : ''; ?>>10 per page</option>
                <option value="25" <?php echo $per_page == 25 ? 'selected' : ''; ?>>25 per page</option>
            </select>
        </div>
        <nav>
            <ul class="pagination">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?section=users&user_page=<?php echo ($page-1); ?>&user_per_page=<?php echo $per_page; ?>">Previous</a>
                    </li>
                <?php endif; ?>
                
                <?php
                $start = max(1, $page - 2);
                $end = min($total_pages, $start + 4);
                if ($end - $start < 4) {
                    $start = max(1, $end - 4);
                }
                
                if ($start > 1): ?>
                    <li class="page-item"><a class="page-link" href="?section=users&user_page=1&user_per_page=<?php echo $per_page; ?>">1</a></li>
                    <?php if ($start > 2): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>
                <?php endif; ?>

                <?php for($i = $start; $i <= $end; $i++): ?>
                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                        <a class="page-link" href="?section=users&user_page=<?php echo $i; ?>&user_per_page=<?php echo $per_page; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($end < $total_pages): ?>
                    <?php if ($end < $total_pages - 1): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>
                    <li class="page-item"><a class="page-link" href="?section=users&user_page=<?php echo $total_pages; ?>&user_per_page=<?php echo $per_page; ?>"><?php echo $total_pages; ?></a></li>
                <?php endif; ?>

                <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?section=users&user_page=<?php echo ($page+1); ?>&user_per_page=<?php echo $per_page; ?>">Next</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="functions/addUser.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-control" name="role" required>
                            <option value="basic">Basic Membership</option>
                            <option value="standard">Standard Membership</option>
                            <option value="premium">Premium Membership</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Profile Image</label>
                        <input type="file" class="form-control" name="image" accept="image/*">
                    </div>
                    <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
                </form>
            </div>
        </div>
    </div>
</div>
