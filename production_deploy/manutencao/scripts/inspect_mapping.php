<?php
require_once 'core/Database.php';
$db = Database::getInstance();

$siglas = ['PI', 'MCG', 'IPM', 'RD2', 'IA', 'MC', 'ES', 'TSI', 'SID'];
$placeholders = str_repeat('?,', count($siglas) - 1) . '?';

// List available disciplines and see if they have professors
$sql = "SELECT d.id, d.codigo, d.nome, 
        (SELECT p.id FROM professores p JOIN professor_disciplina pd ON p.id = pd.professor_id WHERE pd.disciplina_id = d.id LIMIT 1) as professor_id
        FROM disciplinas d
        WHERE d.codigo IN ($placeholders)";

$stmt = $db->prepare($sql);
$stmt->execute($siglas);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($results, JSON_PRETTY_PRINT);
