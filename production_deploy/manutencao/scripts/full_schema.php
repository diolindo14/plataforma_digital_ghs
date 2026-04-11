<?php
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=ghsespf_db;charset=utf8mb4", "root", "");
    $tables = ['turmas', 'disciplinas', 'horarios', 'professor_disciplina'];
    foreach ($tables as $table) {
        echo "--- TABLE: $table ---\n";
        $stmt = $pdo->query("SHOW COLUMNS FROM $table");
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $col) {
            echo "{$col['Field']} | {$col['Type']} | {$col['Null']}\n";
        }
        echo "\n";
    }
} catch (Exception $e) {
    echo "ERRO: " . $e->getMessage() . "\n";
}
?>
