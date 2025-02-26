<?php
session_start();
include 'conn.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not authenticated']);
    exit();
}

$user_id = $_SESSION['user_id'];
$created_date = date('Y-m-d');

$stmt = $pdo->prepare("SELECT item_text, item_type, is_completed FROM tbl_todo_items WHERE user_id = ? AND created_date = ?");
$stmt->execute([$user_id, $created_date]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['items' => $items]);
?>
