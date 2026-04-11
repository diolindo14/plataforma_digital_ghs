<?php
require_once 'core/Database.php';

$db = Database::getInstance();

try {
    // Criar tabela de convites
    $db->exec("
        CREATE TABLE IF NOT EXISTS `convites` (
          `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `email` varchar(100) NOT NULL,
          `tipo` enum('admin', 'secretaria', 'professor') NOT NULL,
          `token` varchar(100) NOT NULL UNIQUE,
          `expira_em` datetime NOT NULL,
          `usado` tinyint(1) DEFAULT 0,
          `criado_por` int(11) NOT NULL,
          `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
          FOREIGN KEY (`criado_por`) REFERENCES `utilizadores` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    echo "Migration para a tabela 'convites' concluída com sucesso.\n";

} catch (Exception $e) {
    echo "Erro na migration: " . $e->getMessage() . "\n";
}
