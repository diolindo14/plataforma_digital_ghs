<?php
require_once 'core/config.php';
require_once 'core/Database.php';

try {
    $db = Database::getInstance();
    
    // Check if columns exist
    $stmt = $db->query("SHOW COLUMNS FROM concordancia_notas LIKE 'contador_reclamacoes'");
    if (!$stmt->fetch()) {
        $db->exec("ALTER TABLE concordancia_notas ADD COLUMN contador_reclamacoes INT DEFAULT 1");
        echo "Coluna 'contador_reclamacoes' adicionada.\n";
    } else {
        echo "Coluna 'contador_reclamacoes' já existe.\n";
    }

    $stmt = $db->query("SHOW COLUMNS FROM concordancia_notas LIKE 'bloqueado_admin'");
    if (!$stmt->fetch()) {
        $db->exec("ALTER TABLE concordancia_notas ADD COLUMN bloqueado_admin TINYINT(1) DEFAULT 0");
        echo "Coluna 'bloqueado_admin' adicionada.\n";
    } else {
        echo "Coluna 'bloqueado_admin' já existe.\n";
    }

    echo "Sucesso!";
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
