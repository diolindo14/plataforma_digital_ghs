<?php
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=ghsespf_db;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    function exportToCsv($pdo, $query, $filename) {
        $stmt = $pdo->query($query);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($data)) return;
        $f = fopen($filename, 'w');
        fputcsv($f, array_keys($data[0]));
        foreach ($data as $row) fputcsv($f, $row);
        fclose($f);
        echo "Exportado: $filename (" . count($data) . " linhas)\n";
    }

    exportToCsv($pdo, "SELECT id, codigo, turno FROM turmas", "c:\\xampp\\htdocs\\green\\turmas.csv");
    exportToCsv($pdo, "SELECT id, codigo, nome FROM disciplinas", "c:\\xampp\\htdocs\\green\\disciplinas.csv");
    exportToCsv($pdo, "SELECT p.id, u.nome_completo FROM professores p JOIN utilizadores u ON p.utilizador_id = u.id", "c:\\xampp\\htdocs\\green\\professores.csv");
    exportToCsv($pdo, "SELECT professor_id, disciplina_id, turma_id FROM professor_disciplina", "c:\\xampp\\htdocs\\green\\professor_disciplina.csv");

} catch (Exception $e) {
    echo "ERRO: " . $e->getMessage() . "\n";
}
?>
