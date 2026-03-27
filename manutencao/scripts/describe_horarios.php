<?php
require_once 'core/Database.php';
$db = Database::getInstance();
$stmt = $db->query("DESCRIBE horarios");
$cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($cols, JSON_PRETTY_PRINT);
