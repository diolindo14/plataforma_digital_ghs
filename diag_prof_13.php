<?php
require_once 'core/Database.php';
$db = Database::getInstance();

echo "### DIAGNOSTICO: Prof 13 em Turma 7 ###\n";

$stmt = $db->query("SELECT pd.disciplina_id, d.nome FROM professor_disciplina pd JOIN disciplinas d ON pd.disciplina_id = d.id WHERE pd.professor_id = 13 AND pd.turma_id = 7");
$pd = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Disciplinas de Prof 13 em Turma 7 (professor_disciplina):\n";
if (empty($pd)) {
    echo "  NENHUMA DISCIPLINA ATRIBUIDA!\n";
} else {
    foreach($pd as $p) {
        echo "  - ID " . $p['disciplina_id'] . ": " . $p['nome'] . "\n";
        
        // Ver slots em horarios
        $stmtH = $db->prepare("SELECT h.id, h.dia_semana, h.hora_inicio, h.hora_fim, h.professor_id, u.nome_completo 
                               FROM horarios h 
                               LEFT JOIN professores prof ON h.professor_id = prof.id
                               LEFT JOIN utilizadores u ON prof.utilizador_id = u.id
                               WHERE h.turma_id = 7 AND h.disciplina_id = ?");
        $stmtH->execute([$p['disciplina_id']]);
        $slots = $stmtH->fetchAll(PDO::FETCH_ASSOC);
        if (empty($slots)) {
            echo "    -> NENHUM HORÁRIO REGISTADO (SLOTS EM FALTA)\n";
        } else {
            foreach($slots as $s) {
                echo "    -> Slot ID " . $s['id'] . " | " . $s['dia_semana'] . " (" . $s['hora_inicio'] . " - " . $s['hora_fim'] . ") | Prof atual: " . $s['professor_id'] . " (" . ($s['nome_completo'] ?? 'NULL') . ")\n";
            }
        }
    }
}
?>
