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
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "Conexão OK.<br>\n";

    $queries = [
        // Migrate from ID 30 to 43 (Done before, but re-run)
        "UPDATE professor_disciplina SET disciplina_id = 43 WHERE turma_id = 7 AND disciplina_id = 30",
        "UPDATE avaliacoes SET disciplina_id = 43 WHERE turma_id = 7 AND disciplina_id = 30",
        "UPDATE concordancia_notas SET disciplina_id = 43 WHERE turma_id = 7 AND disciplina_id = 30",
        "UPDATE frequencias SET disciplina_id = 43 WHERE turma_id = 7 AND disciplina_id = 30",
        "UPDATE sumarios SET disciplina_id = 43 WHERE turma_id = 7 AND disciplina_id = 30",
        
        // Migrate from ID 25 (Fundamentos) to 43 (Sistemas e Serviços) for Turma 7
        "UPDATE professor_disciplina SET disciplina_id = 43 WHERE turma_id = 7 AND disciplina_id = 25",
        "UPDATE avaliacoes SET disciplina_id = 43 WHERE turma_id = 7 AND disciplina_id = 25",
        "UPDATE concordancia_notas SET disciplina_id = 43 WHERE turma_id = 7 AND disciplina_id = 25",
        "UPDATE frequencias SET disciplina_id = 43 WHERE turma_id = 7 AND disciplina_id = 25",
        "UPDATE sumarios SET disciplina_id = 43 WHERE turma_id = 7 AND disciplina_id = 25"
    ];

    foreach ($queries as $q) {
        $stmt = $pdo->prepare($q);
        $stmt->execute();
        echo "Executado: $q (Linhas afetadas: " . $stmt->rowCount() . ")<br>\n";
    }

    echo "<b>Patches aplicados com sucesso!</b>";

} catch (\PDOException $e) {
    echo "Erro na conexao: " . $e->getMessage();
}
?>
