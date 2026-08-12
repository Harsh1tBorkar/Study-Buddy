<?php
require_once __DIR__ . '/../config/database.php';

try {
    $pdo->exec("ALTER TABLE documents ADD COLUMN is_deleted TINYINT DEFAULT 0");
    echo "Migration completed: added is_deleted column to documents table.";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "Migration completed: is_deleted column already exists.";
    } else {
        echo "Migration failed: " . $e->getMessage();
    }
}
