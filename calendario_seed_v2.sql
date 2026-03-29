-- Limpar Tudo Anterior (Reset limpo do calendário escolar)
TRUNCATE TABLE eventos;

-- Iniciar Preenchimento V2:
INSERT INTO eventos (titulo, descricao, data_evento, tipo, cor, destinatario_tipo, criado_por, data_criacao)
VALUES
-- Grelha: Azul (Exames Semestrais) - #3b82f6
('Exames Semestrais', 'Semana de Exames Semestrais', '2025-11-02', 'Institucional', '#3b82f6', 'Global', 1, NOW()),
('Exames Semestrais', 'Semana de Exames Semestrais', '2025-11-16', 'Institucional', '#3b82f6', 'Global', 1, NOW()),
('Exames Semestrais', 'Semana de Exames Semestrais', '2026-01-20', 'Institucional', '#3b82f6', 'Global', 1, NOW()),
('Exames Semestrais', 'Semana de Exames Semestrais', '2026-02-16', 'Institucional', '#3b82f6', 'Global', 1, NOW()),
('Exames Semestrais', 'Semana de Exames Semestrais', '2026-02-17', 'Institucional', '#3b82f6', 'Global', 1, NOW()),
('Exames Semestrais', 'Semana de Exames Semestrais', '2026-03-08', 'Institucional', '#3b82f6', 'Global', 1, NOW()),
('Exames Semestrais', 'Semana de Exames Semestrais', '2026-05-01', 'Institucional', '#3b82f6', 'Global', 1, NOW()),

-- Grelha: Laranja (Prova de recorco) - #ea580c
('Prova de recorco', 'Avaliação contínua/recurso', '2025-12-30', 'Institucional', '#ea580c', 'Global', 1, NOW()),
('Prova de recorco', 'Avaliação contínua/recurso', '2026-02-15', 'Institucional', '#ea580c', 'Global', 1, NOW()),
('Prova de recorco', 'Avaliação contínua/recurso', '2026-02-18', 'Institucional', '#ea580c', 'Global', 1, NOW()),
('Prova de recorco', 'Avaliação contínua/recurso', '2026-03-23', 'Institucional', '#ea580c', 'Global', 1, NOW()),

-- Grelha: Castanho Claro (Semana Transitoria) - #d6d3d1
('Semana Transitoria', 'Semana de reflexão/transição letiva', '2026-02-21', 'Institucional', '#d6d3d1', 'Global', 1, NOW()),
('Semana Transitoria', 'Semana de reflexão/transição letiva', '2026-03-09', 'Institucional', '#d6d3d1', 'Global', 1, NOW()),
('Semana Transitoria', 'Semana de reflexão/transição letiva', '2026-03-10', 'Institucional', '#d6d3d1', 'Global', 1, NOW()),
('Semana Transitoria', 'Semana de reflexão/transição letiva', '2026-03-11', 'Institucional', '#d6d3d1', 'Global', 1, NOW()),
('Semana Transitoria', 'Semana de reflexão/transição letiva', '2026-03-12', 'Institucional', '#d6d3d1', 'Global', 1, NOW()),
('Semana Transitoria', 'Semana de reflexão/transição letiva', '2026-03-13', 'Institucional', '#d6d3d1', 'Global', 1, NOW()),
('Semana Transitoria', 'Semana de reflexão/transição letiva', '2026-03-14', 'Institucional', '#d6d3d1', 'Global', 1, NOW()),
('Semana Transitoria', 'Semana de reflexão/transição letiva', '2026-03-15', 'Institucional', '#d6d3d1', 'Global', 1, NOW()),
('Semana Transitoria', 'Semana de reflexão/transição letiva', '2026-03-16', 'Institucional', '#d6d3d1', 'Global', 1, NOW()),
('Semana Transitoria', 'Semana de reflexão/transição letiva', '2026-03-17', 'Institucional', '#d6d3d1', 'Global', 1, NOW()),
('Semana Transitoria', 'Semana de reflexão/transição letiva', '2026-03-18', 'Institucional', '#d6d3d1', 'Global', 1, NOW()),

