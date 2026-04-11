<?php
$host = 'sql111.infinityfree.com';
$db   = 'if0_41574650_ghs_sistema';
$user = 'if0_41574650';
$pass = '0svEjAnMHnX';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    echo "--- CHECKING TURMAS ---\n";
    $stmt = $pdo->query("SELECT id, nome FROM turmas WHERE nome LIKE '%4T1%'");
    while($row = $stmt->fetch()) {
        echo "Turma ID: {$row['id']} | Nome: {$row['nome']}\n";
    }

    echo "\n--- CHECKING DISCIPLINES ---\n";
    $stmt = $pdo->query("SELECT id, nome FROM disciplinas WHERE nome LIKE '%Fundamentos%'");
    while($row = $stmt->fetch()) {
        echo "Disc ID: {$row['id']} | Nome: {$row['nome']}\n";
    }

    echo "\n--- FINDING AVALIACOES FOR ANY REDES ---\n";
    $stmt = $pdo->query("SELECT a.id, a.turma_id, d.nome, t.nome as turma_nome 
                         FROM avaliacoes a 
                         JOIN disciplinas d ON a.disciplina_id = d.id 
                         JOIN turmas t ON a.turma_id = t.id
                         WHERE d.nome LIKE '%Redes%' AND t.nome LIKE '%4T1%'");
    while($row = $stmt->fetch()) {
        echo "Aval ID: {$row['id']} | Turma: {$row['turma_nome']} ({$row['turma_id']}) | Disc: {$row['nome']}\n";
    }

} catch (Exception $e) { echo $e->getMessage(); }
