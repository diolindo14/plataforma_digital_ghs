<?php
require_once "core/config.php";
require_once "core/Database.php";
require_once "app/Models/Academico.php";

$db = Database::getInstance();
$ac = new Academico();

try {
    $ac->getRankingEscola(3);
    echo "Ranking Escola OK\n";
} catch(Exception $e) {
    echo "Ranking Escola Erro: " . $e->getMessage() . "\n";
}

try {
    $ac->getRankingByNivel();
    echo "Ranking Nivel OK\n";
} catch(Exception $e) {
    echo "Ranking Nivel Erro: " . $e->getMessage() . "\n";
}

try {
    $ac->getTopBySemestre(1, 2);
    echo "Top by semestre OK\n";
} catch(Exception $e) {
    echo "Top by semestre Erro: " . $e->getMessage() . "\n";
}
