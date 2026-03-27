<?php
require_once 'core/Database.php';
$db = Database::getInstance();
$stmt = $db->query("SELECT id, codigo FROM disciplinas ORDER BY codigo");
$discs = $stmt->fetchAll(PDO::FETCH_ASSOC);
$map = [];
foreach($discs as $d) {
    $map[$d['codigo']] = $d['id'];
}
echo json_encode($map, JSON_PRETTY_PRINT);
