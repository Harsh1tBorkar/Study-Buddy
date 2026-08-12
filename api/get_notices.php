<?php
session_start();
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
if ($role === 'principle') $role = 'principal';

try {
    if ($role === 'principal' || $role === 'faculty') {
        // Faculty and principals see all notices they created, plus global notices maybe? 
        // For simplicity and since they might want to see what's out there: fetch all notices for principal. 
        // Faculty could see all notices, or just their own + global. Let's return relevant ones.
        if ($role === 'principal') {
            $stmt = $pdo->prepare("SELECT n.*, u.username as author_name FROM notices n JOIN users u ON n.author_id = u.id ORDER BY n.created_at DESC");
            $stmt->execute();
        } else {
            // Faculty sees global, their own, or their department
            $stmt = $pdo->prepare("SELECT n.*, u.username as author_name FROM notices n JOIN users u ON n.author_id = u.id WHERE n.author_id = ? OR (n.target_department IS NULL AND n.target_semester IS NULL AND n.target_subject IS NULL) ORDER BY n.created_at DESC");
            $stmt->execute([$user_id]);
        }
    } else {
        // Students: Global notices, or notices matching their primary subject. 
        // Since students only have `primary_subject` in users table, we try to find their department/semester from `subjects` table.
        $stmt_subj = $pdo->prepare("SELECT department, semester FROM subjects WHERE subject_name = ? LIMIT 1");
        $stmt_subj->execute([$_SESSION['primary_subject'] ?? '']);
        $subj_info = $stmt_subj->fetch(PDO::FETCH_ASSOC);
        
        $department = $subj_info ? $subj_info['department'] : 'UNKNOWN';
        $semester = $subj_info ? $subj_info['semester'] : 'UNKNOWN';
        $primary_subject = $_SESSION['primary_subject'] ?? 'UNKNOWN';

        $sql = "SELECT n.*, u.username as author_name FROM notices n JOIN users u ON n.author_id = u.id 
                WHERE (n.target_department IS NULL AND n.target_semester IS NULL AND n.target_subject IS NULL) 
                OR (n.target_department = ? AND n.target_semester IS NULL)
                OR (n.target_department = ? AND n.target_semester = ? AND n.target_subject IS NULL)
                OR (n.target_subject = ?)
                ORDER BY n.created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$department, $department, $semester, $primary_subject]);
    }
    
    $notices = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['status' => 'success', 'data' => $notices]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error.']);
}
