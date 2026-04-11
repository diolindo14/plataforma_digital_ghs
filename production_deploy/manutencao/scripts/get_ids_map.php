<?php
$db = new PDO('mysql:host=localhost;dbname=ghsespf_db', 'root', '');
$res = ['disciplinas' => [], 'turmas' => []];
foreach($db->query("SELECT id, codigo, nome FROM disciplinas")->fetchAll(PDO::FETCH_ASSOC) as $r) {
    $res['disciplinas'][$r['codigo']] = $r['id'];
}
foreach($db->query("SELECT id, codigo, turno FROM turmas")->fetchAll(PDO::FETCH_ASSOC) as $r) {
    $res['turmas'][$r['codigo']] = $r['id'];
}
echo json_encode($res, JSON_PRETTY_PRINT);
