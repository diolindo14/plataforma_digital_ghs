<?php
// salvar_ghs.php - Implementação Estrita do Snippet Profissional (Green Académico)
header('Content-Type: application/json');

/**
 * INTEGRAÇÃO GHS: Usamos as constantes do sistema para garantir compatibilidade local (XAMPP/Root)
 */
$host = 'localhost'; 
$db   = 'ghsespf_db'; // Banco GHS detetado
$user = 'root';        // Usuário padrão XAMPP
$pass = '';            // Senha padrão XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro de conexão: ' . $e->getMessage()]);
    exit;
}

$assinatura = $_POST['assinatura'] ?? '';
$user_id = $_POST['user_id'] ?? 0;
$painel = $_POST['painel'] ?? 'desconhecido';

if (empty($assinatura)) {
    echo json_encode(['status' => 'error', 'message' => 'Assinatura vazia']);
    exit;
}

/**
 * LIMPEZA CRUCIAL: Remove o cabeçalho 'data:image/svg+xml;base64,' se existir
 */
if (strpos($assinatura, ',') !== false) {
    $assinatura = explode(',', $assinatura)[1];
}

$sql = "INSERT INTO assinaturas_ghs (user_id, tipo_painel, signature_svg) VALUES (?, ?, ?)";
// Nota Técnica: Verificamos se a coluna é 'assinatura_svg' como no SQL fornecido
$sql = "INSERT INTO assinaturas_ghs (user_id, tipo_painel, assinatura_svg) VALUES (?, ?, ?)";

try {
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$user_id, $painel, $assinatura])) {
        echo json_encode(['status' => 'success', 'message' => 'Gravado com sucesso!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao executar no banco']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro SQL: ' . $e->getMessage()]);
}
