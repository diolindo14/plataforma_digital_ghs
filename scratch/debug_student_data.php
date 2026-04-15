<?php
require_once 'core/config.php';
spl_autoload_register(function ($className) {
    $paths = [
        'core/' . $className . '.php',
        'app/models/' . $className . '.php',
        'app/helpers/' . $className . '.php'
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

$db = Database::getInstance();

echo "--- Students with access to portal (level=aluno) ---\n";
$stmt = $db->query("SELECT u.id, u.nome_completo, e.id as estudante_id FROM utilizadores u JOIN estudantes e ON u.id = e.utilizador_id WHERE u.nivel_acesso = 'aluno' LIMIT 5");
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($students as $student) {
    echo "\nTesting for Student: {$student['nome_completo']} (ID: {$student['id']}, EstID: {$student['estudante_id']})\n";
    
    // Check enrollment
    $stmtMat = $db->prepare("
        SELECT m.id, m.status, m.turma_id, t.codigo as turma_codigo 
        FROM matriculas m 
        LEFT JOIN turmas t ON m.turma_id = t.id 
        WHERE m.estudante_id = :id 
        ORDER BY m.id DESC LIMIT 1
    ");
    $stmtMat->execute([':id' => $student['estudante_id']]);
    $mat = $stmtMat->fetch(PDO::FETCH_ASSOC);
    
    if (!$mat) {
        echo "NO ENROLLMENT RECORD FOUND\n";
        continue;
    }
    
    echo "Latest Enrollment: ID={$mat['id']}, Status={$mat['status']}, TurmaID=" . ($mat['turma_id'] ?? 'NULL') . " ({$mat['turma_codigo']})\n";
    
    // Test Schedule Grid
    if ($mat['turma_id']) {
        $horarioModel = new Horario();
        $grid = $horarioModel->buildWeeklyGrid($mat['turma_id']);
        echo "Schedule Grid: " . (empty($grid['grid']) ? "EMPTY" : count($grid['grid'], COUNT_RECURSIVE) . " items found") . "\n";
    } else {
        echo "Schedule Grid: SKIP (No TurmaID)\n";
    }
    
    // Test Grades
    $academicoModel = new Academico();
    $grades = $academicoModel->getGradesByStudent($student['estudante_id']);
    echo "Grades: " . (empty($grades) ? "EMPTY" : count($grades) . " disciplines found") . "\n";
}
