<?php
require 'core/Database.php';
$pdo = Database::getInstance();
$stmt = $pdo->query('SHOW CREATE TABLE horarios');
file_put_contents('horarios_schema.txt', $stmt->fetchColumn(1));
