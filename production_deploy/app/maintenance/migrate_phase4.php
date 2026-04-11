<?php
require_once __DIR__ . '/core/Database.php';

try {
    $db = Database::getInstance();
    
    // 1. Pagamentos Table
    $db->exec("CREATE TABLE IF NOT EXISTS pagamentos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        estudante_id INT NOT NULL,
        tipo_pagamento ENUM('Propina', 'Matrícula', 'Multa', 'Outros') NOT NULL,
        mes_referencia VARCHAR(20) NULL,
        ano_referencia INT NULL,
        valor DECIMAL(10,2) NOT NULL,
        data_pagamento DATE NOT NULL,
        metodo_pagamento ENUM('Numerário', 'Transferência Bancária', 'Depósito') NOT NULL,
        comprovativo VARCHAR(255) NULL,
        status ENUM('Pendente', 'Pago', 'Rejeitado') NOT NULL DEFAULT 'Pendente',
        registado_por INT NOT NULL,
        observacoes TEXT NULL,
        data_registo TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (estudante_id) REFERENCES estudantes(id) ON DELETE CASCADE,
        FOREIGN KEY (registado_por) REFERENCES utilizadores(id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    echo "Tabela 'pagamentos' criada com sucesso.\n";

    // 2. Comunicados Table
    $db->exec("CREATE TABLE IF NOT EXISTS comunicados (
        id INT AUTO_INCREMENT PRIMARY KEY,
        titulo VARCHAR(255) NOT NULL,
        conteudo TEXT NOT NULL,
        data_publicacao DATETIME NOT NULL,
        tipo_destinatario ENUM('Todos', 'Professores', 'Alunos', 'Turma_Especifica') NOT NULL,
        turma_id INT NULL,
        criado_por INT NOT NULL,
        data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (turma_id) REFERENCES turmas(id) ON DELETE CASCADE,
        FOREIGN KEY (criado_por) REFERENCES utilizadores(id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    echo "Tabela 'comunicados' criada com sucesso.\n";

    // 3. Comunicados Leituras Table
    $db->exec("CREATE TABLE IF NOT EXISTS comunicados_leituras (
        comunicado_id INT NOT NULL,
        utilizador_id INT NOT NULL,
        data_leitura DATETIME NOT NULL,
        PRIMARY KEY (comunicado_id, utilizador_id),
        FOREIGN KEY (comunicado_id) REFERENCES comunicados(id) ON DELETE CASCADE,
        FOREIGN KEY (utilizador_id) REFERENCES utilizadores(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    echo "Tabela 'comunicados_leituras' criada com sucesso.\n";

} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
