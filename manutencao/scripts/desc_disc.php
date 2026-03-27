<?php
require_once 'core/Database.php';
$db = Database::getInstance();

// Describe disciplinas table
$stmt = $db->query("DESCRIBE disciplinas");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_PRETTY_PRINT);
