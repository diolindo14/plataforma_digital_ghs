<?php
require 'core/Database.php';
$pdo = Database::getInstance();

$sql = file_get_contents('seed_horarios_v2.sql');
// Extrai apenas os valores para simular as queries
preg_match_all("/\(\d+,\s*\d+,\s*'[^']+',\s*'[^']+',\s*'[^']+',\s*'[^']+',\s*\d+\)/", $sql, $matches);

$pdo->query("DELETE FROM horarios WHERE turma_id IN (10, 11, 12, 13, 14, 15, 16, 17, 9, 18, 7, 19, 20)");

foreach ($matches[0] as $match) {
    $query = "INSERT INTO horarios (turma_id, disciplina_id, dia_semana, hora_inicio, hora_fim, sala, tempo_aula) VALUES $match";
    try {
        $pdo->query($query);
    } catch (PDOException $e) {
        echo "Error on insert: $match\n";
        echo $e->getMessage() . "\n";
        break; // Stop at first error
    }
}
echo "Done testing inserts.\n";
