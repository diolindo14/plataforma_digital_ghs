<?php
require_once 'core/Database.php';
$db = Database::getInstance();

$horarios_data = [
    // [turma_code, dia, tempo, sigla, inicio, fim, sala]
    // GHS-1M1 (ID 10)
    ['1M1', 'Segunda', 0, 'IGE', '07:20', '08:50', 'lab1'],
    ['1M1', 'Segunda', 1, 'QUIM', '09:10', '10:40', 'S1'],
    ['1M1', 'Segunda', 2, 'TI', '10:45', '12:15', 'S1'],
    ['1M1', 'Segunda', 3, 'PORT', '12:20', '13:50', 'S1'],
    ['1M1', 'Terça', 0, 'FIS', '07:20', '08:50', 'S1'],
    ['1M1', 'Terça', 1, 'MAT', '09:10', '10:40', 'S1'],
    ['1M1', 'Terça', 2, 'TI', '10:45', '12:15', 'S1'],
    ['1M1', 'Quarta', 0, 'MAT', '07:20', '08:50', 'S1'],
    ['1M1', 'Quarta', 1, 'FIS', '09:10', '10:40', 'S1'],
    ['1M1', 'Quarta', 2, 'ING', '10:45', '12:15', 'S1'],
    ['1M1', 'Quinta', 0, 'IGE', '07:20', '08:50', 'lab1'],
    ['1M1', 'Quinta', 1, 'APL', '09:10', '10:40', 'S1'],
    ['1M1', 'Quinta', 2, 'GDA', '10:45', '12:15', 'S1'],
    ['1M1', 'Quinta', 3, 'QUIM', '12:20', '13:50', 'S1'],
    ['1M1', 'Sexta', 0, 'MAT', '07:20', '08:50', 'S1'],
    ['1M1', 'Sexta', 1, 'GDA', '09:10', '10:40', 'S1'],
    ['1M1', 'Sexta', 2, 'PORT', '10:45', '12:15', 'S1'],
    ['1M1', 'Sexta', 3, 'ING', '12:20', '13:50', 'S1'],

    // GHS-1T1 (ID 11)
    ['1T1', 'Segunda', 0, 'QUIM', '13:00', '14:30', 'S1'],
    ['1T1', 'Segunda', 1, 'TI', '14:35', '16:05', 'S1'],
    ['1T1', 'Segunda', 2, 'GDA', '16:10', '17:40', 'S1'],
    ['1T1', 'Segunda', 3, 'IGE', '17:45', '19:15', 'LAB1'],
    ['1T1', 'Terça', 1, 'APL', '14:35', '16:05', 'S1'],
    ['1T1', 'Terça', 2, 'IGE', '16:10', '17:40', 'LAB1'],
    ['1T1', 'Terça', 3, 'FIS', '17:45', '19:15', 'S1'],
    ['1T1', 'Quarta', 1, 'TI', '14:35', '16:05', 'S1'],
    ['1T1', 'Quarta', 2, 'MAT', '16:10', '17:40', 'S1'],
    ['1T1', 'Quarta', 3, 'FIS', '17:45', '19:15', 'S1'],
    ['1T1', 'Quinta', 0, 'PORT', '13:00', '14:30', 'S1'],
    ['1T1', 'Quinta', 1, 'GDA', '14:35', '16:05', 'S1'],
    ['1T1', 'Quinta', 2, 'MAT', '16:10', '17:40', 'S1'],
    ['1T1', 'Quinta', 3, 'ING', '17:45', '19:15', 'S1'],
    ['1T1', 'Sexta', 0, 'PORT', '13:00', '14:30', 'S1'],
    ['1T1', 'Sexta', 1, 'QUIM', '14:35', '16:05', 'S1'],
    ['1T1', 'Sexta', 2, 'MAT', '16:10', '17:40', 'S1'],
    ['1T1', 'Sexta', 3, 'ING', '17:45', '19:15', 'S1'],

    // GHS-1N1
    ['1N1', 'Segunda', 0, 'QUIM', '17:45', '19:15', 'S1'],
    ['1N1', 'Terça', 0, 'MAT', '17:45', '19:15', 'SR'],
    ['1N1', 'Quarta', 0, 'PORT', '17:45', '19:15', 'SR'],
    ['1N1', 'Quinta', 0, 'MAT', '17:45', '19:15', 'SR'],
    ['1N1', 'Sexta', 0, 'MAT', '17:45', '19:15', 'SR'],
    ['1N1', 'Segunda', 1, 'IGE', '19:20', '20:50', 'LAB1'],
    ['1N1', 'Terça', 1, 'FIS', '19:20', '20:50', 'S1'],
    ['1N1', 'Quarta', 1, 'FIS', '19:20', '20:50', 'S1'],
    ['1N1', 'Quinta', 1, 'QUIM', '19:20', '20:50', 'S1'],
    ['1N1', 'Sexta', 1, 'ING', '19:20', '20:50', 'S1'],
    ['1N1', 'Segunda', 2, 'TI', '20:55', '22:25', 'S1'],
    ['1N1', 'Terça', 2, 'GDA', '20:55', '22:25', 'S1'],
    ['1N1', 'Quarta', 2, 'IGE', '20:55', '22:25', 'LAB1'],
    ['1N1', 'Quinta', 2, 'APL', '20:55', '22:25', 'S1'],
    ['1N1', 'Sexta', 2, 'TI', '20:55', '22:25', 'S1'],
    ['1N1', 'Segunda', 3, 'GDA', '22:30', '23:59', 'S1'],
    ['1N1', 'Quinta', 3, 'ING', '22:30', '23:59', 'S1'],
    ['1N1', 'Sexta', 3, 'PORT', '22:30', '23:59', 'S1'],

    // GHS-4N1
    ['4N1', 'Segunda', 1, 'IA', '17:45', '19:15', 'BIB'],
    ['4N1', 'Terça', 1, 'PI', '17:45', '19:15', 'BIB'],
    ['4N1', 'Quinta', 1, 'IA', '17:45', '19:15', 'BIB'],
    ['4N1', 'Sexta', 1, 'SID', '17:45', '19:15', 'S3'],
    ['4N1', 'Segunda', 2, 'ES', '19:20', '20:50', 'LAB3'],
    ['4N1', 'Terça', 2, 'TSI', '19:20', '20:50', 'LAB3'],
    ['4N1', 'Quarta', 2, 'SID', '19:20', '20:50', 'LAB3'],
    ['4N1', 'Quinta', 2, 'MC', '19:20', '20:50', 'LAB3'],
    ['4N1', 'Sexta', 2, 'RD2', '19:20', '20:50', 'LAB3'],
    ['4N1', 'Segunda', 3, 'TSI', '20:55', '22:25', 'LAB3'],
    ['4N1', 'Terça', 3, 'RD2', '20:55', '22:25', 'LAB1'],
    ['4N1', 'Quarta', 3, 'MC', '20:55', '22:25', 'LAB3'],
    ['4N1', 'Quinta', 3, 'PI', '20:55', '22:25', 'LAB3'],
    ['4N1', 'Sexta', 3, 'ES', '20:55', '22:25', 'LAB3'],
    ['4N1', 'Segunda', 4, 'IPM', '22:30', '23:59', 'LAB2'],
    ['4N1', 'Terça', 4, 'MCG', '22:30', '23:59', 'LAB3'],
    ['4N1', 'Quarta', 4, 'MCG', '22:30', '23:59', 'LAB3'],
    ['4N1', 'Quinta', 4, 'IPM', '22:30', '23:59', 'LAB3'],
];

