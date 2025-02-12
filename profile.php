<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="assets/styles/styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="profile-container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
        <p>Email: <?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
        <p>Role: <?php echo htmlspecialchars($_SESSION['user_role']); ?></p>
        <!-- Add more profile information as needed -->
        <form action="functions/logout.php" method="POST">
            <button type="submit">Logout</button>
        </form>
    </div>
</body>
</html>
