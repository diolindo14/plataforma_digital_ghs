<?php
require_once 'core/Database.php';
$db = Database::getInstance();
$stmt = $db->query("SHOW COLUMNS FROM notas");
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['Field'] . "\n";
}
