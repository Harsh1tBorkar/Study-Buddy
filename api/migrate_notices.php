<?php
require_once __DIR__ . '/../config/database.php';

try {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS notices (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            author_role ENUM('principal', 'faculty') NOT NULL,
            author_id INT NOT NULL,
            target_department VARCHAR(100) NULL,
            target_semester VARCHAR(50) NULL,
            target_subject VARCHAR(100) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
        )
    ");
    echo "Migration completed: created notices table.";
} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage();
}
