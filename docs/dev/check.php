<?php
require 'core/Database.php';
$db = Database::getInstance();
$stmt = $db->query('DESCRIBE comunicados_leituras');
if ($stmt) {
    $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $str = "";
    foreach($cols as $col) {
        $str .= $col['Field'] . " - " . $col['Type'] . "\n";
    }
    file_put_contents('columns_leituras.txt', $str);
} else {
    file_put_contents('columns_leituras.txt', 'Table does not exist');
}
