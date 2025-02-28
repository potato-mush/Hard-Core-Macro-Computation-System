<?php
ob_start();
header('Content-Type: application/json');

try {
    require_once('functions/conn.php');
    require_once('functions/password_reset.php');
    ob_clean();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    if (empty($email)) {
        throw new Exception('Email is required');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format');
    }
    
    $result = isset($_POST['check']) 
        ? checkEmailExists($email, $pdo) 
        : sendPasswordResetEmail($email, $pdo);
    
    if (!isset($result['status'])) {
        throw new Exception('Invalid response from server');
    }

    ob_clean();
    echo json_encode($result);

} catch (Exception $e) {
    ob_clean();
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
exit;
?>
