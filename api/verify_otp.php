<?php
session_start();
require_once '../config/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

$email = trim($_POST['email'] ?? '');
$otp = trim($_POST['otp'] ?? '');

if (empty($email) || empty($otp)) {
    echo json_encode(['status' => 'error', 'message' => 'Email and OTP are required.']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND otp_code = ?");
    $stmt->execute([$email, $otp]);
    $user = $stmt->fetch();
    
    if ($user) {
        if (strtotime($user['otp_expires']) < time()) {
            echo json_encode(['status' => 'error', 'message' => 'OTP has expired.']);
            exit;
        }
        
        // Clear OTP
        $updateStmt = $pdo->prepare("UPDATE users SET otp_code = NULL, otp_expires = NULL WHERE id = ?");
        $updateStmt->execute([$user['id']]);
        
        // Log in user
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['primary_subject'] = $user['primary_subject'] ?? null;
        
        if ($user['role'] === 'faculty') {
            $subStmt = $pdo->prepare("SELECT subject FROM faculty_additional_subjects WHERE user_id = ?");
            $subStmt->execute([$user['id']]);
            $_SESSION['additional_subjects'] = $subStmt->fetchAll(PDO::FETCH_COLUMN);
        } else {
            $_SESSION['additional_subjects'] = [];
        }
        
        echo json_encode(['status' => 'success', 'message' => 'Login successful.', 'role' => $user['role']]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid OTP.']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error.']);
}
