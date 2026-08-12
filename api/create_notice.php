<?php
session_start();
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['principal', 'principle', 'faculty'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$title = trim($_POST['title'] ?? '');
$message = trim($_POST['message'] ?? '');
$target_department = !empty($_POST['target_department']) ? $_POST['target_department'] : null;
$target_semester = !empty($_POST['target_semester']) ? $_POST['target_semester'] : null;
$target_subject = !empty($_POST['target_subject']) ? $_POST['target_subject'] : null;

$role = $_SESSION['role'];
if ($role === 'principle') $role = 'principal'; // Normalize role spelling

if (empty($title) || empty($message)) {
    echo json_encode(['status' => 'error', 'message' => 'Title and message are required.']);
    exit;
}

if ($role === 'faculty') {
    if (!$target_semester || !$target_subject) {
        echo json_encode(['status' => 'error', 'message' => 'Faculty must specify target semester and subject.']);
        exit;
    }
}

try {
    $stmt = $pdo->prepare("INSERT INTO notices (title, message, author_role, author_id, target_department, target_semester, target_subject) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $title,
        $message,
        $role,
        $_SESSION['user_id'],
        $target_department,
        $target_semester,
        $target_subject
    ]);
    
    echo json_encode(['status' => 'success', 'message' => 'Notice created successfully.']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error.']);
}
