<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

$department = trim($_GET['department'] ?? '');
$semester = trim($_GET['semester'] ?? '');

if (empty($department) || empty($semester)) {
    echo json_encode(['status' => 'error', 'message' => 'Department and semester are required.']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT subject_name FROM subjects WHERE department = ? AND semester = ? ORDER BY subject_name ASC");
    $stmt->execute([$department, $semester]);
    $subjects = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode(['status' => 'success', 'data' => $subjects]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error.']);
}
