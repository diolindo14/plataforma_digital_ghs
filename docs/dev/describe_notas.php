<?php
require_once 'core/Database.php';
$db = Database::getInstance();
$stmt = $db->query("DESCRIBE notas");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
