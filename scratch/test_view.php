<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
$_SESSION['user_id'] = 138; // Diosives
$_SESSION['perfil'] = 'Estudante';

require_once 'core/Database.php';
define('DB_HOST', 'localhost');
define('DB_NAME', 'ghsespf_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('URL_ROOT', 'http://localhost/green');

// Simulate controller data
$data = [
    'estudante' => [
        'id' => 137,
        'turma_codigo' => 'GHS-4T1',
        'ano_curso_id' => 4,
        'foto_perfil' => ''
    ],
    'is_approved' => true,
    'matricula_status' => 'Aprovada',
    'media_geral' => 14,
    'desempenho_ac' => 80,
    'faltas_count' => 0,
    'smart_delinquency' => ['is_delinquent' => 1, 'missing_months' => 1],
    'comunicados' => [],
    'horario' => [],
    'gridData' => [],
    'can_renew' => false,
    'next_year' => null
];

$primeiro_nome = 'Diosives';

try {
    // Capture output of view
    ob_start();
    function render() {
        global $data, $primeiro_nome;
        include 'app/views/estudante/dashboard.php';
    }
    
    // Create a mock Controller class with e() method
    class MockController {
        public function e($str) { return htmlspecialchars($str ?? ''); }
    }
    
    // Change this context manually since view uses $this->e()
    $content = file_get_contents('app/views/estudante/dashboard.php');
    $content = str_replace('$this->e(', 'htmlspecialchars(', $content);
    $tmp = tempnam(sys_get_temp_dir(), 'ghs_');
    file_put_contents($tmp, $content);
    include $tmp;
    $output = ob_get_clean();
    echo "View rendered successfully. Length: " . strlen($output);
    
} catch (Throwable $e) {
    ob_end_clean();
    echo "FATAL ERROR: " . $e->getMessage() . " on line " . $e->getLine() . " in " . $e->getFile();
}
