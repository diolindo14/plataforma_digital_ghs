-- Migração: Sistema de Certificados de Mérito Semestral
-- Data: 2026-03-27
-- Descrição: Tabela para registar certificados emitidos pelos top 2 alunos por semestre

CREATE TABLE IF NOT EXISTS `certificados_merito` (
    `id`            INT(11) NOT NULL AUTO_INCREMENT,
    `estudante_id`  INT(11) NOT NULL,
    `semestre`      ENUM('1','2') NOT NULL COMMENT 'Semestre em que o mérito foi alcançado',
    `ano_letivo`    VARCHAR(10) NOT NULL COMMENT 'Ex: 2025/2026',
    `posicao`       ENUM('1','2') NOT NULL COMMENT '1º ou 2º lugar',
    `media`         DECIMAL(5,2) NOT NULL COMMENT 'Média calculada (AC + Exame)',
    `nivel_nome`    VARCHAR(150) NULL COMMENT 'Ex: 1º Ano, 2º Ano',
    `emitido_por`   INT(11) NULL COMMENT 'ID do utilizador (Admin/Secretaria) que emitiu',
    `data_emissao`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uniq_cert` (`estudante_id`, `semestre`, `ano_letivo`),
    KEY `idx_estudante` (`estudante_id`),
    KEY `idx_semestre_ano` (`semestre`, `ano_letivo`),
    CONSTRAINT `fk_cert_estudante` FOREIGN KEY (`estudante_id`) REFERENCES `estudantes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
