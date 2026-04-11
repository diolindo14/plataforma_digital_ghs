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
    echo "Conexao estabelecida.<br>";

    // Remover avaliações de Fundamentos vinculadas à turma 4T1 (pela string de nome para garantir)
    $q = "DELETE n FROM notas n 
          JOIN avaliacoes a ON n.avaliacao_id = a.id 
          JOIN turmas t ON a.turma_id = t.id
          JOIN disciplinas d ON a.disciplina_id = d.id
          WHERE t.nome LIKE '%4T1%' AND d.nome LIKE '%Fundamentos%'";
    $stmt = $pdo->prepare($q);
    $stmt->execute();
    echo "Notas 'Fundamentos' removidas da 4T1: " . $stmt->rowCount() . "<br>";

    $q2 = "DELETE a FROM avaliacoes a 
           JOIN turmas t ON a.turma_id = t.id
           JOIN disciplinas d ON a.disciplina_id = d.id
           WHERE t.nome LIKE '%4T1%' AND d.nome LIKE '%Fundamentos%'";
    $stmt = $pdo->prepare($q2);
    $stmt->execute();
    echo "Avaliacoes 'Fundamentos' removidas da 4T1: " . $stmt->rowCount() . "<br>";

    $q3 = "DELETE pd FROM professor_disciplina pd 
           JOIN turmas t ON pd.turma_id = t.id
           JOIN disciplinas d ON pd.disciplina_id = d.id
           WHERE t.nome LIKE '%4T1%' AND d.nome LIKE '%Fundamentos%'";
    $stmt = $pdo->prepare($q3);
    $stmt->execute();
    echo "Vinculacao Prof 'Fundamentos' removida da 4T1: " . $stmt->rowCount() . "<br>";
    
    // CASO EXTREMO: Mudar o ID da turma para 9999 (inutilizar) se ainda persistir
    echo "<b>Limpeza por nome concluída.</b>";

} catch (Exception $e) { echo "Erro: " . $e->getMessage(); }
?>
