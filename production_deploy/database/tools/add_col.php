<?php
require_once "core/config.php";
require_once "core/Database.php";
$db = Database::getInstance();

try {
    $db->exec("ALTER TABLE utilizadores ADD COLUMN data_aprovacao DATETIME NULL DEFAULT NULL");
    echo "Coluna adicionada.\n";
} catch(Exception $e) {
    echo "Provavelmente já existe. Erro: " . $e->getMessage() . "\n";
}
