<?php
require_once 'core/Database.php';
$db = Database::getInstance();
$turma_id = 7;
$placeholder_prof_id = 12; // Samba Djob

// Limpar horário atual do GHS-4T1
$db->prepare("DELETE FROM horarios WHERE turma_id = ?")->execute([$turma_id]);

// Dados reais (Segunda a Sexta)
$rows = [
    // SEG
    ['dia' => 'Segunda', 'tempo' => 0, 'disc' => 78, 'inicio' => '13:00', 'fim' => '14:30', 'sala' => 'LAB3'],
    ['dia' => 'Segunda', 'tempo' => 1, 'disc' => 43, 'inicio' => '14:35', 'fim' => '16:05', 'sala' => 'LAB1'],
    ['dia' => 'Segunda', 'tempo' => 2, 'disc' => 80, 'inicio' => '16:10', 'fim' => '17:40', 'sala' => 'LAB3'],
    ['dia' => 'Segunda', 'tempo' => 3, 'disc' => 44, 'inicio' => '17:45', 'fim' => '19:15', 'sala' => 'LAB3'],
    
    // TER
    ['dia' => 'Terça', 'tempo' => 0, 'disc' => 77, 'inicio' => '13:00', 'fim' => '14:30', 'sala' => 'LAB1'],
    ['dia' => 'Terça', 'tempo' => 1, 'disc' => 74, 'inicio' => '14:35', 'fim' => '16:05', 'sala' => 'LAB3'],
    ['dia' => 'Terça', 'tempo' => 2, 'disc' => 79, 'inicio' => '16:10', 'fim' => '17:40', 'sala' => 'LAB3'],
    ['dia' => 'Terça', 'tempo' => 3, 'disc' => 75, 'inicio' => '17:45', 'fim' => '19:15', 'sala' => 'LAB3'],
    
    // QUA
    ['dia' => 'Quarta', 'tempo' => 0, 'disc' => 77, 'inicio' => '13:00', 'fim' => '14:30', 'sala' => 'LAB3'],
    ['dia' => 'Quarta', 'tempo' => 1, 'disc' => 43, 'inicio' => '14:35', 'fim' => '16:05', 'sala' => 'LAB2'],
    ['dia' => 'Quarta', 'tempo' => 2, 'disc' => 76, 'inicio' => '16:10', 'fim' => '17:40', 'sala' => 'LAB3'],
    ['dia' => 'Quarta', 'tempo' => 3, 'disc' => 44, 'inicio' => '17:45', 'fim' => '19:15', 'sala' => 'LAB3'],
    
    // QUI
    ['dia' => 'Quinta', 'tempo' => 0, 'disc' => 80, 'inicio' => '13:00', 'fim' => '14:30', 'sala' => 'LAB3'],
    ['dia' => 'Quinta', 'tempo' => 1, 'disc' => 75, 'inicio' => '14:35', 'fim' => '16:05', 'sala' => 'LAB3'],
    ['dia' => 'Quinta', 'tempo' => 2, 'disc' => 79, 'inicio' => '16:10', 'fim' => '17:40', 'sala' => 'LAB3'],
    
    // SEX
    ['dia' => 'Sexta', 'tempo' => 0, 'disc' => 78, 'inicio' => '13:00', 'fim' => '14:30', 'sala' => 'LAB3'],
    ['dia' => 'Sexta', 'tempo' => 1, 'disc' => 76, 'inicio' => '14:35', 'fim' => '16:05', 'sala' => 'LAB3'],
    ['dia' => 'Sexta', 'tempo' => 2, 'disc' => 74, 'inicio' => '16:10', 'fim' => '17:40', 'sala' => 'LAB3'],
];

$stmt = $db->prepare("INSERT INTO horarios (turma_id, professor_id, dia_semana, tempo_aula, disciplina_id, hora_inicio, hora_fim, sala) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

foreach ($rows as $r) {
    if (!$stmt->execute([$turma_id, $placeholder_prof_id, $r['dia'], $r['tempo'], $r['disc'], $r['inicio'], $r['fim'], $r['sala']])) {
        echo "Error inserting " . $r['dia'] . " " . $r['tempo'] . "\n";
    }
}

echo "Successfully updated " . count($rows) . " slots for GHS-4T1.\n";
