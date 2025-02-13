<?php
// Include database connection file
include('db.php');

// Start the session
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL statement to prevent SQL injection
    $stmt = $pdo->prepare("SELECT * FROM tbl_admin WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Fetch user from the database
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verify the hashed password
        if (password_verify($password, $user['password'])) {
            // Password is correct, set session and redirect to dashboard
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $user['username'];
            header('Location: dashboard.php');
            exit();
        } else {
            // Invalid password
            $error = 'Invalid password.';
        }
    } else {
        // Invalid username
        $error = 'Invalid username.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex justify-content-center align-items-center" style="height: 100vh; background-color: #f8f9fa;">

    <div class="card" style="width: 18rem;">
        <div class="card-body">
            <h5 class="card-title text-center">Admin Login</h5>
            <form action="index.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
                <?php if (isset($error)): ?>
                    <div class="mt-3 alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Bootstrap 5 JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>