<?php
include 'conn.php';

try {
    // Reset completion status for all items from previous days
    $stmt = $pdo->prepare("UPDATE tbl_todo_items SET is_completed = 0 WHERE created_date < CURRENT_DATE");
    $stmt->execute();
    
    echo "Successfully reset todo items completion status\n";
} catch (PDOException $e) {
    error_log("Error resetting todo items: " . $e->getMessage());
    echo "Error resetting todo items\n";
}
?>
