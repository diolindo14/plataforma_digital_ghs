<?php
require_once 'core/Database.php';
define('DB_HOST', 'localhost');
define('DB_NAME', 'ghsespf_db');
define('DB_USER', 'root');
define('DB_PASS', '');

require_once 'app/models/Horario.php';

$horarioModel = new Horario();
$turma_id = 7; // Known turma id for the student
$gridData = $horarioModel->buildWeeklyGrid($turma_id);

echo "Grid Data:\n";
print_r($gridData);
