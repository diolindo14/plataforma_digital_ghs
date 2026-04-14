<?php
/**
 * Script de Inserção em Massa - Turma 4N1 (PRODUÇÃO)
 * 
 * ATENÇÃO: Apagar este ficheiro IMEDIATAMENTE após a execução.
 * Ficheiro exposto ao público pode ser um risco de segurança.
 */

// === CHAVE DE PROTECÇÃO ===
// Por segurança, o script só corre se a chave correcta for passada na URL
// Aceder em: /insert_4n1_prod.php?chave=GHS2026BATCH
define('CHAVE_SECRETA', 'GHS2026BATCH');

if (!isset($_GET['chave']) || $_GET['chave'] !== CHAVE_SECRETA) {
    http_response_code(403);
    die('<h1>403 - Acesso Negado</h1>');
}

$db_host = 'sql111.infinityfree.com';
$db_name = 'if0_41574650_ghs_sistema';
$db_user = 'if0_41574650';
$db_pass = '0svEjAnMHnX';

$names = [
    "Augusto Quadé",
    "Djibril Djau",
    "Edjar Jacinto Mendes",
    "Felix Diouf Júnior",
    "Ismael Cumor Bleté Mirik",
    "Júlio Na Cia",
    "Nhinte Na Tum-na",
    "Paiva Ibraim Gomes Jaquité",
    "Raki Wane",
    "Vivaldo U. Tavares Gomes",
    "Odília Luís Pereira",
    "Negado Insanhe",
    "Ocharina Embaló",
    "Quintino Nhanque"
];

$turma_id    = null; // Será determinado dinamicamente
$ano_curso_id = null;
$ano_letivo  = '2025/2026';

echo "<pre>";
echo "=== INSERÇÃO TURMA 4N1 - PRODUÇÃO ===\n\n";

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✔ Conectado à base de dados de produção.\n\n";

    // Descobrir o ID da turma GHS-4N1
    $stmt = $pdo->query("SELECT id, ano_id FROM turmas WHERE codigo = 'GHS-4N1' LIMIT 1");
    $turma = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$turma) {
        // Tentar outros formatos de código
        $stmt = $pdo->query("SELECT id, ano_id FROM turmas WHERE codigo LIKE '%4N1%' LIMIT 1");
        $turma = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if (!$turma) {
        echo "ERRO: Turma 4N1 não encontrada na base de dados de produção!\n";
        echo "Turmas existentes:\n";
        $stmt = $pdo->query("SELECT id, codigo, ano_id FROM turmas");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "  ID: {$row['id']} | Codigo: {$row['codigo']} | Ano ID: {$row['ano_id']}\n";
        }
        echo "</pre>";
        exit;
    }

    $turma_id    = $turma['id'];
    $ano_curso_id = $turma['ano_id'];
    echo "✔ Turma encontrada: GHS-4N1 (ID: $turma_id, Ano ID: $ano_curso_id)\n\n";

    $success = 0;
    $skipped = 0;
    $errors  = 0;

    foreach ($names as $nome) {

        // Gerar email sem acentos: nome.sobrenome@ghs.school
        $parts = explode(' ', strtolower(trim($nome)));
        $email  = $parts[0] . '.' . end($parts) . '@ghs.school';
        $email  = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $email);
        $email  = preg_replace('/[^a-z0-9@._-]/', '', $email);

        // Verificar se já existe utilizador com esse nome
        $stmt_check = $pdo->prepare("SELECT id FROM utilizadores WHERE nome_completo = ? AND tipo = 'estudante'");
        $stmt_check->execute([$nome]);
        if ($stmt_check->fetch()) {
            echo "⚠ JÁ EXISTE: $nome – ignorado.\n";
            $skipped++;
            continue;
        }

        $pdo->beginTransaction();
        try {
            // 1. Criar Utilizador
            $senha_hash = password_hash("123456", PASSWORD_DEFAULT);
            $stmt_user = $pdo->prepare("INSERT INTO utilizadores (nome_completo, email, senha, tipo, status) VALUES (?, ?, ?, 'estudante', 'ativo')");
            $stmt_user->execute([$nome, $email, $senha_hash]);
            $user_id = $pdo->lastInsertId();

            // 2. Criar Perfil Estudante
            $bi_mock = "GHS" . str_pad($user_id, 6, "0", STR_PAD_LEFT);
            $stmt_est = $pdo->prepare("INSERT INTO estudantes (utilizador_id, bi, nacionalidade, cidade, estado_civil) VALUES (?, ?, 'Guineense', 'Bissau', 'Solteiro')");
            $stmt_est->execute([$user_id, $bi_mock]);
            $estudante_id = $pdo->lastInsertId();

            // 3. Criar Matrícula vinculada à Turma 4N1
            $stmt_mat = $pdo->prepare("INSERT INTO matriculas (estudante_id, ano_letivo, ano_curso_id, turma_id, status, data_matricula) VALUES (?, ?, ?, ?, 'Aprovada', NOW())");
            $stmt_mat->execute([$estudante_id, $ano_letivo, $ano_curso_id, $turma_id]);

            $pdo->commit();
            echo "✔ Inserido: $nome → $email (User ID: $user_id)\n";
            $success++;

        } catch (Exception $e) {
            $pdo->rollBack();
            echo "✘ Erro ao inserir $nome: " . $e->getMessage() . "\n";
            $errors++;
        }
    }

    echo "\n---\n";
    echo "RESULTADO: $success inseridos | $skipped já existiam | $errors erros\n";

} catch (PDOException $e) {
    echo "ERRO DE CONEXÃO: " . $e->getMessage();
}

echo "\n⚠ APAGUE ESTE FICHEIRO DO SERVIDOR AGORA!\n";
echo "</pre>";
