<?php
session_start();
include 'conn.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not authenticated']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$user_id = $_SESSION['user_id'];
$item_text = $data['itemText'];
$item_type = $data['itemType'];
$is_completed = $data['isCompleted'];
$created_date = date('Y-m-d');

// Check if item exists for today
$check_stmt = $pdo->prepare("SELECT id FROM tbl_todo_items WHERE user_id = ? AND item_text = ? AND created_date = ?");
$check_stmt->execute([$user_id, $item_text, $created_date]);
$existing_item = $check_stmt->fetch();

if ($existing_item) {
    // Update existing item
    $stmt = $pdo->prepare("UPDATE tbl_todo_items SET is_completed = ? WHERE id = ?");
    $stmt->execute([$is_completed, $existing_item['id']]);
} else {
    // Insert new item
    $stmt = $pdo->prepare("INSERT INTO tbl_todo_items (user_id, item_text, item_type, is_completed, created_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $item_text, $item_type, $is_completed, $created_date]);
}

echo json_encode(['success' => true]);
?>
