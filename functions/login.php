<?php
include('conn.php');
session_start();

function login($email, $password, $pdo) {
    // Prepare SQL statement to prevent SQL injection
    $stmt = $pdo->prepare("SELECT *, DATE_FORMAT(expiration_date, '%Y-%m-%d') as formatted_expiration_date FROM tbl_users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Fetch user from the database
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Check if membership has expired
        if ($user['expiration_date'] && strtotime($user['expiration_date']) < time()) {
            return 'Your membership has expired. Please renew your membership to continue.';
        }

        // Verify the hashed password
        if (password_verify($password, $user['password'])) {
            // Password is correct, set session variables
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['expiration_date'] = $user['formatted_expiration_date'];

            // Store user_id in localStorage
            echo "<script>localStorage.setItem('user_id', '{$user['id']}');</script>";

            // Update last login time
            $stmt = $pdo->prepare("UPDATE tbl_users SET last_login = NOW() WHERE id = :id");
            $stmt->bindParam(':id', $user['id']);
            $stmt->execute();

            // Redirect to index.php
            header('Location: index.php');
            exit();
        } else {
            // Invalid password
            return 'Invalid password.';
        }
    } else {
        // Invalid email
        return 'Invalid email.';
    }
}

function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true;
}
?>
