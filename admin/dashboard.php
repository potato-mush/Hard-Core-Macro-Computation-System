<?php
// Include the database connection
include('db.php');

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}

// Handle the delete action (if delete button is pressed)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    if (isset($_POST['user_ids']) && !empty($_POST['user_ids'])) {
        $user_ids = $_POST['user_ids'];

        // Delete selected users from the database
        $placeholders = implode(',', array_fill(0, count($user_ids), '?'));
        $stmt = $pdo->prepare("DELETE FROM tbl_users WHERE id IN ($placeholders)");
        $stmt->execute($user_ids);

        // Reload the page to reflect changes
        header('Location: dashboard.php');
        exit();
    }
}

// Pagination setup
$items_per_page = 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Search functionality
$search = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';

// Count the total number of users
$stmt_count = $pdo->prepare("SELECT COUNT(*) FROM tbl_users WHERE name LIKE :search OR email LIKE :search");
$stmt_count->bindParam(':search', $search);
$stmt_count->execute();
$total_users = $stmt_count->fetchColumn();

// Fetch users from tbl_users table with pagination
$stmt = $pdo->prepare("SELECT * FROM tbl_users WHERE name LIKE :search OR email LIKE :search LIMIT :offset, :limit");
$stmt->bindParam(':search', $search);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':limit', $items_per_page, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate total pages
$total_pages = ceil($total_users / $items_per_page);
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
    </style>
</head>

<body>

    <!-- Header Section with Logout Button -->
    <div class="header">
        <h1>Admin Dashboard</h1>
        <a href="logout.php" class="btn btn-logout">Logout</a>
    </div>

    <div class="container mt-5">
        <!-- Row for Add User Button, Search Bar, and Delete Button -->
        <div class="d-flex justify-content-between mb-3">
            <!-- Add User Button -->
            <div>
                <a href="register.php" class="btn btn-blue-no-radius text-white">Add New User</a>
            </div>

            <!-- Search Bar and Delete Button at the right -->
            <div class="d-flex gap-2">
                <form action="dashboard.php" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control" placeholder="Search users..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button class="btn-search" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                <form action="dashboard.php" method="POST" id="user_form">
                    <button type="submit" name="delete" class="btn-trash" onclick="return confirm('Are you sure you want to delete the selected users?')">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        <!-- Table for Users -->
        <table class="table">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select_all"></th>
                    <th>Name / Email</th>
                    <th>Role</th>
                    <th>Last Login</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="5" class="text-center">No user found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><input type="checkbox" name="user_ids[]" value="<?php echo $user['id']; ?>"></td>
                            <td>
                                <?php echo htmlspecialchars($user['name']); ?><br>
                                <small><?php echo htmlspecialchars($user['email']); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                            <td><?php echo htmlspecialchars($user['last_login']); ?></td>
                            <td><?php echo htmlspecialchars($user['notes']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            <a href="?page=1&search=<?php echo urlencode($_GET['search'] ?? ''); ?>" class="<?php echo ($page == 1) ? 'disabled' : ''; ?>">First</a>
            <a href="?page=<?php echo ($page - 1) > 0 ? $page - 1 : 1; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>" class="<?php echo ($page == 1) ? 'disabled' : ''; ?>">Previous</a>
            <span>Page <?php echo $page; ?> of <?php echo $total_pages; ?></span>
            <a href="?page=<?php echo ($page + 1) <= $total_pages ? $page + 1 : $total_pages; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>" class="<?php echo ($page == $total_pages) ? 'disabled' : ''; ?>">Next</a>
            <a href="?page=<?php echo $total_pages; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>" class="<?php echo ($page == $total_pages) ? 'disabled' : ''; ?>">Last</a>
        </div>
    </div>

    <!-- Bootstrap 5 JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // JavaScript to handle the "select all" functionality
        document.getElementById('select_all').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });
    </script>
</body>

</html>