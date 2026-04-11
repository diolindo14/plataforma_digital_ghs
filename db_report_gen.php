<?php
$host = 'sql111.infinityfree.com';
$db   = 'if0_41574650_ghs_sistema';
$user = 'if0_41574650';
$pass = '0svEjAnMHnX';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];

$report = "";
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    $report .= "--- CHECKING TURMAS ---\n";
    $stmt = $pdo->query("SELECT id, nome FROM turmas WHERE nome LIKE '%4T1%'");
    while($row = $stmt->fetch()) {
        $report .= "Turma ID: {$row['id']} | Nome: {$row['nome']}\n";
    }

    $report .= "\n--- CHECKING DISCIPLINES ---\n";
    $stmt = $pdo->query("SELECT id, nome FROM disciplinas WHERE nome LIKE '%Redes%'");
    while($row = $stmt->fetch()) {
        $report .= "Disc ID: {$row['id']} | Nome: {$row['nome']}\n";
    }

    $report .= "\n--- FINDING AVALIACOES FOR ANY REDES ---\n";
    $stmt = $pdo->query("SELECT a.id, a.turma_id, a.disciplina_id, d.nome, t.nome as turma_nome 
                         FROM avaliacoes a 
                         JOIN disciplinas d ON a.disciplina_id = d.id 
                         JOIN turmas t ON a.turma_id = t.id
                         WHERE d.nome LIKE '%Redes%' AND t.nome LIKE '%4T1%'");
    while($row = $stmt->fetch()) {
        $report .= "Aval ID: {$row['id']} | Turma: {$row['turma_nome']} ({$row['turma_id']}) | Disc ID: {$row['disciplina_id']} | Nome: {$row['nome']}\n";
    }

} catch (Exception $e) { $report .= $e->getMessage(); }

file_put_contents("db_report.txt", $report);
echo "Report generated.";
?>
