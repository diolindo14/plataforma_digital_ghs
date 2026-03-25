<?php
require_once 'core/Database.php';
$db = Database::getInstance();
$turma_id = 7;
$stmt = $db->prepare("SELECT h.id, h.dia_semana, h.hora_inicio, h.hora_fim, d.codigo as sigla, d.nome as disciplina_nome, h.sala 
                     FROM horarios h 
                     JOIN disciplinas d ON h.disciplina_id = d.id 
                     WHERE h.turma_id = ? 
                     ORDER BY FIELD(h.dia_semana, 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'), h.hora_inicio");
$stmt->execute([$turma_id]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
file_put_contents('tmp_schedule_dump.json', json_encode($rows, JSON_PRETTY_PRINT));
echo "Dumped " . count($rows) . " rows.\n";
