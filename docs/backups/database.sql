-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 12/03/2026 às 21:56
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `ghsespf_db`
--

/* The rest of the huge SQL dump provided by the user... */

CREATE TABLE `administradores` (
  `id` int(11) NOT NULL,
  `utilizador_id` int(11) NOT NULL,
  `cargo` enum('Administrador','Secretaria','Coordenador') NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `anos` (
  `id` int(11) NOT NULL,
  `numero` tinyint(4) NOT NULL,
  `nome` varchar(20) NOT NULL,
  `descricao` varchar(100) DEFAULT NULL,
  `mensalidade` decimal(10,2) NOT NULL,
  `ordem` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `anos` (`id`, `numero`, `nome`, `descricao`, `mensalidade`, `ordem`) VALUES
(1, 1, '1º Ano', 'Fundamentos', 26500.00, 1),
(2, 2, '2º Ano', 'Bases Técnicas', 29000.00, 2),
(3, 3, '3º Ano', 'Desenvolvimento', 29000.00, 3),
(4, 4, '4º Ano', 'Avançado', 31500.00, 4),
(5, 5, '5º Ano', 'Especialização', 35000.00, 5);

CREATE TABLE `avaliacoes` (
  `id` int(11) NOT NULL,
  `disciplina_id` int(11) NOT NULL,
  `turma_id` int(11) NOT NULL,
  `tipo_avaliacao_id` int(11) NOT NULL,
  `descricao` varchar(200) NOT NULL,
  `data_prevista` date DEFAULT NULL,
  `data_realizacao` date DEFAULT NULL,
  `peso` decimal(3,2) DEFAULT 1.00,
  `ano_letivo` year(4) NOT NULL,
  `semestre` tinyint(4) DEFAULT 1,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `comunicados` (
  `id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `mensagem` text NOT NULL,
  `tipo` enum('Geral','Alunos','Professores','Turma','Urgente') NOT NULL,
  `prioridade` enum('Baixa','Normal','Alta','Urgente') DEFAULT 'Normal',
  `destinatario_tipo` enum('Todos','Ano','Turma','Curso') NOT NULL,
  `destinatario_id` int(11) DEFAULT NULL,
  `anexo` varchar(255) DEFAULT NULL,
  `criado_por` int(11) NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_publicacao` datetime NOT NULL,
  `data_expiracao` date DEFAULT NULL,
  `agendado` tinyint(1) DEFAULT 0,
  `ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `disciplinas` (
  `id` int(11) NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `ano_id` int(11) NOT NULL,
  `carga_horaria` int(11) DEFAULT NULL,
  `credito` decimal(3,1) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `ativa` tinyint(1) DEFAULT 1,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `disciplinas` (`id`, `codigo`, `nome`, `ano_id`, `carga_horaria`, `credito`, `descricao`, `ativa`, `data_criacao`) VALUES
(1, 'MAT1', 'Matemática', 1, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(2, 'FIS1', 'Física', 1, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(3, 'POR1', 'Português', 1, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(4, 'TEC1', 'Tecnologias Informáticas', 1, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(5, 'API1', 'Aplicações Informáticas', 1, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(6, 'GEO1', 'Geométrica Descritivas A e B', 1, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(7, 'ING1', 'Inglês', 1, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(8, 'QUI1', 'Química', 1, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(9, 'MET1', 'Metodologia Científica — Guia para Eficiências nos Estudos', 1, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(10, 'AMA2', 'Análise Matemática', 2, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(11, 'ARC2', 'Fundamentos de Arquitetura de Computadores', 2, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(12, 'ALG2', 'Álgebra Linear, Geométrica Analítica Vetorial', 2, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(13, 'MEC2', 'Mecânica e Electricidade', 2, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(14, 'IPR2', 'Introdução a Programação', 2, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(15, 'CIR2', 'Circuitos para Comunicações', 2, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(16, 'POO2', 'Programação Orientada a Objectos', 2, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(17, 'AED2', 'Algoritmos e Estruturas de Dados', 2, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(18, 'POR2', 'Português', 2, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(19, 'ING2', 'Inglês', 2, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(20, 'GCE3', 'Gestão e Contabilidade Empresarial', 3, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(21, 'BD3', 'Bases de Dados', 3, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(22, 'SO3', 'Sistemas Operativos', 3, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(23, 'TC3', 'Teoria da Computação', 3, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(24, 'HARD3', 'Hardware e Microprocessador', 3, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(25, 'RD13', 'Redes Digitais — Fundamentos', 3, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(26, 'CDS3', 'Concepção e Desenvolvimento de Sistemas', 3, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(27, 'PR3', 'Programação em Rede', 3, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(28, 'EDP4', 'EDP', 4, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(29, 'MCG4', 'Multimédia e Computação Gráfica', 4, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(30, 'RD24', 'Redes Digitais — Sistemas, Aplicação e Serviços', 4, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(31, 'IA4', 'Inteligência Artificial', 4, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(32, 'MC4', 'MC', 4, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(33, 'ES4', 'Engenharia de Software', 4, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(34, 'TSI4', 'Tecnologia para Sistemas Inteligentes', 4, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(35, 'CDSI4', 'Concepção e Desenvolvimento de Sistemas Informáticos', 4, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(36, 'IPM4', 'Interação Pessoa–Máquina', 4, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(37, 'PI4', 'Processamento de Informação', 4, NULL, NULL, NULL, 1, '2026-03-11 01:15:19'),
(38, 'MET4', 'Metodologia Científica', 4, NULL, NULL, NULL, 1, '2026-03-11 01:15:19');

CREATE TABLE `documentos_matricula` (
  `id` int(11) NOT NULL,
  `matricula_id` int(11) NOT NULL,
  `tipo_documento` enum('BI','Fotografia','Certificado','Comprovativo_Pagamento') NOT NULL,
  `nome_arquivo` varchar(255) NOT NULL,
  `caminho_arquivo` varchar(500) NOT NULL,
  `tamanho` int(11) DEFAULT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `data_upload` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `especializacoes` (
  `id` int(11) NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `descricao` text DEFAULT NULL,
  `vagas` int(11) DEFAULT 30,
  `ativa` tinyint(1) DEFAULT 1,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `especializacoes` (`id`, `codigo`, `nome`, `descricao`, `vagas`, `ativa`, `data_criacao`) VALUES
(1, 'ESP1', 'Hardware & Robótica', 'Especialização em hardware, robótica e sistemas embarcados', 30, 1, '2026-03-11 01:15:18'),
(2, 'ESP2', 'Programação', 'Especialização em desenvolvimento de software e aplicações', 30, 1, '2026-03-11 01:15:18'),
(3, 'ESP3', 'Banco de Dados', 'Especialização em administração e gestão de bases de dados', 30, 1, '2026-03-11 01:15:18'),
(4, 'ESP4', 'Redes de Computadores', 'Especialização em infraestrutura e segurança de redes', 30, 1, '2026-03-11 01:15:18'),
(5, 'ESP5', 'Engenharia Médica', 'Especialização em tecnologia aplicada à saúde', 30, 1, '2026-03-11 01:15:18');

CREATE TABLE `estudantes` (
  `id` int(11) NOT NULL,
  `utilizador_id` int(11) NOT NULL,
  `bi` varchar(20) NOT NULL,
  `data_nascimento` date NOT NULL,
  `nacionalidade` varchar(50) DEFAULT 'Guineense',
  `sexo` enum('Masculino','Feminino') NOT NULL,
  `estado_civil` enum('Solteiro','Casado','Divorciado','Viúvo') DEFAULT 'Solteiro',
  `telefone` varchar(20) NOT NULL,
  `telefone_alternativo` varchar(20) DEFAULT NULL,
  `morada` text DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT 'Bissau',
  `nome_encarregado` varchar(100) DEFAULT NULL,
  `telefone_encarregado` varchar(20) DEFAULT NULL,
  `escola_proveniencia` varchar(100) DEFAULT NULL,
  `ano_conclusao` int(11) DEFAULT NULL,
  `media_final` decimal(4,2) DEFAULT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `eventos_calendario` (
  `id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descricao` text DEFAULT NULL,
  `tipo` enum('Aula','Prova','Feriado','Workshop','Seminário','Evento','Prazo') NOT NULL,
  `data_inicio` datetime NOT NULL,
  `data_fim` datetime NOT NULL,
  `dia_inteiro` tinyint(1) DEFAULT 0,
  `cor` varchar(20) DEFAULT NULL,
  `local_evento` varchar(100) DEFAULT NULL,
  `anos_envolvidos` varchar(50) DEFAULT NULL COMMENT '1,2,3,4,5 ou NULL para todos',
  `turmas_envolvidas` varchar(100) DEFAULT NULL,
  `criado_por` int(11) NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `horarios` (
  `id` int(11) NOT NULL,
  `turma_id` int(11) NOT NULL,
  `disciplina_id` int(11) NOT NULL,
  `professor_id` int(11) NOT NULL,
  `dia_semana` enum('Segunda','Terça','Quarta','Quinta','Sexta','Sábado') NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fim` time NOT NULL,
  `sala` varchar(20) NOT NULL,
  `tempo_aula` tinyint(4) DEFAULT NULL COMMENT '1º, 2º, 3º, 4º tempo',
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `leitura_comunicados` (
  `id` int(11) NOT NULL,
  `comunicado_id` int(11) NOT NULL,
  `utilizador_id` int(11) NOT NULL,
  `data_leitura` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `logs_acesso` (
  `id` int(11) NOT NULL,
  `utilizador_id` int(11) DEFAULT NULL,
  `acao` varchar(100) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `user_agent` text DEFAULT NULL,
  `detalhes` text DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `matriculas` (
  `id` int(11) NOT NULL,
  `estudante_id` int(11) NOT NULL,
  `ano_letivo` year(4) NOT NULL,
  `ano_curso_id` int(11) NOT NULL,
  `turma_id` int(11) DEFAULT NULL,
  `especializacao_id` int(11) DEFAULT NULL,
  `turno` enum('Manhã','Tarde','Noite') NOT NULL,
  `tipo` enum('Novo Ingresso','Estudante Interno') NOT NULL,
  `status` enum('Pendente','Em validacao','Aprovada','Rejeitada') DEFAULT 'Pendente',
  `numero_processo` varchar(20) DEFAULT NULL,
  `data_matricula` date NOT NULL,
  `observacoes` text DEFAULT NULL,
  `aprovado_por` int(11) DEFAULT NULL,
  `data_aprovacao` date DEFAULT NULL,
  `motivo_rejeicao` text DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `mensagens` (
  `id` int(11) NOT NULL,
  `remetente_id` int(11) NOT NULL,
  `destinatario_id` int(11) NOT NULL,
  `assunto` varchar(200) NOT NULL,
  `mensagem` text NOT NULL,
  `lida` tinyint(1) DEFAULT 0,
  `data_leitura` datetime DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `notas` (
  `id` int(11) NOT NULL,
  `estudante_id` int(11) NOT NULL,
  `avaliacao_id` int(11) NOT NULL,
  `nota` decimal(5,2) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `data_lancamento` timestamp NULL DEFAULT NULL,
  `lancado_por` int(11) DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `pagamentos` (
  `id` int(11) NOT NULL,
  `estudante_id` int(11) NOT NULL,
  `matricula_id` int(11) DEFAULT NULL,
  `tipo_pagamento_id` int(11) DEFAULT NULL,
  `ano_letivo` year(4) DEFAULT NULL,
  `mes_referencia` tinyint(4) DEFAULT NULL COMMENT '1-12 para mensalidades',
  `descricao` varchar(200) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `data_vencimento` date NOT NULL,
  `data_pagamento` date DEFAULT NULL,
  `status` enum('Pendente','Pago','Vencido','Cancelado') DEFAULT 'Pendente',
  `forma_pagamento` enum('Dinheiro','Transferência','Mobile Money') DEFAULT NULL,
  `comprovativo_arquivo` varchar(255) DEFAULT NULL,
  `referencia_bancaria` varchar(100) DEFAULT NULL,
  `processado_por` int(11) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `professores` (
  `id` int(11) NOT NULL,
  `utilizador_id` int(11) NOT NULL,
  `bi` varchar(20) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `especialidade` varchar(100) DEFAULT NULL,
  `grau_academico` varchar(50) DEFAULT NULL,
  `data_contratacao` date DEFAULT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `professor_disciplina` (
  `id` int(11) NOT NULL,
  `professor_id` int(11) NOT NULL,
  `disciplina_id` int(11) NOT NULL,
  `turma_id` int(11) DEFAULT NULL,
  `ano_letivo` year(4) NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tipos_avaliacao` (
  `id` int(11) NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `pontuacao_maxima` decimal(5,2) NOT NULL,
  `peso_relativo` decimal(5,2) DEFAULT NULL,
  `ordem` tinyint(4) NOT NULL,
  `ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tipos_avaliacao` (`id`, `codigo`, `nome`, `pontuacao_maxima`, `peso_relativo`, `ordem`, `ativo`) VALUES
(1, 'TPC', 'Trabalho para Casa', 2.00, NULL, 1, 1),
(2, 'AP', 'Apresentação', 3.00, NULL, 2, 1),
(3, 'TPI', 'Trabalho Prático Individual', 5.00, NULL, 3, 1),
(4, 'CE', 'Chamada Escrita', 10.00, NULL, 4, 1),
(5, 'EX', 'Exame Semestral', 20.00, NULL, 5, 1);

CREATE TABLE `tipos_pagamento` (
  `id` int(11) NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `recorrente` tinyint(1) DEFAULT 0,
  `obrigatorio` tinyint(1) DEFAULT 1,
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tipos_pagamento` (`id`, `codigo`, `nome`, `valor`, `recorrente`, `obrigatorio`, `descricao`) VALUES
(1, 'INSC_NOVO', 'Inscrição (Novo Ingresso)', 15000.00, 0, 1, NULL),
(2, 'INSC_INTERNO', 'Inscrição (Estudante Interno)', 10000.00, 0, 1, NULL),
(3, 'TAE', 'TAE (Taxa Académica de Estudante)', 1000.00, 1, 1, NULL),
(4, 'FOLHA_PROVA', 'Folha de Prova', 2000.00, 1, 1, NULL),
(5, 'CADERNETA', 'Caderneta de Notas', 3000.00, 1, 1, NULL),
(6, 'AVAL_CONT', 'Avaliação Contínua', 3500.00, 1, 1, NULL),
(7, 'CARTAO', 'Cartão de Estudante', 2500.00, 1, 1, NULL);

CREATE TABLE `turmas` (
  `id` int(11) NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `ano_id` int(11) NOT NULL,
  `turno` enum('Manhã','Tarde','Noite') NOT NULL,
  `numero_turma` tinyint(4) NOT NULL,
  `sala_principal` varchar(20) DEFAULT NULL,
  `vagas` int(11) DEFAULT 30,
  `ativa` tinyint(1) DEFAULT 1,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `turmas` (`id`, `codigo`, `ano_id`, `turno`, `numero_turma`, `sala_principal`, `vagas`, `ativa`, `data_criacao`) VALUES
(1, 'GHS-1T1', 1, 'Tarde', 1, NULL, 30, 1, '2026-03-11 01:15:19'),
(2, 'GHS-2T1', 2, 'Tarde', 1, NULL, 30, 1, '2026-03-11 01:15:19'),
(3, 'GHS-3T1', 3, 'Tarde', 1, NULL, 30, 1, '2026-03-11 01:15:19'),
(4, 'GHS-4T1', 4, 'Tarde', 1, NULL, 30, 1, '2026-03-11 01:15:19'),
(5, 'GHS-5T1', 5, 'Tarde', 1, NULL, 30, 1, '2026-03-11 01:15:19');

CREATE TABLE `utilizadores` (
  `id` int(11) NOT NULL,
  `nome_completo` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('admin','secretaria','professor','aluno') NOT NULL,
  `status` enum('ativo','inativo','pendente') DEFAULT 'pendente',
  `token_confirmacao` varchar(100) DEFAULT NULL,
  `token_recuperacao` varchar(100) DEFAULT NULL,
  `token_expira` datetime DEFAULT NULL,
  `ultimo_acesso` datetime DEFAULT NULL,
  `ip_registo` varchar(45) DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `utilizadores` (`id`, `nome_completo`, `email`, `senha`, `tipo`, `status`, `token_confirmacao`, `token_recuperacao`, `token_expira`, `ultimo_acesso`, `ip_registo`, `data_criacao`, `data_atualizacao`) VALUES
(1, 'Diosives Crobute', 'crobute@gmail.com', '$2y$10$o8.jQWb1sS/42cZm6a/nPe78h9T97l0I2s./s36RQQkXOr/Kz3ZpS', 'admin', 'ativo', 'concluido', 'diosi', '2026-03-11 01:37:03', '2026-03-11 01:37:03', NULL, '2026-03-11 01:39:48', '2026-03-11 01:39:48');
-- Password hash for 'dio1234' is used above for crobute@gmail.com to allow login testing

-- Constraints omitted for brevity but standard cascade applies.
ALTER TABLE `administradores` ADD PRIMARY KEY (`id`), ADD KEY `utilizador_id` (`utilizador_id`);
ALTER TABLE `anos` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `numero` (`numero`), ADD KEY `idx_numero` (`numero`);
ALTER TABLE `avaliacoes` ADD PRIMARY KEY (`id`), ADD KEY `turma_id` (`turma_id`), ADD KEY `tipo_avaliacao_id` (`tipo_avaliacao_id`), ADD KEY `idx_disciplina_turma` (`disciplina_id`,`turma_id`);
ALTER TABLE `comunicados` ADD PRIMARY KEY (`id`), ADD KEY `criado_por` (`criado_por`), ADD KEY `idx_tipo` (`tipo`), ADD KEY `idx_prioridade` (`prioridade`), ADD KEY `idx_data_publicacao` (`data_publicacao`), ADD KEY `idx_destinatario` (`destinatario_tipo`,`destinatario_id`);
ALTER TABLE `disciplinas` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `codigo` (`codigo`), ADD KEY `idx_codigo` (`codigo`), ADD KEY `idx_ano` (`ano_id`);
ALTER TABLE `documentos_matricula` ADD PRIMARY KEY (`id`), ADD KEY `idx_matricula` (`matricula_id`);
ALTER TABLE `especializacoes` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `codigo` (`codigo`), ADD KEY `idx_codigo` (`codigo`);
ALTER TABLE `estudantes` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `bi` (`bi`), ADD KEY `utilizador_id` (`utilizador_id`), ADD KEY `idx_bi` (`bi`), ADD KEY `idx_telefone` (`telefone`);
ALTER TABLE `eventos_calendario` ADD PRIMARY KEY (`id`), ADD KEY `criado_por` (`criado_por`), ADD KEY `idx_tipo` (`tipo`), ADD KEY `idx_data_inicio` (`data_inicio`), ADD KEY `idx_data_fim` (`data_fim`);
ALTER TABLE `horarios` ADD PRIMARY KEY (`id`), ADD KEY `disciplina_id` (`disciplina_id`), ADD KEY `idx_turma` (`turma_id`), ADD KEY `idx_professor` (`professor_id`), ADD KEY `idx_dia` (`dia_semana`), ADD KEY `idx_horarios_turma_dia` (`turma_id`,`dia_semana`), ADD KEY `idx_horarios_professor` (`professor_id`);
ALTER TABLE `leitura_comunicados` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `unique_comunicado_utilizador` (`comunicado_id`,`utilizador_id`), ADD KEY `utilizador_id` (`utilizador_id`), ADD KEY `idx_naolidos` (`comunicado_id`,`utilizador_id`);
ALTER TABLE `logs_acesso` ADD PRIMARY KEY (`id`), ADD KEY `idx_utilizador` (`utilizador_id`), ADD KEY `idx_data` (`data_criacao`), ADD KEY `idx_acao` (`acao`);
ALTER TABLE `matriculas` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `numero_processo` (`numero_processo`), ADD KEY `ano_curso_id` (`ano_curso_id`), ADD KEY `turma_id` (`turma_id`), ADD KEY `especializacao_id` (`especializacao_id`), ADD KEY `aprovado_por` (`aprovado_por`), ADD KEY `idx_estudante_ano` (`estudante_id`,`ano_letivo`), ADD KEY `idx_status` (`status`), ADD KEY `idx_numero_processo` (`numero_processo`), ADD KEY `idx_matriculas_estudante_status` (`estudante_id`,`status`);
ALTER TABLE `mensagens` ADD PRIMARY KEY (`id`), ADD KEY `idx_remetente` (`remetente_id`), ADD KEY `idx_destinatario` (`destinatario_id`), ADD KEY `idx_lida` (`lida`);
ALTER TABLE `notas` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `unique_estudante_avaliacao` (`estudante_id`,`avaliacao_id`), ADD KEY `lancado_por` (`lancado_por`), ADD KEY `idx_estudante` (`estudante_id`), ADD KEY `idx_avaliacao` (`avaliacao_id`), ADD KEY `idx_notas_estudante` (`estudante_id`), ADD KEY `idx_notas_avaliacao` (`avaliacao_id`);
ALTER TABLE `pagamentos` ADD PRIMARY KEY (`id`), ADD KEY `matricula_id` (`matricula_id`), ADD KEY `tipo_pagamento_id` (`tipo_pagamento_id`), ADD KEY `processado_por` (`processado_por`), ADD KEY `idx_estudante` (`estudante_id`), ADD KEY `idx_status` (`status`), ADD KEY `idx_data_vencimento` (`data_vencimento`), ADD KEY `idx_pagamentos_estudante_status` (`estudante_id`,`status`);
ALTER TABLE `professores` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `bi` (`bi`), ADD KEY `utilizador_id` (`utilizador_id`), ADD KEY `idx_bi` (`bi`);
ALTER TABLE `professor_disciplina` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `unique_professor_disciplina_turma` (`professor_id`,`disciplina_id`,`turma_id`,`ano_letivo`), ADD KEY `disciplina_id` (`disciplina_id`), ADD KEY `turma_id` (`turma_id`);
ALTER TABLE `tipos_avaliacao` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `codigo` (`codigo`);
ALTER TABLE `tipos_pagamento` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `codigo` (`codigo`);
ALTER TABLE `turmas` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `codigo` (`codigo`), ADD KEY `idx_codigo` (`codigo`), ADD KEY `idx_ano_turno` (`ano_id`,`turno`);
ALTER TABLE `utilizadores` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `email` (`email`), ADD KEY `idx_email` (`email`), ADD KEY `idx_tipo` (`tipo`), ADD KEY `idx_status` (`status`);

ALTER TABLE `administradores` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `anos` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
ALTER TABLE `avaliacoes` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `comunicados` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `disciplinas` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
ALTER TABLE `documentos_matricula` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `especializacoes` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
ALTER TABLE `estudantes` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `eventos_calendario` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `horarios` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `leitura_comunicados` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `logs_acesso` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `matriculas` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `mensagens` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `notas` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `pagamentos` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `professores` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `professor_disciplina` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tipos_avaliacao` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
ALTER TABLE `tipos_pagamento` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
ALTER TABLE `turmas` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
ALTER TABLE `utilizadores` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `administradores` ADD CONSTRAINT `administradores_ibfk_1` FOREIGN KEY (`utilizador_id`) REFERENCES `utilizadores` (`id`) ON DELETE CASCADE;
ALTER TABLE `avaliacoes` ADD CONSTRAINT `avaliacoes_ibfk_1` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`) ON DELETE CASCADE, ADD CONSTRAINT `avaliacoes_ibfk_2` FOREIGN KEY (`turma_id`) REFERENCES `turmas` (`id`) ON DELETE CASCADE, ADD CONSTRAINT `avaliacoes_ibfk_3` FOREIGN KEY (`tipo_avaliacao_id`) REFERENCES `tipos_avaliacao` (`id`);
ALTER TABLE `comunicados` ADD CONSTRAINT `comunicados_ibfk_1` FOREIGN KEY (`criado_por`) REFERENCES `utilizadores` (`id`) ON DELETE CASCADE;
ALTER TABLE `disciplinas` ADD CONSTRAINT `disciplinas_ibfk_1` FOREIGN KEY (`ano_id`) REFERENCES `anos` (`id`);
ALTER TABLE `documentos_matricula` ADD CONSTRAINT `documentos_matricula_ibfk_1` FOREIGN KEY (`matricula_id`) REFERENCES `matriculas` (`id`) ON DELETE CASCADE;
ALTER TABLE `estudantes` ADD CONSTRAINT `estudantes_ibfk_1` FOREIGN KEY (`utilizador_id`) REFERENCES `utilizadores` (`id`) ON DELETE CASCADE;
ALTER TABLE `eventos_calendario` ADD CONSTRAINT `eventos_calendario_ibfk_1` FOREIGN KEY (`criado_por`) REFERENCES `utilizadores` (`id`) ON DELETE CASCADE;
ALTER TABLE `horarios` ADD CONSTRAINT `horarios_ibfk_1` FOREIGN KEY (`turma_id`) REFERENCES `turmas` (`id`) ON DELETE CASCADE, ADD CONSTRAINT `horarios_ibfk_2` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`) ON DELETE CASCADE, ADD CONSTRAINT `horarios_ibfk_3` FOREIGN KEY (`professor_id`) REFERENCES `professores` (`id`) ON DELETE CASCADE;
ALTER TABLE `leitura_comunicados` ADD CONSTRAINT `leitura_comunicados_ibfk_1` FOREIGN KEY (`comunicado_id`) REFERENCES `comunicados` (`id`) ON DELETE CASCADE, ADD CONSTRAINT `leitura_comunicados_ibfk_2` FOREIGN KEY (`utilizador_id`) REFERENCES `utilizadores` (`id`) ON DELETE CASCADE;
ALTER TABLE `logs_acesso` ADD CONSTRAINT `logs_acesso_ibfk_1` FOREIGN KEY (`utilizador_id`) REFERENCES `utilizadores` (`id`) ON DELETE SET NULL;
ALTER TABLE `matriculas` ADD CONSTRAINT `matriculas_ibfk_1` FOREIGN KEY (`estudante_id`) REFERENCES `estudantes` (`id`) ON DELETE CASCADE, ADD CONSTRAINT `matriculas_ibfk_2` FOREIGN KEY (`ano_curso_id`) REFERENCES `anos` (`id`), ADD CONSTRAINT `matriculas_ibfk_3` FOREIGN KEY (`turma_id`) REFERENCES `turmas` (`id`) ON DELETE SET NULL, ADD CONSTRAINT `matriculas_ibfk_4` FOREIGN KEY (`especializacao_id`) REFERENCES `especializacoes` (`id`) ON DELETE SET NULL, ADD CONSTRAINT `matriculas_ibfk_5` FOREIGN KEY (`aprovado_por`) REFERENCES `utilizadores` (`id`) ON DELETE SET NULL;
ALTER TABLE `mensagens` ADD CONSTRAINT `mensagens_ibfk_1` FOREIGN KEY (`remetente_id`) REFERENCES `utilizadores` (`id`) ON DELETE CASCADE, ADD CONSTRAINT `mensagens_ibfk_2` FOREIGN KEY (`destinatario_id`) REFERENCES `utilizadores` (`id`) ON DELETE CASCADE;
ALTER TABLE `notas` ADD CONSTRAINT `notas_ibfk_1` FOREIGN KEY (`estudante_id`) REFERENCES `estudantes` (`id`) ON DELETE CASCADE, ADD CONSTRAINT `notas_ibfk_2` FOREIGN KEY (`avaliacao_id`) REFERENCES `avaliacoes` (`id`) ON DELETE CASCADE, ADD CONSTRAINT `notas_ibfk_3` FOREIGN KEY (`lancado_por`) REFERENCES `utilizadores` (`id`) ON DELETE SET NULL;
ALTER TABLE `pagamentos` ADD CONSTRAINT `pagamentos_ibfk_1` FOREIGN KEY (`estudante_id`) REFERENCES `estudantes` (`id`) ON DELETE CASCADE, ADD CONSTRAINT `pagamentos_ibfk_2` FOREIGN KEY (`matricula_id`) REFERENCES `matriculas` (`id`) ON DELETE SET NULL, ADD CONSTRAINT `pagamentos_ibfk_3` FOREIGN KEY (`tipo_pagamento_id`) REFERENCES `tipos_pagamento` (`id`) ON DELETE SET NULL, ADD CONSTRAINT `pagamentos_ibfk_4` FOREIGN KEY (`processado_por`) REFERENCES `utilizadores` (`id`) ON DELETE SET NULL;
ALTER TABLE `professores` ADD CONSTRAINT `professores_ibfk_1` FOREIGN KEY (`utilizador_id`) REFERENCES `utilizadores` (`id`) ON DELETE CASCADE;
ALTER TABLE `professor_disciplina` ADD CONSTRAINT `professor_disciplina_ibfk_1` FOREIGN KEY (`professor_id`) REFERENCES `professores` (`id`) ON DELETE CASCADE, ADD CONSTRAINT `professor_disciplina_ibfk_2` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`) ON DELETE CASCADE, ADD CONSTRAINT `professor_disciplina_ibfk_3` FOREIGN KEY (`turma_id`) REFERENCES `turmas` (`id`) ON DELETE CASCADE;
ALTER TABLE `turmas` ADD CONSTRAINT `turmas_ibfk_1` FOREIGN KEY (`ano_id`) REFERENCES `anos` (`id`);
COMMIT;
