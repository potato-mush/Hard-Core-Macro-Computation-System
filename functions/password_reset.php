<?php
function checkEmailExists($email, $pdo) {
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_users WHERE email = :email");
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        
        return [
            'status' => 'success',
            'exists' => $count > 0,
            'message' => $count > 0 ? 'Email found' : 'Email not found'
        ];

    } catch (PDOException $e) {
        return [
            'status' => 'error',
            'exists' => false,
            'message' => 'Database error occurred'
        ];
    }
}

function generateResetToken() {
    // Generate a shorter token (16 characters)
    return substr(bin2hex(random_bytes(8)), 0, 16);
}

function sendPasswordResetEmail($email, $pdo) {
    try {
        $emailCheck = checkEmailExists($email, $pdo);
        
        if (!$emailCheck['exists']) {
            return ['status' => 'error', 'message' => 'Email not found in our records'];
        }

        $token = generateResetToken();
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Update user with reset token
        $updateStmt = $pdo->prepare("UPDATE tbl_users SET reset_token = :token, reset_token_expiry = :expiry WHERE email = :email");
        $success = $updateStmt->execute([
            ':token' => $token,
            ':expiry' => $expiry,
            ':email' => $email
        ]);

        if (!$success || $updateStmt->rowCount() === 0) {
            throw new Exception('Failed to update reset token');
        }

        return [
            'status' => 'success',
            'message' => 'Your reset token is shown below. Please copy and use it to reset your password:',
            'token' => $token  // Return the actual token
        ];

    } catch (Exception $e) {
        error_log("Password Reset Error: " . $e->getMessage());
        return [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
    }
}

function validateResetToken($token, $pdo) {
    try {
        // Debug: Log the received token
        error_log("Validating token: " . $token);
        
        // First get the user with this token
        $stmt = $pdo->prepare("SELECT id, reset_token, reset_token_expiry FROM tbl_users WHERE reset_token = :token");
        $stmt->execute([':token' => $token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Debug: Log what we found
        error_log("User data: " . print_r($user, true));
        
        if (!$user) {
            error_log("No user found with token: " . $token);
            return false;
        }
        
        // Check if token has expired
        $expiry = strtotime($user['reset_token_expiry']);
        $now = time();
        
        // Debug: Log time comparison
        error_log("Token expiry: " . date('Y-m-d H:i:s', $expiry));
        error_log("Current time: " . date('Y-m-d H:i:s', $now));
        
        if ($now > $expiry) {
            error_log("Token expired. Expiry: " . date('Y-m-d H:i:s', $expiry));
            return false;
        }
        
        return true;
    } catch (PDOException $e) {
        error_log("Token validation error: " . $e->getMessage());
        return false;
    }
}

function resetPassword($token, $newPassword, $pdo) {
    try {
        // First verify if token is valid
        if (!validateResetToken($token, $pdo)) {
            error_log("Token validation failed during password reset");
            return [
                'status' => 'error',
                'message' => 'Invalid or expired token'
            ];
        }

        // Get user ID from token
        $stmt = $pdo->prepare("SELECT id FROM tbl_users WHERE reset_token = :token");
        $stmt->execute([':token' => $token]);
        $userId = $stmt->fetchColumn();

        if (!$userId) {
            error_log("No user found with token during password reset");
            return [
                'status' => 'error',
                'message' => 'User not found'
            ];
        }

        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the password and clear reset token in a transaction
        $pdo->beginTransaction();
        
        $updateStmt = $pdo->prepare("
            UPDATE tbl_users 
            SET 
                password = :password,
                reset_token = NULL,
                reset_token_expiry = NULL 
            WHERE id = :userId
        ");

        $success = $updateStmt->execute([
            ':password' => $hashedPassword,
            ':userId' => $userId
        ]);

        if ($success && $updateStmt->rowCount() > 0) {
            $pdo->commit();
            error_log("Password successfully reset for user ID: " . $userId);
            return [
                'status' => 'success',
                'message' => 'Password successfully reset'
            ];
        } else {
            $pdo->rollBack();
            error_log("Failed to update password in database");
            return [
                'status' => 'error',
                'message' => 'Failed to update password'
            ];
        }
    } catch (PDOException $e) {
        error_log("Password reset error: " . $e->getMessage());
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        return [
            'status' => 'error',
            'message' => 'Database error occurred'
        ];
    }
}
?>
