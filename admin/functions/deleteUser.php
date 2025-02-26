<?php
// Include the database connection
include('db.php');

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: index.php');
    exit();
}

// Handle the delete action (if delete button is pressed)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    if (isset($_POST['user_ids']) && !empty($_POST['user_ids'])) {
        $user_ids = $_POST['user_ids'];

        // Fetch the images of the users to be deleted
        $placeholders = implode(',', array_fill(0, count($user_ids), '?'));
        $stmt = $pdo->prepare("SELECT image FROM tbl_users WHERE id IN ($placeholders)");
        $stmt->execute($user_ids);
        $images = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Delete selected users from the database
        $stmt = $pdo->prepare("DELETE FROM tbl_users WHERE id IN ($placeholders)");
        $stmt->execute($user_ids);

        // Delete the images from the uploads folder
        foreach ($images as $image) {
            if ($image !== 'default.jpg') {
                $imagePath = 'uploads/' . $image;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        }

        // Reload the page to reflect changes
        header('Location: dashboard.php');
        exit();
    }
}
?>
