<?php
require_once 'core/Database.php';
$db = Database::getInstance();
$stmt = $db->prepare("SELECT h.dia_semana, t.codigo as turma, d.nome as disciplina, h.hora_inicio, h.hora_fim
                      FROM horarios h 
                      JOIN turmas t ON h.turma_id = t.id 
                      JOIN disciplinas d ON h.disciplina_id = d.id 
                      WHERE h.professor_id = 13
                      ORDER BY FIELD(h.dia_semana, 'Segunda','Terça','Quarta','Quinta','Sexta','Sábado'), h.hora_inicio");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (empty($results)) {
    echo "NENHUM HORÁRIO ENCONTRADO PARA O PROFESSOR 13.\n";
} else {
    foreach($results as $row) {
        echo $row['dia_semana'] . " | " . $row['turma'] . " | " . $row['disciplina'] . " (" . substr($row['hora_inicio'],0,5) . " - " . substr($row['hora_fim'],0,5) . ")\n";
    }
}
echo "\nTotal: " . count($results) . " sessões.\n";
