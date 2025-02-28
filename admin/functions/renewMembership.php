<?php
include('db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $renewal_type = $_POST['renewal_type'];

    // Determine renewal duration based on membership type
    switch($renewal_type) {
        case 'Premium':
            $renewal_months = 6;
            $new_role = 'Premium Membership';
            break;
        case 'Standard':
            $renewal_months = 3;
            $new_role = 'Standard Membership';
            break;
        case 'Basic':
            $renewal_months = 1;
            $new_role = 'Basic Membership';
            break;
        default:
            $_SESSION['error'] = "Invalid membership type";
            header('Location: ../dashboard.php?section=users');
            exit();
    }

    try {
        // Get current expiration date
        $stmt = $pdo->prepare("SELECT expiration_date FROM tbl_users WHERE id = ?");
        $stmt->execute([$user_id]);
        $current_expiration = $stmt->fetchColumn();

        // Calculate new expiration date
        $today = date('Y-m-d');
        if (strtotime($current_expiration) < strtotime($today)) {
            $new_expiration = date('Y-m-d', strtotime($today . " +{$renewal_months} months"));
        } else {
            $new_expiration = date('Y-m-d', strtotime($current_expiration . " +{$renewal_months} months"));
        }

        // Update the expiration date and role
        $stmt = $pdo->prepare("UPDATE tbl_users SET expiration_date = ?, role = ? WHERE id = ?");
        $stmt->execute([$new_expiration, $new_role, $user_id]);

        $_SESSION['message'] = "Membership renewed successfully to {$new_role}!";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error renewing membership: " . $e->getMessage();
    }

    header('Location: ../dashboard.php?section=users');
    exit();
}
?>
