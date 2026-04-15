<?php
require_once 'core/Database.php';
define('DB_HOST', 'localhost');
define('DB_NAME', 'ghsespf_db');
define('DB_USER', 'root');
define('DB_PASS', '');

$db = Database::getInstance();

echo "--- HORARIOS FOR TURMA 7 ---\n";
$stmt = $db->prepare("SELECT * FROM horarios WHERE turma_id = 7");
$stmt->execute();
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

echo "\n--- GRADES FOR STUDENT 137 ---\n";
$stmt = $db->prepare("SELECT * FROM notas WHERE estudante_id = 137");
$stmt->execute();
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
