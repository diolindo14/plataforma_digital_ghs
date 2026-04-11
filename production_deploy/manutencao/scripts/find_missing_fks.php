<?php
require 'core/Database.php';
$pdo = Database::getInstance();
$sql = file_get_contents('seed_horarios_v2.sql');
preg_match_all('/\(\d+,\s*(\d+),/', $sql, $matches);
$disciplinas_file = array_unique($matches[1]);
$stmt = $pdo->query('SELECT id FROM disciplinas');
$disciplinas_db = $stmt->fetchAll(PDO::FETCH_COLUMN);
$missing = array_diff($disciplinas_file, $disciplinas_db);
echo "Missing Disciplinas:\n";
print_r($missing);

preg_match_all('/\((\d+),\s*\d+,/', $sql, $matches2);
$turmas_file = array_unique($matches2[1]);
$stmt2 = $pdo->query('SELECT id FROM turmas');
$turmas_db = $stmt2->fetchAll(PDO::FETCH_COLUMN);
$missing2 = array_diff($turmas_file, $turmas_db);
echo "Missing Turmas:\n";
print_r($missing2);
