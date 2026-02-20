<?php
require_once __DIR__ . '/app/Core/Database.php';
use App\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    $sql = "ALTER TABLE users ADD COLUMN profile_image VARCHAR(255) NULL AFTER email";
    $db->exec($sql);
    echo "Successfully added profile_image column to users table.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
