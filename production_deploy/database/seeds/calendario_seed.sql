-- Povoamento de eventos do calendário baseados no panfleto "CALENDÁRIO LETIVO 2025/2026"
DELETE FROM eventos WHERE titulo IN (
    'Início do Ano Letivo', 'Natal', 'Férias de Natal', 'Fim das Férias de Natal',
    'Ano Novo', 'Dia dos Heróis Nacionais', 'Carnaval (Início)', 'Carnaval (Fim)',
    'Fim do 1º Semestre', 'Dia da Mulher', 'Páscoa', 'Férias da Páscoa (Início)',
    'Férias da Páscoa (Fim)', 'Dia dos Trabalhadores', 'Tabaski', 'Fim do Ano Letivo',
    'Dia dos Professores', 'Ramadão'
);

INSERT INTO eventos (titulo, descricao, data_evento, tipo, cor, destinatario_tipo, criado_por, data_criacao) VALUES
('Início do Ano Letivo', 'Início das atividades do ano letivo 2025/2026.', '2025-10-15', 'Institucional', '#ca8a04', 'Global', 1, NOW()),
('Férias de Natal', 'Início da pausa letiva de Natal.', '2025-12-22', 'Férias', '#f59e0b', 'Global', 1, NOW()),
('Natal', 'Feriado Nacional: Dia de Natal.', '2025-12-25', 'Feriado', '#dc2626', 'Global', 1, NOW()),
('Fim das Férias de Natal', 'Último dia da pausa de Natal.', '2026-01-04', 'Férias', '#f59e0b', 'Global', 1, NOW()),
('Ano Novo', 'Feriado Nacional: Dia de Ano Novo.', '2026-01-01', 'Feriado', '#dc2626', 'Global', 1, NOW()),
('Dia dos Heróis Nacionais', 'Feriado Nacional em homenagem aos Heróis Nacionais.', '2026-01-20', 'Feriado', '#dc2626', 'Global', 1, NOW()),
('Carnaval (Início)', 'Pausa letiva para as festividades de Carnaval.', '2026-02-15', 'Férias', '#f59e0b', 'Global', 1, NOW()),
('Dia dos Professores', 'Homenagem aos Docentes.', '2026-02-17', 'Oficial', '#ea580c', 'Global', 1, NOW()),
('Carnaval (Fim)', 'Término da pausa de Carnaval.', '2026-02-17', 'Férias', '#f59e0b', 'Global', 1, NOW()),
('Fim do 1º Semestre', 'Encerramento oficial das atividades do 1º Semestre.', '2026-03-06', 'Institucional', '#2563eb', 'Global', 1, NOW()),
('Dia da Mulher', 'Feriado Nacional: Dia Internacional da Mulher.', '2026-03-08', 'Feriado', '#dc2626', 'Global', 1, NOW()),
('Ramadão', 'Data reservada ao Ramadão.', '2026-03-20', 'Feriado', '#dc2626', 'Global', 1, NOW()),
('Férias da Páscoa (Início)', 'Início da pausa letiva da Páscoa.', '2026-04-03', 'Férias', '#f59e0b', 'Global', 1, NOW()),
('Páscoa', 'Feriado Nacional.', '2026-04-05', 'Feriado', '#dc2626', 'Global', 1, NOW()),
('Férias da Páscoa (Fim)', 'Fim da pausa letiva.', '2026-04-06', 'Férias', '#f59e0b', 'Global', 1, NOW()),
('Dia dos Trabalhadores', 'Feriado Nacional.', '2026-05-01', 'Feriado', '#dc2626', 'Global', 1, NOW()),
('Tabaski', 'Feriado Nacional (Data dependente do calendário lunar, est. Tabaski).', '2026-05-27', 'Feriado', '#dc2626', 'Global', 1, NOW()),
('Fim do Ano Letivo', 'Finalização do Ano Letivo 2025/2026.', '2026-07-22', 'Institucional', '#ca8a04', 'Global', 1, NOW());
