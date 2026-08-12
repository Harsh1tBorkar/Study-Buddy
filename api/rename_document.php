<?php
session_start();
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$document_id = $_POST['document_id'] ?? null;
$new_name = trim($_POST['new_name'] ?? '');

if (!$document_id || empty($new_name)) {
    echo json_encode(['status' => 'error', 'message' => 'Document ID and new name are required.']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE documents SET title = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$new_name, $document_id, $_SESSION['user_id']]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Document renamed successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Document not found or unauthorized.']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error.']);
}
