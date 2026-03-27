<?php
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=ghsespf_db;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "--- TURMAS ---\n";
    foreach ($pdo->query("SELECT id, codigo, nivel, periodo FROM turmas") as $t) {
        echo "ID: {$t['id']} | Código: {$t['codigo']} | Nível: {$t['nivel']} | Período: {$t['periodo']}\n";
    }

    echo "\n--- PROFESSORES ---\n";
    foreach ($pdo->query("SELECT p.id, u.nome_completo FROM professores p JOIN utilizadores u ON p.utilizador_id = u.id") as $p) {
        echo "ID: {$p['id']} | Nome: {$p['nome_completo']}\n";
    }

    echo "\n--- DISCIPLINAS ---\n";
    foreach ($pdo->query("SELECT id, nome, codigo FROM disciplinas") as $d) {
        echo "ID: {$d['id']} | Código: {$d['codigo']} | Nome: {$d['nome']}\n";
    }

    echo "\n--- PROFESSOR_DISCIPLINA ---\n";
    $pd = $pdo->query("SELECT t.codigo as turma, d.nome as disc, u.nome_completo as prof 
                      FROM professor_disciplina pd 
                      JOIN turmas t ON pd.turma_id = t.id 
                      JOIN disciplinas d ON pd.disciplina_id = d.id 
                      JOIN professores p ON pd.professor_id = p.id 
                      JOIN utilizadores u ON p.utilizador_id = u.id");
    foreach ($pd as $row) {
        echo "Turma: {$row['turma']} | Disc: {$row['disc']} | Prof: {$row['prof']}\n";
    }

} catch (Exception $e) {
    echo "ERRO: " . $e->getMessage() . "\n";
}
?>
