<?php
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=ghsespf_db;charset=utf8mb4", "root", "");
    echo "CONEXAO OK!\n";
    $res = $pdo->query("SELECT COUNT(*) as total FROM turmas")->fetch();
    echo "Total de turmas: " . $res['total'] . "\n";
} catch (Exception $e) {
    echo "ERRO: " . $e->getMessage() . "\n";
}
?>
