<?php
$db = new PDO('mysql:host=localhost;dbname=ghsespf_db', 'root', '');
$q = $db->query("SELECT id, codigo, nome, ano_id FROM disciplinas");
echo json_encode($q->fetchAll(PDO::FETCH_ASSOC), JSON_PRETTY_PRINT);
