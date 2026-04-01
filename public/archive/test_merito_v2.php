<?php
require_once '../core/config.php';
spl_autoload_register(function ($className) {
    if (file_exists('../core/' . $className . '.php')) require_once '../core/' . $className . '.php';
    if (file_exists('../app/models/' . $className . '.php')) require_once '../app/models/' . $className . '.php';
});

$a = new Academico();
try {
    // Try both 1st and 2nd semester
    echo "Pesquisando Top 10 - 1º Semestre:\n";
    $res1 = $a->getTopBySemestre(1, '2026', 10);
    print_r($res1);
    
    echo "\nPesquisando Top 10 - 2º Semestre:\n";
    $res2 = $a->getTopBySemestre(2, '2026', 10);
    print_r($res2);
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