// Map Turma IDs
$turma_ids = [
    '1M1' => 10, '1T1' => 11, '1N1' => 12, '4T1' => 7, '4N1' => 18
];

// Map Discipline IDs (from disciplinas.csv)
$disc_ids = [
    'IGE' => 40, 'QUIM' => 55, 'TI' => 56, 'PORT' => 57, 'FIS' => 58, 'MAT' => 59, 'APL' => 61, 'GDA' => 62, 'ING' => 60,
    'PI' => 78, 'RD2' => 30, 'IPM' => 80, 'SID' => 44, 'MCG' => 77, 'IA' => 31, 'TSI' => 79, 'MC' => 75, 'ES' => 76
];

// Clean current horarios
$db->query("DELETE FROM horarios");

// Get teacher assignments from professor_disciplina
$stmtAssig = $db->query("SELECT professor_id, disciplina_id, turma_id FROM professor_disciplina");
$assignments = [];
foreach ($stmtAssig->fetchAll() as $row) {
    $assignments[$row['turma_id'] . '-' . $row['disciplina_id']] = $row['professor_id'];
}

$stmt = $db->prepare("INSERT INTO horarios (turma_id, disciplina_id, professor_id, dia_semana, hora_inicio, hora_fim, sala, tempo_aula) VALUES (?,?,?,?,?,?,?,?)");

foreach ($horarios_data as $h) {
    [$tc, $dia, $tempo, $sigla, $inicio, $fim, $sala] = $h;
    $tid = $turma_ids[$tc] ?? null;
    $did = $disc_ids[$sigla] ?? null;
    
    if (!$tid || !$did) continue;
    
    // Assignment check, use Samba Djob (12) as fallback to avoid integrity error
    $pid = $assignments[$tid . '-' . $did] ?? 12; 
    
    $stmt->execute([$tid, $did, $pid, $dia, $inicio, $fim, $sala, $tempo]);
}

echo "Horários sincronizados!";

echo "Horários sincronizados!";
