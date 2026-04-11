<?php
// Tentar carregar as configurações do sistema
if (file_exists('core/config.php')) {
    require_once 'core/config.php';
}

// Simular autoloader para o Database
if (file_exists('core/Database.php')) {
    require_once 'core/Database.php';
}

try {
    $db = Database::getInstance();
    
    echo "<h1>Atualização da Base de Dados</h1>";

    // 1. Adicionar coluna 'grupo' à tabela 'matriculas'
    echo "<p>Verificando coluna 'grupo' na tabela 'matriculas'...</p>";
    try {
        $db->exec("ALTER TABLE matriculas ADD COLUMN grupo VARCHAR(50) DEFAULT 'G1' AFTER turno");
        echo "<p style='color:green'>Coluna 'grupo' adicionada com sucesso.</p>";
    } catch (Exception $e) {
        echo "<p style='color:orange'>Coluna 'grupo' já existe ou erro: " . $e->getMessage() . "</p>";
    }
    
    // 2. Adicionar colunas de antifraude em 'concordancia_notas'
    echo "<p>Verificando colunas de antifraude em 'concordancia_notas'...</p>";
    try {
        $db->exec("ALTER TABLE concordancia_notas ADD COLUMN contador_reclamacoes INT DEFAULT 0");
        echo "<p style='color:green'>Coluna 'contador_reclamacoes' adicionada.</p>";
    } catch (Exception $e) {
        echo "<p style='color:orange'>Coluna 'contador_reclamacoes' já existe ou erro: " . $e->getMessage() . "</p>";
    }

    try {
        $db->exec("ALTER TABLE concordancia_notas ADD COLUMN bloqueado_admin TINYINT(1) DEFAULT 0");
        echo "<p style='color:green'>Coluna 'bloqueado_admin' adicionada.</p>";
    } catch (Exception $e) {
        echo "<p style='color:orange'>Coluna 'bloqueado_admin' já existe ou erro: " . $e->getMessage() . "</p>";
    }

    echo "<h2 style='color:blue'>Processo Concluído!</h2>";
} catch (Exception $e) {
    echo "<h2 style='color:red'>Erro Crítico: " . $e->getMessage() . "</h2>";
}
