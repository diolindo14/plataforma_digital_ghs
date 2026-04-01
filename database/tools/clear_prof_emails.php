<?php
require_once 'core/config.php';
require_once 'core/Database.php';

$db = Database::getInstance();

try {
    // 1. Verificar se a coluna aceita NULL
    $cols = $db->query("SHOW COLUMNS FROM utilizadores LIKE 'email'")->fetch();
    $canBeNull = ($cols['Null'] === 'YES');

    if ($canBeNull) {
        $stmt = $db->prepare("UPDATE utilizadores SET email = NULL WHERE tipo = 'professor'");
    } else {
        // Se NÃO aceitar NULL, usamos um placeholder único baseado no ID para não violar a unicidade
        $stmt = $db->prepare("UPDATE utilizadores SET email = CONCAT('prof_pendente_', id) WHERE tipo = 'professor'");
    }

    $stmt->execute();
    $count = $stmt->rowCount();
    echo "SUCESSO: E-mails de $count professores limpos.";
} catch (Exception $e) {
    echo "ERRO: " . $e->getMessage();
}
