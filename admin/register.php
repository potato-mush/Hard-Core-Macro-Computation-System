<?php
// Include database connection
include('db.php');

// Start the session
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if username already exists
        $stmt = $pdo->prepare("SELECT * FROM tbl_admin WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $existing_user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing_user) {
            $error = "Username is already taken.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Prepare the SQL statement to insert the new admin
            $stmt = $pdo->prepare("INSERT INTO tbl_admin (username, password) VALUES (:username, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashed_password);

            if ($stmt->execute()) {
                // Redirect to login page after successful registration
                $_SESSION['success'] = "Admin registered successfully! Please log in.";
                header('Location: login.php');
                exit();
            } else {
                $error = "An error occurred. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex justify-content-center align-items-center" style="height: 100vh; background-color: #f8f9fa;">

    <div class="card" style="width: 18rem;">
        <div class="card-body">
            <h5 class="card-title text-center">Admin Registration</h5>
            <form action="register.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
                <?php if (isset($error)): ?>
                    <div class="mt-3 alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                <?php elseif (isset($_SESSION['success'])): ?>
                    <div class="mt-3 alert alert-success">
                        <?php echo $_SESSION['success'];
                        unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Bootstrap 5 JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>