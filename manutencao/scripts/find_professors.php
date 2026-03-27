<?php
require_once 'core/Database.php';
$db = Database::getInstance();
$siglas = ['PI', 'MCG', 'IPM', 'RD2', 'IA', 'MC', 'ES', 'TSI', 'SID'];
$placeholders = str_repeat('?,', count($siglas) - 1) . '?';

$sql = "SELECT p.id as professor_id, u.nome_completo as professor_nome, d.id as disciplina_id, d.codigo as sigla 
        FROM professores p 
        JOIN utilizadores u ON p.utilizador_id = u.id 
        JOIN professor_disciplina pd ON p.id = pd.professor_id 
        JOIN disciplinas d ON pd.disciplina_id = d.id 
        WHERE d.codigo IN ($placeholders)";

$stmt = $db->prepare($sql);
$stmt->execute($siglas);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$mapping = [];
foreach($results as $r) {
    if (!isset($mapping[$r['sigla']])) {
        $mapping[$r['sigla']] = $r;
    }
}

echo json_encode($mapping, JSON_PRETTY_PRINT);