-- Grelha: Verde Claro (Torneio de AAEGHS) - #22c55e
('Torneio de AAEGHS', 'Atividades Estudantis (Verde)', '2025-11-22', 'Extra', '#22c55e', 'Global', 1, NOW()),

-- Grelha: Verde Escuro (Torneio de futebol AAEGHS) - #4d7c0f
('Torneio de futebol AAEGHS', 'Atividade Desportiva Estudantil', '2025-11-29', 'Extra', '#4d7c0f', 'Global', 1, NOW()),
('Torneio de futebol AAEGHS', 'Atividade Desportiva Estudantil', '2026-04-25', 'Extra', '#4d7c0f', 'Global', 1, NOW()),

-- Grelha: Castanho Escuro (Admintaria Geral AAEGHS) - #572d16
('Admintaria Geral AAEGHS', 'Evento Administrativo Estudantil', '2025-12-13', 'Oficial', '#572d16', 'Global', 1, NOW()),
('Admintaria Geral AAEGHS', 'Evento Administrativo Estudantil', '2026-06-20', 'Oficial', '#572d16', 'Global', 1, NOW()),

-- Grelha: Amarelo Claro (Férias de Natal, Carnaval e Páscoa) - #fef08a
('Férias Letivas (Amarelo Claro)', 'Período Férias (Calendário)', '2025-12-22', 'Férias', '#fde047', 'Global', 1, NOW()),
('Férias Letivas (Amarelo Claro)', 'Período Férias (Calendário)', '2025-12-23', 'Férias', '#fde047', 'Global', 1, NOW()),
('Férias Letivas (Amarelo Claro)', 'Período Férias (Calendário)', '2025-12-24', 'Férias', '#fde047', 'Global', 1, NOW()),
('Férias Letivas (Amarelo Claro)', 'Período Férias (Calendário)', '2025-12-28', 'Férias', '#fde047', 'Global', 1, NOW()),
('Férias Letivas (Amarelo Claro)', 'Período Férias (Calendário)', '2025-12-29', 'Férias', '#fde047', 'Global', 1, NOW()),
('Férias Letivas (Amarelo Claro)', 'Período Férias (Calendário)', '2025-12-31', 'Férias', '#fde047', 'Global', 1, NOW()),
('Férias Letivas (Amarelo Claro)', 'Período Férias (Calendário)', '2026-01-03', 'Férias', '#fde047', 'Global', 1, NOW()),
('Férias Letivas (Amarelo Claro)', 'Período Férias (Calendário)', '2026-01-04', 'Férias', '#fde047', 'Global', 1, NOW()),
('Férias Letivas (Amarelo Claro)', 'Período Férias (Calendário)', '2026-02-14', 'Férias', '#fde047', 'Global', 1, NOW()),
('Férias Letivas (Amarelo Claro)', 'Período Férias (Calendário)', '2026-03-22', 'Férias', '#fde047', 'Global', 1, NOW()),

-- Grelha: Vermelho (Feriados Nacionais na Grelha) - #ef4444
('Feriado Nacional (Calendário)', 'Bloco Vermelho', '2026-03-01', 'Feriado', '#ef4444', 'Global', 1, NOW()),
('Feriado Nacional (Calendário)', 'Bloco Vermelho', '2026-03-04', 'Feriado', '#ef4444', 'Global', 1, NOW()),
('Feriado Nacional (Calendário)', 'Bloco Vermelho', '2026-03-05', 'Feriado', '#ef4444', 'Global', 1, NOW()),
('Feriado Nacional (Calendário)', 'Bloco Vermelho', '2026-03-06', 'Feriado', '#ef4444', 'Global', 1, NOW()),
('Feriado Nacional (Calendário)', 'Bloco Vermelho', '2026-03-07', 'Feriado', '#ef4444', 'Global', 1, NOW()),
('Feriado Nacional (Calendário)', 'Bloco Vermelho', '2026-07-13', 'Feriado', '#ef4444', 'Global', 1, NOW()),
('Feriado Nacional (Calendário)', 'Bloco Vermelho', '2026-07-14', 'Feriado', '#ef4444', 'Global', 1, NOW()),
('Feriado Nacional (Calendário)', 'Bloco Vermelho', '2026-07-17', 'Feriado', '#ef4444', 'Global', 1, NOW()),
('Feriado Nacional (Calendário)', 'Bloco Vermelho', '2026-07-18', 'Feriado', '#ef4444', 'Global', 1, NOW()),
('Feriado Nacional (Calendário)', 'Bloco Vermelho', '2026-07-20', 'Feriado', '#ef4444', 'Global', 1, NOW()),
('Feriado Nacional (Calendário)', 'Bloco Vermelho', '2026-07-21', 'Feriado', '#ef4444', 'Global', 1, NOW()),
('Feriado Nacional (Calendário)', 'Bloco Vermelho', '2026-07-22', 'Feriado', '#ef4444', 'Global', 1, NOW()),
('Feriado Nacional (Calendário)', 'Bloco Vermelho', '2026-07-23', 'Feriado', '#ef4444', 'Global', 1, NOW()),

