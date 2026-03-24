<?php
require_once 'core/Database.php';
$db = Database::getInstance();

echo "=== ASSIDUIDADE PROFESSORES ===\n";
$stmt = $db->query("SELECT * FROM assiduidade_professores");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($rows as $row) {
    echo "ID: {$row['id']} | Prof ID: {$row['professor_id']} | Data: {$row['data']} | Status: {$row['status']}\n";
}

echo "\n=== PROFESSORES TABLE ===\n";
$stmt = $db->query("SELECT p.id, p.utilizador_id, u.nome_completo FROM professores p JOIN utilizadores u ON p.utilizador_id = u.id");
$profs = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($profs as $p) {
    echo "Prof ID: {$p['id']} | User ID: {$p['utilizador_id']} | Nome: {$p['nome_completo']}\n";
}

echo "\n=== CHECKING JOINS ===\n";
$stmt = $db->query("
    SELECT ap.id, ap.professor_id, u.nome_completo as prof_nome, t.codigo as turma, d.nome as disc
    FROM assiduidade_professores ap 
    LEFT JOIN professores p ON ap.professor_id = p.id 
    LEFT JOIN utilizadores u ON p.utilizador_id = u.id 
    LEFT JOIN turmas t ON ap.turma_id = t.id 
    LEFT JOIN disciplinas d ON ap.disciplina_id = d.id
");
$joins = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($joins as $j) {
    echo "ID: {$j['id']} | Prof: {$j['prof_nome']} (ID: {$j['professor_id']}) | Turma: {$j['turma']} | Disc: {$j['disc']}\n";
}
