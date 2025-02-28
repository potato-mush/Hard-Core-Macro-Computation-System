<?php
header('Content-Type: application/json');
require_once('functions/conn.php');
require_once('functions/password_reset.php');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $action = $_POST['action'] ?? '';
    $token = $_POST['token'] ?? '';

    if (empty($token)) {
        throw new Exception('Token is required');
    }

    if ($action === 'verify') {
        $isValid = validateResetToken($token, $pdo);
        
        // Add more detailed debug info
        $debug = [
            'token_received' => $token,
            'token_length' => strlen($token),
            'validation_result' => $isValid,
            'server_time' => date('Y-m-d H:i:s')
        ];
        
        if (!$isValid) {
            // Check if token exists at all
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_users WHERE reset_token = :token");
            $stmt->execute([':token' => $token]);
            $exists = $stmt->fetchColumn() > 0;
            
            $debug['token_exists'] = $exists;
            if ($exists) {
                // Check if it's expired
                $stmt = $pdo->prepare("SELECT reset_token_expiry FROM tbl_users WHERE reset_token = :token");
                $stmt->execute([':token' => $token]);
                $expiry = $stmt->fetchColumn();
                $debug['token_expiry'] = $expiry;
            }
        }
        
        echo json_encode([
            'status' => $isValid ? 'success' : 'error',
            'message' => $isValid ? 'Token verified' : 'Invalid or expired token',
            'debug' => $debug
        ]);
    } elseif ($action === 'reset') {
        $password = $_POST['password'] ?? '';
        if (empty($password)) {
            throw new Exception('Password is required');
        }
        if (strlen($password) < 8) {
            throw new Exception('Password must be at least 8 characters long');
        }
        
        $result = resetPassword($token, $password, $pdo);
        echo json_encode($result);
    } else {
        throw new Exception('Invalid action');
    }

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>
