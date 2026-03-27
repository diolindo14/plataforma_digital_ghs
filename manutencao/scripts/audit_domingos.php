<?php
require_once 'core/Database.php';
$db = Database::getInstance();

echo "Auditoria para Professor 13 (Domingos Correia)\n";

// 1. Get assignments
$stmt = $db->prepare("SELECT pd.turma_id, t.codigo as turma, pd.disciplina_id, d.nome as disciplina 
                      FROM professor_disciplina pd 
                      JOIN turmas t ON pd.turma_id = t.id 
                      JOIN disciplinas d ON pd.disciplina_id = d.id 
                      WHERE pd.professor_id = 13");
$stmt->execute();
$assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Atribuições em professor_disciplina:\n";
foreach($assignments as $a) {
    echo "  - " . $a['turma'] . " | " . $a['disciplina'] . " (T:" . $a['turma_id'] . " D:" . $a['disciplina_id'] . ")\n";
    
    // 2. Check if in horarios
    $stmtH = $db->prepare("SELECT id, professor_id FROM horarios WHERE turma_id = :tid AND disciplina_id = :did");
    $stmtH->execute([':tid' => $a['turma_id'], ':did' => $a['disciplina_id']]);
    $slots = $stmtH->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($slots)) {
        echo "    !!! SEM HORÁRIO DEFINIDO NA TABELA 'horarios' !!!\n";
    } else {
        foreach($slots as $s) {
            if ($s['professor_id'] != 13) {
                echo "    !!! PROFESSOR INCORRETO EM 'horarios' ID " . $s['id'] . " (Atual: " . $s['professor_id'] . ")\n";
            } else {
                echo "    OK: Horário ID " . $s['id'] . " corretamente atribuído.\n";
            }
        }
    }
}
echo "\nFim da Auditoria.\n";
