<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../app/bootstrap.php';

try {
    $a = new Academico();
    $res = $a->getTopBySemestre('1', '2026/2027', 10);
    print_r($res);
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
} catch (Error $err) {
    echo "Fatal Error: " . $err->getMessage();
}
