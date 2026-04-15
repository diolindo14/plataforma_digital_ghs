<?php
require_once 'core/Database.php';
define('DB_HOST', 'localhost');
define('DB_NAME', 'ghsespf_db');
define('DB_USER', 'root');
define('DB_PASS', '');

$db = Database::getInstance();
$stmt = $db->query("SELECT DISTINCT dia_semana, LENGTH(dia_semana) as len FROM horarios");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
