<?php
define('URL_ROOT', 'http://localhost/green');
require_once 'app/config/config.php';
require_once 'app/core/Database.php';

$db = Database::getInstance();
$stmt = $db->query('SELECT id, email, status, tipo, data_criacao, data_aprovacao FROM utilizadores ORDER BY id DESC LIMIT 5');
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "--- ULTIMOS 5 UTILIZADORES ---\n";
foreach($users as $u) {
    echo "ID: {$u['id']} | Email: {$u['email']} | Status: {$u['status']} | Tipo: {$u['tipo']} | Criado: {$u['data_criacao']} | Aprovado: {$u['data_aprovacao']}\n";
}
?>
