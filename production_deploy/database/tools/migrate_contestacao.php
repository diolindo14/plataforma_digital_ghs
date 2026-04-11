<?php
/**
 * Script de migração seguro: adiciona apenas as colunas que ainda não existem
 * Executar via: http://localhost/green/database/tools/migrate_contestacao.php
 * (ou via CLI: C:\xampp\php\php.exe database/tools/migrate_contestacao.php)
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'ghsespf_db');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

echo "<pre style='font-family:monospace; padding:20px'>\n";
echo "=== MIGRAÇÃO: Contestação de Avaliação v2.0 ===\n\n";

// 1. Descobrir colunas existentes
$existentes = [];
$res = $pdo->query("SHOW COLUMNS FROM concordancia_notas");
foreach ($res->fetchAll(PDO::FETCH_ASSOC) as $col) {
    $existentes[$col['Field']] = $col['Type'];
}

echo "Colunas existentes: " . implode(', ', array_keys($existentes)) . "\n";
echo "ENUM actual de 'status': " . $existentes['status'] . "\n\n";

// 2. Definir todas as colunas necessárias
$colunas = [
    'contra_argumentacao'    => "TEXT NULL",
    'data_abertura'          => "DATETIME NULL",
    'data_impasse'           => "DATETIME NULL",
    'data_escalacao'         => "DATETIME NULL",
    'data_reuniao'           => "DATE NULL",
    'hora_reuniao'           => "TIME NULL",
    'local_reuniao'          => "VARCHAR(150) NULL",
    'motivo_convocacao'      => "TEXT NULL",
    'decisao_final'          => "TEXT NULL",
    'mediado_por'            => "INT(11) NULL",
    'presenca_aluno'         => "TINYINT(1) NULL",
    'presenca_professor'     => "TINYINT(1) NULL",
    'data_decisao'           => "DATETIME NULL",
];

echo "--- Verificando colunas em falta ---\n";
$adicionadas = 0;
foreach ($colunas as $col => $def) {
    if (!isset($existentes[$col])) {
        try {
            $pdo->exec("ALTER TABLE concordancia_notas ADD COLUMN `$col` $def");
            echo "✅ Adicionada: $col $def\n";
            $adicionadas++;
        } catch (PDOException $e) {
            echo "⚠️  Erro em $col: " . $e->getMessage() . "\n";
        }
    } else {
        echo "⏭️  Já existe: $col ({$existentes[$col]})\n";
    }
}

// 3. Actualizar ENUM de status
echo "\n--- Actualizando ENUM 'status' ---\n";
$enumCorreto = "enum('Pendente','Respondido','Resolvido','Impasse','Em_Mediacao','Aguardando_Comparecimento','Encerrado','Concordado','Reclamado')";
try {
    $pdo->exec("ALTER TABLE concordancia_notas MODIFY COLUMN status $enumCorreto NOT NULL DEFAULT 'Pendente'");
    echo "✅ ENUM actualizado com todos os estados.\n";
} catch (PDOException $e) {
    echo "⚠️  Erro no ENUM: " . $e->getMessage() . "\n";
}

// 4. Inicializar data_abertura para registos existentes
echo "\n--- Inicializando data_abertura ---\n";
$n = $pdo->exec("UPDATE concordancia_notas SET data_abertura = data_resposta WHERE data_abertura IS NULL AND comentario IS NOT NULL");
echo "✅ Actualizados $n registos.\n";

// 5. Verificar/criar índice
echo "\n--- Verificando índice ---\n";
try {
    $pdo->exec("CREATE INDEX idx_cn_status ON concordancia_notas (status, bloqueado_admin)");
    echo "✅ Índice criado.\n";
} catch (PDOException $e) {
    echo "⏭️  Índice já existe (normal): " . $e->getMessage() . "\n";
}

// 6. Mostrar estrutura final
echo "\n--- Estrutura final da tabela ---\n";
$res = $pdo->query("SHOW COLUMNS FROM concordancia_notas");
foreach ($res->fetchAll(PDO::FETCH_ASSOC) as $col) {
    printf("  %-30s %s\n", $col['Field'], $col['Type']);
}

echo "\n✅ Migração concluída! $adicionadas coluna(s) nova(s) adicionada(s).\n";
echo "</pre>\n";
echo "<script>document.title='Migração OK';</script>";
