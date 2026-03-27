<?php
require_once 'core/Database.php';
$db = Database::getInstance();

echo "=== TURMAS ===\n";
$stmt = $db->query("SELECT id, codigo FROM turmas ORDER BY id");
$turmas = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($turmas as $t) {
    echo $t['id'] . "\t" . $t['codigo'] . "\n";
}

echo "\n=== DISCIPLINAS ===\n";
$stmt = $db->query("SELECT id, codigo FROM disciplinas ORDER BY id");
$discs = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($discs as $d) {
    echo $d['id'] . "\t" . $d['codigo'] . "\n";
}
