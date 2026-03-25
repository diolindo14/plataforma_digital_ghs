<?php
require_once __DIR__ . '/core/Database.php';

try {
    $db = Database::getInstance();
    $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    $output = "";
    foreach ($tables as $table) {
        $output .= "--- TABLE: $table ---\n";
        $columns = $db->query("DESCRIBE $table")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($columns as $column) {
            $output .= "{$column['Field']} | {$column['Type']} | {$column['Null']} | {$column['Key']} | {$column['Default']} | {$column['Extra']}\n";
        }
        $output .= "\n";
    }
    
    file_put_contents(__DIR__ . '/full_db_metadata.txt', $output);
    echo "Metadados extraídos para full_db_metadata.txt\n";
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
