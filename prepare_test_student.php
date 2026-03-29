<?php
$base_dir = dirname(__FILE__);
require_once $base_dir . '/core/config.php';
require_once $base_dir . '/core/Database.php';

try {
    $db = Database::getInstance();
    $db->beginTransaction();

    $email = 'estudante_renovacao@green.com';
    $password = password_hash('Alunos123!', PASSWORD_BCRYPT);
    $nome = 'Estudante de Teste Renovação';

    // 1. Criar/Ativar Utilizador
    $stmt = $db->prepare("INSERT INTO utilizadores (nome_completo, email, senha, tipo, status, data_aprovacao) 
                          VALUES (?, ?, ?, 'estudante', 'ativo', NOW()) 
                          ON DUPLICATE KEY UPDATE status='ativo', senha=?");
    $stmt->execute([$nome, $email, $password, $password]);
    $userIdArr = $db->query("SELECT id FROM utilizadores WHERE email='$email'")->fetch(PDO::FETCH_ASSOC);
    $userId = $userIdArr['id'];

    // 2. Criar/Verificar Perfil Estudante
    $stmt = $db->prepare("INSERT INTO estudantes (utilizador_id, bi, data_nascimento, nacionalidade, sexo, estado_civil, telefone, morada) 
                          VALUES (?, 'BI-TESTE-001', '2000-01-01', 'Guineense', 'Masculino', 'Solteiro', '912345678', 'Bissau') 
                          ON DUPLICATE KEY UPDATE bi='BI-TESTE-001'");
    $stmt->execute([$userId]);
    $estudanteIdArr = $db->query("SELECT id FROM estudantes WHERE utilizador_id=$userId")->fetch(PDO::FETCH_ASSOC);
    $estudanteId = $estudanteIdArr['id'];

    // 3. Preparar Matrícula Aprovada anterior para permitir TRANSITO (Elegível para renovação)
    $stmtAno = $db->query("SELECT id FROM anos WHERE ordem = 1 LIMIT 1");
    $anoId = $stmtAno->fetchColumn() ?: 1;
    
    // Limpar vestígios
    $db->prepare("DELETE FROM matriculas WHERE estudante_id = ?")->execute([$estudanteId]);

    $stmtM = $db->prepare("INSERT INTO matriculas (estudante_id, ano_letivo, ano_curso_id, turno, tipo, status, data_matricula, data_aprovacao) 
                           VALUES (?, 2025, ?, 'Manhã', 'Matrícula', 'Aprovada', '2025-01-01', NOW())");
    $stmtM->execute([$estudanteId, $anoId]);
    $matriculaId = $db->lastInsertId();

    // 4. Inserir Notas de APROVAÇÃO (Todas >= 12 para can_transit = true)
    $disciplinas = $db->query("SELECT id FROM disciplinas WHERE ano_id = $anoId")->fetchAll(PDO::FETCH_COLUMN);
    
    foreach($disciplinas as $did) {
        // Criar avaliação se não existir para o ano
        $stmtAv = $db->prepare("INSERT INTO avaliacoes (disciplina_id, tipo_avaliacao_id, data_avaliacao, peso) VALUES (?, 5, NOW(), 1.0)");
        $stmtAv->execute([$did]);
        $avId = $db->lastInsertId();
        
        $stmtNota = $db->prepare("INSERT INTO notas (estudante_id, avaliacao_id, nota, confirmado_admin) VALUES (?, ?, 15.0, 1)");
        $stmtNota->execute([$estudanteId, $avId]);
    }

    $db->commit();
    echo "Cenário de teste preparado com SUCESSO.\n";
    echo "Utilizador: $email | Senha: Alunos123!\n";

} catch (Exception $e) {
    if (isset($db)) $db->rollBack();
    echo "ERRO CRÍTICO no script: " . $e->getMessage() . "\n";
}
?>
