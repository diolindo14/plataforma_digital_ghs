<?php
require_once 'core/config.php';
require_once 'core/Database.php';

$db = Database::getInstance();

try {
    // 1. Restaurar Admin Principal
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $db->exec("INSERT IGNORE INTO utilizadores (id, nome_completo, email, senha, tipo, status) 
               VALUES (1, 'Administrador GHS', 'admin@ghs.com', '$hash', 'admin', 'ativo')");
    
    // 2. Restaurar Tabelas de Mapeamento (Anos, Turmas, Especialidades) se estiverem vazias
    // (Pode ter ocorrido um truncamento acidental de tudo)
    
    // Anos
    $anos_count = $db->query("SELECT COUNT(*) FROM anos")->fetchColumn();
    if ($anos_count == 0) {
        $db->exec("INSERT INTO anos (id, nome, ordem) VALUES 
                   (1, '1º Ano', 1), (2, '2º Ano', 2), (3, '3º Ano', 3), (4, '4º Ano', 4), (5, '5º Ano', 5)");
    }
    
    // Especializações
    $esp_count = $db->query("SELECT COUNT(*) FROM especializacoes")->fetchColumn();
    if ($esp_count == 0) {
        $db->exec("INSERT INTO especializacoes (id, nome) VALUES 
                   (1, 'Hardware & Robótica'), (2, 'Programação'), (3, 'Banco de Dados'), 
                   (4, 'Redes de Computadores'), (5, 'Engenharia Médica')");
    }

    // Turmas (GHS-1M1, etc.)
    $turmas_count = $db->query("SELECT COUNT(*) FROM turmas")->fetchColumn();
    if ($turmas_count == 0) {
        $db->exec("INSERT INTO turmas (codigo, turno) VALUES 
            ('GHS-1M1', 'Manhã'), ('GHS-1T1', 'Tarde'), ('GHS-1N1', 'Noite'),
            ('GHS-2M1', 'Manhã'), ('GHS-2T1', 'Tarde'),
            ('GHS-3M1', 'Manhã'), ('GHS-3T1', 'Tarde'),
            ('GHS-4T1', 'Tarde'),
            ('GHS-5TRD1', 'Tarde'), ('GHS-5NBD1', 'Noite')");
    }

    echo "ESTADO DO SERVIDOR RESTAURADO COM SUCESSO.\n";
    echo "Login Admin: admin@ghs.com | Senha: admin123\n";

} catch (Exception $e) {
    echo "ERRO NA RESTAURAÇÃO: " . $e->getMessage();
}
