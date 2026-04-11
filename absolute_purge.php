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
    
    // 1. Get IDs
    $stmt = $pdo->prepare("SELECT e.id FROM estudantes e JOIN utilizadores u ON e.utilizador_id = u.id WHERE u.nome_completo LIKE '%Diosives%'");
    $stmt->execute();
    $sid = $stmt->fetchColumn() ?: 137;

    $dids = $pdo->query("SELECT id FROM disciplinas WHERE nome LIKE '%Fundamentos%'")->fetchAll(PDO::FETCH_COLUMN);
    $ids_str = implode(",", $dids);

    $tids = $pdo->query("SELECT id FROM turmas WHERE nome LIKE '%4T1%'")->fetchAll(PDO::FETCH_COLUMN);
    $t_ids_str = implode(",", $tids);

    if(!empty($ids_str)) {
        // Purge Notas
        $pdo->exec("DELETE FROM notas WHERE estudante_id = $sid AND avaliacao_id IN (SELECT id FROM avaliacoes WHERE disciplina_id IN ($ids_str))");
        
        if(!empty($t_ids_str)) {
            $pdo->exec("DELETE FROM notas WHERE avaliacao_id IN (SELECT id FROM avaliacoes WHERE turma_id IN ($t_ids_str) AND disciplina_id IN ($ids_str))");
            $pdo->exec("DELETE FROM avaliacoes WHERE turma_id IN ($t_ids_str) AND disciplina_id IN ($ids_str)");
            $pdo->exec("DELETE FROM professor_disciplina WHERE turma_id IN ($t_ids_str) AND disciplina_id IN ($ids_str)");
            $pdo->exec("DELETE FROM concordancia_notas WHERE turma_id IN ($t_ids_str) AND disciplina_id IN ($ids_str)");
            $pdo->exec("DELETE FROM frequencias WHERE turma_id IN ($t_ids_str) AND disciplina_id IN ($ids_str)");
            $pdo->exec("DELETE FROM sumarios WHERE turma_id IN ($t_ids_str) AND disciplina_id IN ($ids_str)");
        }
    }

    echo "Purge complete.";

} catch (Exception $e) { echo $e->getMessage(); }
?>
