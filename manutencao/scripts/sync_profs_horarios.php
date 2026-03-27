<?php
require_once 'core/Database.php';
$db = Database::getInstance();

echo "Sincronizando Professores em Horários...\n";

// Fetch all assignments
$stmt = $db->query("SELECT turma_id, disciplina_id, professor_id FROM professor_disciplina");
$assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);

$updated = 0;
$total = count($assignments);

foreach ($assignments as $a) {
    $tid = $a['turma_id'];
    $did = $a['disciplina_id'];
    $pid = $a['professor_id'];

    $stmtUpd = $db->prepare("UPDATE horarios SET professor_id = :pid WHERE turma_id = :tid AND disciplina_id = :did");
    $stmtUpd->execute([':pid' => $pid, ':tid' => $tid, ':did' => $did]);
    $count = $stmtUpd->rowCount();
    if ($count > 0) {
        $updated += $count;
        echo "  - Turma ID $tid | Disc ID $did -> Prof ID $pid ($count slots)\n";
    }
}

echo "\nConcluído: $updated slots de horários atualizados com professores reais.\n";
