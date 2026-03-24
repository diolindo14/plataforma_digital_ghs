<?php
session_start();
$_SESSION['user_id'] = 19; // Simulate a professor user ID (from the error log earlier)
require_once 'core/Database.php';
require_once 'app/models/Comunicado.php';

$comModel = new Comunicado();
$comunicados = $comModel->getAll();
if (!empty($comunicados)) {
    $id = $comunicados[0]['id'];
    echo "TESTING WITH COMUNICADO ID: $id\n";
    $res = $comModel->marcarComoLido($id, $_SESSION['user_id']);
    echo "RESULT: " . ($res ? "SUCCESS" : "FAILURE") . "\n";
    
    $db = Database::getInstance();
    $check = $db->query("SELECT * FROM leitura_comunicados WHERE comunicado_id = $id AND utilizador_id = 19")->fetch();
    echo "INSERTED DATA: " . print_r($check, true) . "\n";
} else {
    echo "NO COMUNICADOS FOUND TO TEST.\n";
}