-- Grelha: Inícios e Fins de Ano / Semestre (Amarelo) - #eab308
('Início do Ano Lectivo', 'Primeiro dia de Aulas', '2025-10-15', 'Institucional', '#eab308', 'Global', 1, NOW()),
('Fim do 2º Semestre/Ano Letivo', 'Finalização Amarela', '2026-03-21', 'Institucional', '#eab308', 'Global', 1, NOW()),
('Fim do Ano/Marco', 'Finalização Amarela', '2026-05-29', 'Institucional', '#eab308', 'Global', 1, NOW()),

-- Textboxes / Caixas de Texto Sobrepostas
('NATAL (Grelha Nov/Mar)', 'Caixa de Texto "NATAL"', '2025-11-05', 'Feriado', '#dc2626', 'Global', 1, NOW()),
('NATAL (Grelha Nov/Mar)', 'Caixa de Texto "NATAL"', '2025-11-06', 'Feriado', '#dc2626', 'Global', 1, NOW()),
('HERÓIS (Grelha Dez)', 'Caixa de Texto "HERÓIS"', '2025-12-25', 'Feriado', '#ca8a04', 'Global', 1, NOW()),
('HERÓIS (Grelha Dez)', 'Caixa de Texto "HERÓIS"', '2025-12-26', 'Feriado', '#ca8a04', 'Global', 1, NOW()),
('HERÓIS (Grelha Dez)', 'Caixa de Texto "HERÓIS"', '2025-12-27', 'Feriado', '#ca8a04', 'Global', 1, NOW()),
('ANO NOVO (Grelha Jan)', 'Caixa de Texto "ANO NOVO"', '2026-01-01', 'Feriado', '#ca8a04', 'Global', 1, NOW()),
('ANO NOVO (Grelha Jan)', 'Caixa de Texto "ANO NOVO"', '2026-01-02', 'Feriado', '#ca8a04', 'Global', 1, NOW()),
('CARNAVAL (Grelha Fev)', 'Caixa de Texto "CARNAVAL"', '2026-02-25', 'Férias', '#dc2626', 'Global', 1, NOW()),
('CARNAVAL (Grelha Fev)', 'Caixa de Texto "CARNAVAL"', '2026-02-26', 'Férias', '#dc2626', 'Global', 1, NOW()),
('CARNAVAL (Grelha Fev)', 'Caixa de Texto "CARNAVAL"', '2026-02-27', 'Férias', '#dc2626', 'Global', 1, NOW()),
('CARNAVAL (Grelha Fev)', 'Caixa de Texto "CARNAVAL"', '2026-02-28', 'Férias', '#dc2626', 'Global', 1, NOW()),
('NATAL (Grelha Nov/Mar)', 'Caixa de Texto "NATAL"', '2026-03-02', 'Feriado', '#dc2626', 'Global', 1, NOW()),
('NATAL (Grelha Nov/Mar)', 'Caixa de Texto "NATAL"', '2026-03-03', 'Feriado', '#dc2626', 'Global', 1, NOW()),
('HERÓIS (Grelha Mar)', 'Caixa de Texto "HERÓIS"', '2026-03-19', 'Feriado', '#ca8a04', 'Global', 1, NOW()),
('HERÓIS (Grelha Mar)', 'Caixa de Texto "HERÓIS"', '2026-03-20', 'Feriado', '#ca8a04', 'Global', 1, NOW()),
('PÁSCOA (Caixa de Texto)', 'Caixa Dominante "PÁSCOA"', '2026-04-01', 'Férias', '#1d4ed8', 'Global', 1, NOW()),
('PÁSCOA (Caixa de Texto)', 'Caixa Dominante "PÁSCOA"', '2026-04-02', 'Férias', '#1d4ed8', 'Global', 1, NOW()),
('PÁSCOA (Caixa de Texto)', 'Caixa Dominante "PÁSCOA"', '2026-04-03', 'Férias', '#1d4ed8', 'Global', 1, NOW()),
('PÁSCOA (Caixa de Texto)', 'Caixa Dominante "PÁSCOA"', '2026-04-04', 'Férias', '#1d4ed8', 'Global', 1, NOW()),
('PÁSCOA (Caixa de Texto)', 'Caixa Dominante "PÁSCOA"', '2026-04-05', 'Férias', '#1d4ed8', 'Global', 1, NOW()),
('PÁSCOA (Caixa de Texto)', 'Caixa Dominante "PÁSCOA"', '2026-04-06', 'Férias', '#1d4ed8', 'Global', 1, NOW()),
('PÁSCOA (Caixa de Texto)', 'Caixa Dominante "PÁSCOA"', '2026-04-07', 'Férias', '#1d4ed8', 'Global', 1, NOW()),
('HERÓIS (Grelha Maio)', 'Caixa de Texto "HERÓIS"', '2026-05-26', 'Feriado', '#ca8a04', 'Global', 1, NOW()),
('HERÓIS (Grelha Maio)', 'Caixa de Texto "HERÓIS"', '2026-05-27', 'Feriado', '#ca8a04', 'Global', 1, NOW()),
('HERÓIS (Grelha Maio)', 'Caixa de Texto "HERÓIS"', '2026-05-28', 'Feriado', '#ca8a04', 'Global', 1, NOW()),
('HERÓIS (Grelha Julho)', 'Caixa de Texto "HERÓIS"', '2026-07-15', 'Feriado', '#ca8a04', 'Global', 1, NOW()),
('HERÓIS (Grelha Julho)', 'Caixa de Texto "HERÓIS"', '2026-07-16', 'Feriado', '#ca8a04', 'Global', 1, NOW()),

