<?php
$host = 'sql111.infinityfree.com';
$db   = 'if0_41574650_ghs_sistema';
$user = 'if0_41574650';
$pass = '0svEjAnMHnX';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "Conexão OK.<br>";

    // ID 25 = Redes Digitais — Fundamentos
    // Turma 7 = 4T1
    
    // 1. Eliminar notas vinculadas a avaliações erradas desta turma
    $q1 = "DELETE n FROM notas n 
           JOIN avaliacoes a ON n.avaliacao_id = a.id 
           WHERE a.turma_id = 7 AND a.disciplina_id = 25";
    $stmt = $pdo->prepare($q1);
    $stmt->execute();
    echo "Notas removidas: " . $stmt->rowCount() . "<br>";

    // 2. Eliminar as avaliações em si
    $q2 = "DELETE FROM avaliacoes WHERE turma_id = 7 AND disciplina_id = 25";
    $stmt = $pdo->prepare($q2);
    $stmt->execute();
    echo "Avaliações removidas: " . $stmt->rowCount() . "<br>";

    // 3. Eliminar vinculação do professor
    $q3 = "DELETE FROM professor_disciplina WHERE turma_id = 7 AND disciplina_id = 25";
    $stmt = $pdo->prepare($q3);
    $stmt->execute();
    echo "Vinculação Professor removida: " . $stmt->rowCount() . "<br>";

    // 4. Limpar concordancia (disputas)
    $q4 = "DELETE FROM concordancia_notas WHERE turma_id = 7 AND disciplina_id = 25";
    $stmt = $pdo->prepare($q4);
    $stmt->execute();
    echo "Disputas removidas: " . $stmt->rowCount() . "<br>";

    echo "<b>Purga da disciplina 'Fundamentos' na 4T1 concluída.</b>";

} catch (Exception $e) { echo "Erro: " . $e->getMessage(); }
?>
