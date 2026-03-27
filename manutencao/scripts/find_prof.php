<?php
require_once 'core/Database.php';
$db = Database::getInstance();
$stmt = $db->prepare("SELECT p.id, u.nome_completo FROM professores p JOIN utilizadores u ON p.utilizador_id = u.id WHERE u.nome_completo LIKE :nome");
$stmt->execute([':nome' => '%Domingos%']);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($results as $row) {
    echo "ID: " . $row['id'] . " | Nome: " . $row['nome_completo'] . PHP_EOL;
}
