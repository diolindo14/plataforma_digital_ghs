<?php
require_once __DIR__ . '/core/Database.php';

try {
    $db = Database::getInstance();
    
    echo "Iniciando migração de banco de dados...\n";

    // Adicionar confirmado_admin em sumarios
    $db->exec("ALTER TABLE sumarios ADD COLUMN IF NOT EXISTS confirmado_admin TINYINT(1) DEFAULT 0");
    echo "Coluna 'confirmado_admin' adicionada em 'sumarios'.\n";

    // Adicionar confirmado_admin em notas
    $db->exec("ALTER TABLE notas ADD COLUMN IF NOT EXISTS confirmado_admin TINYINT(1) DEFAULT 0");
    echo "Coluna 'confirmado_admin' adicionada em 'notas'.\n";

    // Adicionar confirmado_admin em frequencias
    $db->exec("ALTER TABLE frequencias ADD COLUMN IF NOT EXISTS confirmado_admin TINYINT(1) DEFAULT 0");
    echo "Coluna 'confirmado_admin' adicionada em 'frequencias'.\n";

    // Garantir que a tabela tipos_pagamento existe (para o campo dinâmico)
    $db->exec("CREATE TABLE IF NOT EXISTS tipos_pagamento (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(50) NOT NULL UNIQUE
    )");
    
    // Inserir tipos padrão se estiver vazio
    $stmt = $db->query("SELECT COUNT(*) FROM tipos_pagamento");
    if ($stmt->fetchColumn() == 0) {
        $db->exec("INSERT INTO tipos_pagamento (nome) VALUES ('Mensalidade'), ('Matrícula'), ('Exame Recurso'), ('Uniforme'), ('Outros')");
    }
    echo "Tabela 'tipos_pagamento' verificada/populada.\n";

    echo "Migração concluída com sucesso!\n";

} catch (Exception $e) {
    echo "ERRO NA MIGRAÇÃO: " . $e->getMessage() . "\n";
}
