<?php
$db = new PDO('mysql:host=localhost;dbname=ghsespf_db', 'root', '');
foreach($db->query("SELECT codigo, nome FROM disciplinas") as $r) {
    echo $r['codigo'] . " | " . $r['nome'] . "\n";
}
