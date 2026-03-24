<?php
require_once 'core/Database.php';
$db = Database::getInstance();
try {
    $db->query("SELECT 1 FROM leitura_comunicados LIMIT 1");
    echo "TABLE leitura_comunicados EXISTS\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Creating table...\n";
    try {
        $db->exec("CREATE TABLE IF NOT EXISTS leitura_comunicados (
            id INT AUTO_INCREMENT PRIMARY KEY,
            comunicado_id INT NOT NULL,
            utilizador_id INT NOT NULL,
            data_leitura DATETIME,
            UNIQUE KEY (comunicado_id, utilizador_id)
        )");
        echo "Table created successfully.\n";
    } catch (Exception $ex) {
        echo "FAILED to create table: " . $ex->getMessage() . "\n";
    }
}
