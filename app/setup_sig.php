<?php
require_once __DIR__ . '/../core/config.php';
require_once __DIR__ . '/../core/Database.php';

try {
    $db = Database::getInstance();
    $db->exec('ALTER TABLE sumarios ADD COLUMN assinatura_digital VARCHAR(255) NULL');
    echo "Coluna adicionada!";
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
