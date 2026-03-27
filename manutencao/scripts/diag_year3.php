<?php
$db = new PDO('mysql:host=localhost;dbname=ghsespf_db', 'root', '');
$discs = $db->query("SELECT id, codigo, nome, ano_id FROM disciplinas WHERE ano_id = 3")->fetchAll(PDO::FETCH_ASSOC);

$targets = ['CDSI', 'PHP', 'RD1', 'HM', 'JAVASCR', 'JAVA', 'TC', 'SO', 'FBD'];
echo "Checking Year 3 Disciplines:\n";
foreach($targets as $t) {
    echo "$t: ";
    $found = false;
    foreach($discs as $d) {
        if(str_contains($d['codigo'], $t) || str_contains($d['nome'], $t)) {
            echo "Found {$d['codigo']} ({$d['nome']}) | ";
            $found = true;
        }
    }
    if(!$found) echo "NOT FOUND";
    echo "\n";
}
