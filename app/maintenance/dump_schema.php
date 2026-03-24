<?php
require_once 'core/Database.php';
$db = Database::getInstance();

function getTableSchema($db, $table) {
    try {
        $stmt = $db->query("DESCRIBE $table");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return ["error" => $e->getMessage()];
    }
}

$schema = [
    'utilizadores' => getTableSchema($db, 'utilizadores'),
    'estudantes' => getTableSchema($db, 'estudantes'),
    'professores' => getTableSchema($db, 'professores')
];

file_put_contents('schema_dump.json', json_encode($schema, JSON_PRETTY_PRINT));
echo "Schema dumped to schema_dump.json\n";
