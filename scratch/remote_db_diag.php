<?php
$db_host = 'sql111.infinityfree.com';
$db_name = 'if0_41574650_ghs_sistema';
$db_user = 'if0_41574650';
$db_pass = '0svEjAnMHnX';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "CONNECTED TO PROD DB\n\n";
    
    echo "--- LATEST MATRICULAS ---\n";
    $stmt = $pdo->query("
        SELECT m.id, m.estudante_id, m.status, m.turma_id, m.ano_curso_id, u.nome_completo 
        FROM matriculas m 
        JOIN estudantes e ON m.estudante_id = e.id 
        JOIN utilizadores u ON e.utilizador_id = u.id 
        ORDER BY m.id DESC LIMIT 10
    ");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($rows as $r) {
        echo "ID:{$r['id']} | Est:{$r['nome_completo']} | Status:{$r['status']} | Turma:".($r['turma_id']??"NULL")." | Ano:{$r['ano_curso_id']}\n";
    }

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