-- Datas Oficiais da Legenda Inferior (Para consistência)
('Fim do 1º Semestre', 'Registo Oficial', '2026-03-06', 'Institucional', '#475569', 'Global', 1, NOW()),
('Fim do 2º Semestre', 'Registo Oficial', '2026-03-22', 'Institucional', '#475569', 'Global', 1, NOW()),
('FIM DO ANO LECTIVO', 'Encerramento Total Letivo', '2026-07-22', 'Institucional', '#475569', 'Global', 1, NOW()),
('Dia do Natal (Legenda)', 'Feriado Nacional: Dia de Natal', '2025-12-25', 'Feriado', '#b91c1c', 'Global', 1, NOW()),
('Dia do Novo Avo (Legenda)', 'Feriado Nacional', '2026-01-01', 'Feriado', '#b91c1c', 'Global', 1, NOW()),
('Dia dos Heróis Nacionais (Legenda)', 'Feriado Nacional', '2026-01-20', 'Feriado', '#b91c1c', 'Global', 1, NOW()),
('Dia dos Professores (Legenda)', 'Homenagem', '2026-02-17', 'Oficial', '#2563eb', 'Global', 1, NOW()),
('Dia da Mulher (Legenda)', 'Feriado Nacional', '2026-01-08', 'Feriado', '#b91c1c', 'Global', 1, NOW()),
('Ramadão (Legenda)', 'Feriado', '2026-03-20', 'Feriado', '#b91c1c', 'Global', 1, NOW()),
('Páscoa (Legenda)', 'Feriado', '2026-04-05', 'Feriado', '#b91c1c', 'Global', 1, NOW()),
('Dia dos Trabalhadores (Legenda)', 'Feriado', '2026-05-01', 'Feriado', '#b91c1c', 'Global', 1, NOW()),
('Tabaski (Legenda)', 'Feriado', '2026-05-27', 'Feriado', '#b91c1c', 'Global', 1, NOW());
