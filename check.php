<?php
require_once 'core/config.php';
require_once 'core/Database.php';

$db = Database::getInstance();
$total = $db->query("SELECT COUNT(*) FROM estudantes")->fetchColumn();
echo "TOTAL: " . $total . "\n";

$turmas = $db->query("SELECT t.codigo, COUNT(m.id) as total FROM turmas t LEFT JOIN matriculas m ON t.id = m.turma_id GROUP BY t.id")->fetchAll(PDO::FETCH_ASSOC);
print_r($turmas);

$users = $db->query("SELECT tipo, COUNT(*) as c FROM utilizadores GROUP BY tipo")->fetchAll(PDO::FETCH_ASSOC);
print_r($users);
