<?php
require_once '../core/config.php';
spl_autoload_register(function ($className) {
    $paths = [
        '../core/' . $className . '.php',
        '../app/models/' . $className . '.php',
        '../app/helpers/' . $className . '.php'
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

$db = Database::getInstance();
$q = $db->query("DESCRIBE notas");
print_r($q->fetchAll(PDO::FETCH_ASSOC));
$q = $db->query("DESCRIBE avaliacoes");
print_r($q->fetchAll(PDO::FETCH_ASSOC));
