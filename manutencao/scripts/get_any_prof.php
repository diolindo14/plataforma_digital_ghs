<?php
require_once 'core/Database.php';
$db = Database::getInstance();
$stmt = $db->query("SELECT id FROM professores LIMIT 1");
$prof = $stmt->fetch(PDO::FETCH_ASSOC);
echo $prof['id'];
