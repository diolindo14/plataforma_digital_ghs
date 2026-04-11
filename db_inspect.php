<?php
$host = 'sql111.infinityfree.com';
$db   = 'if0_41574650_ghs_sistema';
$user = 'if0_41574650';
$pass = '0svEjAnMHnX';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    echo "--- Avaliacoes Turma 7 ---\n";
    $stmt = $pdo->query("SELECT a.id, a.disciplina_id, d.nome as disc_nome FROM avaliacoes a JOIN disciplinas d ON a.disciplina_id = d.id WHERE a.turma_id = 7");
    while($row = $stmt->fetch()) {
        echo "ID: {$row['id']} | DiscID: {$row['disciplina_id']} | Nome: {$row['disc_nome']}\n";
    }

    echo "\n--- Notas Aluno 137 ---\n";
    $stmt = $pdo->query("SELECT n.*, d.nome as disc_nome FROM notas n JOIN avaliacoes a ON n.avaliacao_id = a.id JOIN disciplinas d ON a.disciplina_id = d.id WHERE n.estudante_id = 137");
    while($row = $stmt->fetch()) {
        echo "NotaID: {$row['id']} | AvalID: {$row['avaliacao_id']} | Disc: {$row['disc_nome']} | Nota: {$row['nota']}\n";
    }

} catch (Exception $e) { echo $e->getMessage(); }
