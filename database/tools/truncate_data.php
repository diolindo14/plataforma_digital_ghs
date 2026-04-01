<?php
require_once "core/config.php";
require_once "core/Database.php";
$db = Database::getInstance();

$tablesToTruncate = [
    'notas',
    'concordancia_notas',
    'frequencias',
    'sumarios',
    'pagamentos',
    'logs',
    'mensagens',
    'certificados_merito',
    // We shouldn't clear 'utilizadores', 'estudantes', 'professores', 'disciplinas', 'turmas', 'horarios', 'matriculas' based on prompt, mostly notes and tracking.
];

foreach ($tablesToTruncate as $t) {
    try {
        $db->exec("DELETE FROM $t WHERE 1=1");
        echo "Deleted $t\n";
    } catch (Exception $e) {
        echo "Could not truncate $t: " . $e->getMessage() . "\n";
    }
}
echo "Done.";
