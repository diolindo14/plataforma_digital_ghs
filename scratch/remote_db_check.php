<?php
header('Content-Type: text/plain');
$host = 'sql111.infinityfree.com';
$db   = 'if0_41574650_ghs_sistema';
$user = 'if0_41574650';
$pass = '0svEjAnMHnX';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    echo "✔ DB Connection OK\n";
    
    $tCount = $pdo->query("SELECT COUNT(*) FROM turmas")->fetchColumn();
    echo "Turmas: $tCount\n";
    
    $hCount = $pdo->query("SELECT COUNT(*) FROM horarios")->fetchColumn();
    echo "Horarios: $hCount\n";
    
    $mCount = $pdo->query("SELECT COUNT(*) FROM matriculas")->fetchColumn();
    echo "Matriculas: $mCount\n";
    
} catch (PDOException $e) {
    echo "✘ Connection Failed: " . $e->getMessage();
}
?>
