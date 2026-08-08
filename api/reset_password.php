<?php
session_start();
require_once '../config/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

$token = $_POST['token'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($token) || empty($password)) {
    echo json_encode(['status' => 'error', 'message' => 'Token and new password are required.']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, reset_expires FROM users WHERE reset_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch();
    
    if ($user) {
        if (strtotime($user['reset_expires']) < time()) {
            echo json_encode(['status' => 'error', 'message' => 'Reset token has expired.']);
            exit;
        }
        
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        $updateStmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
        $updateStmt->execute([$hashed_password, $user['id']]);
        
        echo json_encode(['status' => 'success', 'message' => 'Password reset successfully. You can now login.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid reset token.']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error.']);
}
