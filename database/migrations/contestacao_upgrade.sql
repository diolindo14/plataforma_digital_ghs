-- ============================================================
-- MIGRAÇÃO: Fluxo Completo de Contestação de Avaliação v2.0
-- Compatível com MySQL 5.7+ / MariaDB
-- ============================================================

-- 1. Expandir ENUM de status
ALTER TABLE concordancia_notas
    MODIFY COLUMN status ENUM(
        'Pendente',
        'Respondido',
        'Resolvido',
        'Impasse',
        'Em_Mediacao',
        'Aguardando_Comparecimento',
        'Encerrado',
        'Concordado',
        'Reclamado'
    ) NOT NULL DEFAULT 'Pendente';

-- 2. Adicionar novas colunas (MySQL 5.7: sem IF NOT EXISTS, usa IGNORE no erro)
ALTER TABLE concordancia_notas
    ADD COLUMN contra_argumentacao TEXT NULL,
    ADD COLUMN data_abertura DATETIME NULL,
    ADD COLUMN data_impasse DATETIME NULL,
    ADD COLUMN data_escalacao DATETIME NULL,
    ADD COLUMN data_reuniao DATE NULL,
    ADD COLUMN hora_reuniao TIME NULL,
    ADD COLUMN local_reuniao VARCHAR(150) NULL,
    ADD COLUMN motivo_convocacao TEXT NULL,
    ADD COLUMN decisao_final TEXT NULL,
    ADD COLUMN mediado_por INT(11) NULL,
    ADD COLUMN presenca_aluno TINYINT(1) NULL,
    ADD COLUMN presenca_professor TINYINT(1) NULL,
    ADD COLUMN data_decisao DATETIME NULL;

-- 3. Inicializar data_abertura para registos existentes
UPDATE concordancia_notas
SET data_abertura = data_resposta
WHERE data_abertura IS NULL AND comentario IS NOT NULL;

-- 4. Índice para queries de mediação
CREATE INDEX idx_cn_status ON concordancia_notas (status, bloqueado_admin);
