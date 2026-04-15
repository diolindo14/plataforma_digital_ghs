<?php
// Acessível sem restrição - diagnóstico direto com output completo
header('Content-Type: text/plain; charset=utf-8');

$db_host = 'sql111.infinityfree.com';
$db_name = 'if0_41574650_ghs_sistema';
$db_user = 'if0_41574650';
$db_pass = '0svEjAnMHnX';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);

    echo "=== GRID DATA (turma 7) ===\n";
    $stmt = $pdo->prepare("
        SELECT h.id, h.dia_semana, h.hora_inicio, h.hora_fim, h.sala,
               h.tempo_aula,
               d.codigo as sigla, d.nome as disciplina_nome
        FROM horarios h
        JOIN disciplinas d ON h.disciplina_id = d.id
        WHERE h.turma_id = 7
        ORDER BY FIELD(h.dia_semana,'Segunda','Terça','Quarta','Quinta','Sexta','Sábado'), h.hora_inicio
    ");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Total: " . count($rows) . " rows\n";
    foreach ($rows as $r) {
        echo "Dia:{$r['dia_semana']} T:{$r['tempo_aula']} Sigla:'{$r['sigla']}' Nome:'{$r['disciplina_nome']}' Sala:'{$r['sala']}'\n";
    }

    echo "\n=== TURMA 7 ===\n";
    $stmt2 = $pdo->query("SELECT id, codigo, turno FROM turmas WHERE id = 7");
    $t = $stmt2->fetch(PDO::FETCH_ASSOC);
    echo "Codigo:{$t['codigo']} Turno:{$t['turno']}\n";

} catch (Exception $e) {
    echo "ERRO: " . $e->getMessage() . "\n";
}
