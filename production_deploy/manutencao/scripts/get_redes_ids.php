<?php
require_once 'core/Database.php';
$db = Database::getInstance();
$stmt = $db->query("SELECT id, nome, codigo FROM disciplinas WHERE nome LIKE '%Redes%' OR codigo LIKE '%RD%'");
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    echo $row['id'] . " | " . $row['codigo'] . " | " . $row['nome'] . PHP_EOL;
}
