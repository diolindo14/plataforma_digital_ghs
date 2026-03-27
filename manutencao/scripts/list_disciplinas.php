<?php
require_once 'core/Database.php';
$db = Database::getInstance();
$stmt = $db->query("SELECT id, nome, codigo FROM disciplinas ORDER BY id");
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    echo $row['id'] . " | " . $row['codigo'] . " | " . $row['nome'] . PHP_EOL;
}
