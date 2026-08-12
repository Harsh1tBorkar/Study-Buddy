<?php
require_once __DIR__ . '/../config/database.php';

try {
    // Create subjects table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS subjects (
            id INT AUTO_INCREMENT PRIMARY KEY,
            department VARCHAR(100) NOT NULL,
            semester VARCHAR(50) NOT NULL,
            subject_name VARCHAR(100) NOT NULL,
            UNIQUE KEY (department, semester, subject_name)
        )
    ");

    // Clear existing to avoid duplicates if run multiple times
    $pdo->exec("TRUNCATE TABLE subjects");

    // Insert mapping
    $data = [
        ['Computer Engineering', 'FY - BTECH - SEM 1', 'Physics'],
        ['Computer Engineering', 'FY - BTECH - SEM 2', 'EM-2'],
        ['Computer Engineering', 'SY - BTECH - SEM 3', 'FCPP'],
        ['Computer Engineering', 'SY - BTECH - SEM 4', 'DSDA'],
        ['Computer Engineering', 'TY - BTECH - SEM 5', 'FCSN'],
        ['Information Technology', 'SY - BTECH - SEM 3', 'Data Structures'],
        ['Information Technology', 'SY - BTECH - SEM 4', 'Computer Networks']
    ];

    $stmt = $pdo->prepare("INSERT INTO subjects (department, semester, subject_name) VALUES (?, ?, ?)");
    foreach ($data as $row) {
        $stmt->execute($row);
    }

    echo "Migration completed successfully.";
} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage();
}
