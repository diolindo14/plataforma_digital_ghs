<?php
require_once 'core/Database.php';
$db = Database::getInstance();
$stmt = $db->query("DESCRIBE concordancia_notas");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_PRETTY_PRINT);
