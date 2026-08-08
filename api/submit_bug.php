<?php
session_start();
require_once '../config/database.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized. Please log in.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$severity = $_POST['severity'] ?? '';
$area = $_POST['area'] ?? '';
$description = trim($_POST['description'] ?? '');
$steps_to_reproduce = trim($_POST['steps_to_reproduce'] ?? '');
$browser_info = $_POST['browser_info'] ?? 'Unknown';

if (empty($severity) || empty($area) || empty($description) || empty($steps_to_reproduce)) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    exit;
}

$valid_severities = ['Critical', 'Minor', 'Cosmetic'];
if (!in_array($severity, $valid_severities)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid severity selected.']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO bug_reports (user_id, severity, area, description, steps_to_reproduce, browser_info) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $severity, $area, $description, $steps_to_reproduce, $browser_info]);
    
    echo json_encode(['status' => 'success', 'message' => 'Thank you for the report! Our team will look into it.']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: Could not submit the bug report.']);
}
