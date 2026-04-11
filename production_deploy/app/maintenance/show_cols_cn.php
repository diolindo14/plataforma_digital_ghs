<?php
require_once 'core/Database.php';
$db = Database::getInstance();
$stmt = $db->query("SHOW COLUMNS FROM concordancia_notas");
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['Field'] . " (" . $row['Type'] . ")\n";
}
