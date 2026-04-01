<?php
require_once '../core/config.php';
spl_autoload_register(function ($className) {
    if (file_exists('../core/' . $className . '.php')) require_once '../core/' . $className . '.php';
});
$db = Database::getInstance();
$q = $db->query("SELECT DISTINCT ano_letivo FROM avaliacoes");
print_r($q->fetchAll(PDO::FETCH_ASSOC));
