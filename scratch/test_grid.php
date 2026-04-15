<?php
require_once 'core/Database.php';
define('DB_HOST', 'localhost');
define('DB_NAME', 'ghsespf_db');
define('DB_USER', 'root');
define('DB_PASS', '');

$db = Database::getInstance();

// Simular buildWeeklyGrid para turma 7 local
require_once 'app/models/Horario.php';
$horarioModel = new Horario();
$grid = $horarioModel->buildWeeklyGrid(7);

echo "=== RESULT OF buildWeeklyGrid(7) ===\n";
echo "TEMPOS:\n";
foreach ($grid['tempos'] as $t => $horas) {
    echo "  Tempo $t: {$horas['inicio']} - {$horas['fim']}\n";
}
echo "\nGRID:\n";
foreach ($grid['grid'] as $tempo => $dias) {
    foreach ($dias as $dia => $slot) {
        $sigla = $slot['sigla'] ?? 'N/A';
        $sala = $slot['sala'] ?? 'N/A';
        echo "  T$tempo | $dia | Sigla:$sigla | Sala:$sala\n";
    }
}
echo "\nTotal grid slots: " . count($grid['grid']) . "\n";
echo "Total dias in grid keys: " . array_sum(array_map('count', $grid['grid'])) . "\n";
