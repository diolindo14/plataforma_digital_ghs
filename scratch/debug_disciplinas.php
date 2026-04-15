<?php
require_once 'core/Database.php';
define('DB_HOST', 'localhost');
define('DB_NAME', 'ghsespf_db');
define('DB_USER', 'root');
define('DB_PASS', '');

$db = Database::getInstance();
$stmt = $db->query("SELECT id, nome FROM disciplinas WHERE id IN (78,43,80,44,77,74,79,75,76)");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
