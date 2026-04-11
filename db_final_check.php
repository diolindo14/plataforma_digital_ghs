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
    
    $report .= "--- NOTAS ATUAIS DO ALUNO DIOSIVES (137) ---\n";
    $stmt = $pdo->query("SELECT n.id as nota_id, a.id as aval_id, d.id as disc_id, d.nome as disc_nome, t.nome as turma_nome 
                         FROM notas n 
                         JOIN avaliacoes a ON n.avaliacao_id = a.id 
                         JOIN disciplinas d ON a.disciplina_id = d.id 
                         JOIN turmas t ON a.turma_id = t.id
                         WHERE n.estudante_id = 137");
    while($row = $stmt->fetch()) {
        $report .= "Nota: {$row['nota_id']} | Aval: {$row['aval_id']} | Turma: {$row['turma_nome']} | Disc: {$row['disc_nome']} (ID: {$row['disc_id']})\n";
    }

} catch (Exception $e) { $report .= $e->getMessage(); }

file_put_contents("db_report_final.txt", $report);
echo "Final report done.";
?>
