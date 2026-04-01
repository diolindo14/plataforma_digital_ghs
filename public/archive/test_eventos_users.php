<?php
require_once '../core/config.php';
spl_autoload_register(function ($className) {
    if (file_exists('../core/' . $className . '.php')) require_once '../core/' . $className . '.php';
    if (file_exists('../app/models/' . $className . '.php')) require_once '../app/models/' . $className . '.php';
});

$db = Database::getInstance();
try {
    $q = $db->query("SELECT e.id, e.titulo, e.criado_por, u.id as user_id FROM eventos e LEFT JOIN utilizadores u ON e.criado_por = u.id");
    print_r($q->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
