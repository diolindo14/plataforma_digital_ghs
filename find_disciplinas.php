<?php
require_once 'core/Database.php';
$db = Database::getInstance();
$siglas = ['PI', 'MCG', 'IPM', 'RD2', 'IA', 'MC', 'ES', 'TSI', 'SID'];
$placeholders = str_repeat('?,', count($siglas) - 1) . '?';
$stmt = $db->prepare("SELECT id, codigo, nome FROM disciplinas WHERE codigo IN ($placeholders)");
$stmt->execute($siglas);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($results, JSON_PRETTY_PRINT);
