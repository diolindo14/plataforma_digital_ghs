-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: ghsespf_db
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `administradores`
--

DROP TABLE IF EXISTS `administradores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `administradores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utilizador_id` int(11) NOT NULL,
  `cargo` enum('Administrador','Secretaria','Coordenador') NOT NULL,
  `bi` varchar(20) DEFAULT NULL,
  `telefone` varchar(20) NOT NULL,
  `data_contratacao` date DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `utilizador_id` (`utilizador_id`),
  CONSTRAINT `administradores_ibfk_1` FOREIGN KEY (`utilizador_id`) REFERENCES `utilizadores` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `administradores`
--

LOCK TABLES `administradores` WRITE;
/*!40000 ALTER TABLE `administradores` DISABLE KEYS */;
INSERT INTO `administradores` VALUES (2,177,'Secretaria','007654','9566876','2026-04-01','2026-04-01 04:16:22','2026-04-04 03:26:06');
/*!40000 ALTER TABLE `administradores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `anos`
--

DROP TABLE IF EXISTS `anos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `anos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero` tinyint(4) NOT NULL,
  `nome` varchar(20) NOT NULL,
  `descricao` varchar(100) DEFAULT NULL,
  `mensalidade` decimal(10,2) NOT NULL,
  `ordem` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero` (`numero`),
  KEY `idx_numero` (`numero`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `anos`
--

LOCK TABLES `anos` WRITE;
/*!40000 ALTER TABLE `anos` DISABLE KEYS */;
INSERT INTO `anos` VALUES (1,1,'1º Ano','Fundamentos',26500.00,1),(2,2,'2º Ano','Bases Técnicas',29000.00,2),(3,3,'3º Ano','Desenvolvimento',29000.00,3),(4,4,'4º Ano','Avançado',31500.00,4),(5,5,'5º Ano','Especialização',35000.00,5);
/*!40000 ALTER TABLE `anos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `assiduidade_professores`
--

DROP TABLE IF EXISTS `assiduidade_professores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assiduidade_professores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `professor_id` int(11) DEFAULT NULL,
  `turma_id` int(11) DEFAULT NULL,
  `disciplina_id` int(11) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `tempo` varchar(20) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `justificacao` text DEFAULT NULL,
  `marcado_por` int(11) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT curdate(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uidx_prof_sessao` (`professor_id`,`turma_id`,`disciplina_id`,`data`,`tempo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assiduidade_professores`
--

LOCK TABLES `assiduidade_professores` WRITE;
/*!40000 ALTER TABLE `assiduidade_professores` DISABLE KEYS */;
/*!40000 ALTER TABLE `assiduidade_professores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `avaliacoes`
--

DROP TABLE IF EXISTS `avaliacoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `avaliacoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `disciplina_id` int(11) NOT NULL,
  `turma_id` int(11) NOT NULL,
  `tipo_avaliacao_id` int(11) NOT NULL,
  `descricao` varchar(200) NOT NULL,
  `data_prevista` date DEFAULT NULL,
  `data_realizacao` date DEFAULT NULL,
  `peso` decimal(3,2) DEFAULT 1.00,
  `ano_letivo` year(4) NOT NULL,
  `semestre` tinyint(4) DEFAULT 1,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `turma_id` (`turma_id`),
  KEY `tipo_avaliacao_id` (`tipo_avaliacao_id`),
  KEY `idx_disciplina_turma` (`disciplina_id`,`turma_id`),
  CONSTRAINT `avaliacoes_ibfk_1` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `avaliacoes_ibfk_2` FOREIGN KEY (`turma_id`) REFERENCES `turmas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `avaliacoes_ibfk_3` FOREIGN KEY (`tipo_avaliacao_id`) REFERENCES `tipos_avaliacao` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `avaliacoes`
--

LOCK TABLES `avaliacoes` WRITE;
/*!40000 ALTER TABLE `avaliacoes` DISABLE KEYS */;
INSERT INTO `avaliacoes` VALUES (1,30,7,1,'Lançamento Automático',NULL,NULL,1.00,0000,1,'2026-04-01 05:16:47'),(2,30,7,2,'Lançamento Automático',NULL,NULL,1.00,0000,1,'2026-04-01 05:16:47'),(3,30,7,3,'Lançamento Automático',NULL,NULL,1.00,0000,1,'2026-04-01 05:16:47'),(4,30,7,4,'Lançamento Automático',NULL,NULL,1.00,0000,1,'2026-04-01 05:16:47'),(5,30,7,5,'Lançamento Automático',NULL,NULL,1.00,0000,1,'2026-04-01 05:16:47'),(6,29,7,1,'Lançamento Automático',NULL,NULL,1.00,0000,1,'2026-04-02 01:23:36'),(7,29,7,2,'Lançamento Automático',NULL,NULL,1.00,0000,1,'2026-04-02 01:23:36'),(8,29,7,3,'Lançamento Automático',NULL,NULL,1.00,0000,1,'2026-04-02 01:23:36'),(9,29,7,4,'Lançamento Automático',NULL,NULL,1.00,0000,1,'2026-04-02 01:23:36');
/*!40000 ALTER TABLE `avaliacoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `certificados_merito`
--

DROP TABLE IF EXISTS `certificados_merito`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `certificados_merito` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `estudante_id` int(11) NOT NULL,
  `semestre` enum('1','2') NOT NULL,
  `ano_letivo` varchar(10) NOT NULL,
  `posicao` enum('1','2') NOT NULL,
  `media` decimal(5,2) NOT NULL,
  `nivel_nome` varchar(150) DEFAULT NULL,
  `emitido_por` int(11) DEFAULT NULL,
  `data_emissao` timestamp NOT NULL DEFAULT current_timestamp(),
  `assinatura_diretor` text DEFAULT NULL,
  `assinatura_secretaria` text DEFAULT NULL,
  `status` enum('Pendente','Publicado') DEFAULT 'Pendente',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_cert` (`estudante_id`,`semestre`,`ano_letivo`),
  KEY `idx_estudante` (`estudante_id`),
  KEY `idx_semestre_ano` (`semestre`,`ano_letivo`),
  CONSTRAINT `fk_cert_estudante` FOREIGN KEY (`estudante_id`) REFERENCES `estudantes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `certificados_merito`
--

LOCK TABLES `certificados_merito` WRITE;
/*!40000 ALTER TABLE `certificados_merito` DISABLE KEYS */;
/*!40000 ALTER TABLE `certificados_merito` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comunicados`
--

DROP TABLE IF EXISTS `comunicados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comunicados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `ativo` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `criado_por` (`criado_por`),
  KEY `idx_tipo` (`tipo`),
  KEY `idx_prioridade` (`prioridade`),
  KEY `idx_data_publicacao` (`data_publicacao`),
  KEY `idx_destinatario` (`destinatario_tipo`,`destinatario_id`),
  CONSTRAINT `comunicados_ibfk_1` FOREIGN KEY (`criado_por`) REFERENCES `utilizadores` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comunicados`
--

LOCK TABLES `comunicados` WRITE;
/*!40000 ALTER TABLE `comunicados` DISABLE KEYS */;
/*!40000 ALTER TABLE `comunicados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comunicados_leituras`
--

DROP TABLE IF EXISTS `comunicados_leituras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comunicados_leituras` (
  `comunicado_id` int(11) NOT NULL,
  `utilizador_id` int(11) NOT NULL,
  `data_leitura` datetime NOT NULL,
  PRIMARY KEY (`comunicado_id`,`utilizador_id`),
  KEY `utilizador_id` (`utilizador_id`),
  CONSTRAINT `comunicados_leituras_ibfk_1` FOREIGN KEY (`comunicado_id`) REFERENCES `comunicados` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comunicados_leituras_ibfk_2` FOREIGN KEY (`utilizador_id`) REFERENCES `utilizadores` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comunicados_leituras`
--

LOCK TABLES `comunicados_leituras` WRITE;
/*!40000 ALTER TABLE `comunicados_leituras` DISABLE KEYS */;
/*!40000 ALTER TABLE `comunicados_leituras` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `concordancia_notas`
--

DROP TABLE IF EXISTS `concordancia_notas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `concordancia_notas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `estudante_id` int(11) NOT NULL,
  `turma_id` int(11) NOT NULL,
  `disciplina_id` int(11) NOT NULL,
  `status` enum('Pendente','Respondido','Resolvido','Impasse','Em_Mediacao','Aguardando_Comparecimento','Encerrado','Concordado','Reclamado') NOT NULL DEFAULT 'Pendente',
  `comentario` text DEFAULT NULL,
  `resposta_professor` text DEFAULT NULL,
  `contra_argumentacao` text DEFAULT NULL,
  `data_resposta` datetime DEFAULT current_timestamp(),
  `data_abertura` datetime DEFAULT NULL,
  `data_impasse` datetime DEFAULT NULL,
  `data_escalacao` datetime DEFAULT NULL,
  `data_reuniao` date DEFAULT NULL,
  `hora_reuniao` time DEFAULT NULL,
  `local_reuniao` varchar(150) DEFAULT NULL,
  `motivo_convocacao` text DEFAULT NULL,
  `decisao_final` text DEFAULT NULL,
  `mediado_por` int(11) DEFAULT NULL,
  `presenca_aluno` tinyint(1) DEFAULT NULL,
  `presenca_professor` tinyint(1) DEFAULT NULL,
  `data_decisao` datetime DEFAULT NULL,
  `contador_reclamacoes` int(11) DEFAULT 0,
  `bloqueado_admin` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `estudante_id` (`estudante_id`,`turma_id`,`disciplina_id`),
  KEY `turma_id` (`turma_id`),
  KEY `disciplina_id` (`disciplina_id`),
  KEY `idx_cn_status_mediacao` (`status`,`bloqueado_admin`),
  KEY `idx_cn_status` (`status`,`bloqueado_admin`),
  CONSTRAINT `concordancia_notas_ibfk_1` FOREIGN KEY (`estudante_id`) REFERENCES `estudantes` (`id`),
  CONSTRAINT `concordancia_notas_ibfk_2` FOREIGN KEY (`turma_id`) REFERENCES `turmas` (`id`),
  CONSTRAINT `concordancia_notas_ibfk_3` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `concordancia_notas`
--

LOCK TABLES `concordancia_notas` WRITE;
/*!40000 ALTER TABLE `concordancia_notas` DISABLE KEYS */;
INSERT INTO `concordancia_notas` VALUES (1,135,7,30,'Encerrado','Não concordo com a nota atribuida','Não há irregularidade na sua nota','Eu discordo','2026-04-01 15:52:01','2026-04-01 12:45:44','2026-04-01 12:49:05','2026-04-01 12:49:05',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0),(2,136,7,30,'Encerrado','A minha nota nata da CE não é essa','Não houve a irregularidade na sua nota.','Como assim?','2026-04-01 06:35:34','2026-04-01 06:33:52','2026-04-01 06:36:45','2026-04-01 06:36:45','2026-04-13','13:00:00','Gabinete de Apoio Psicopedagógico (GAP)','Convocatória obrigatória para resolução de impasse em avaliação académica.','A administração decidiu que o aluno tem razão',1,1,1,'2026-04-01 07:02:42',1,0),(3,135,7,29,'Resolvido','Não vejo a media de exame do primeiro semestre.','','Cade as nota do exame?','2026-04-02 01:30:09','2026-04-02 01:24:49','2026-04-02 01:31:04','2026-04-02 01:31:04',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0),(4,137,7,30,'Encerrado','Protesto essa nota.','Não há irregularidade','Discordo','2026-04-04 01:11:03','2026-04-03 09:49:21','2026-04-03 09:52:38','2026-04-03 09:52:38',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0);
/*!40000 ALTER TABLE `concordancia_notas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `disciplinas`
--

DROP TABLE IF EXISTS `disciplinas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `disciplinas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `ano_id` int(11) NOT NULL,
  `carga_horaria` int(11) DEFAULT NULL,
  `credito` decimal(3,1) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `ativa` tinyint(1) DEFAULT 1,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `idx_codigo` (`codigo`),
  KEY `idx_ano` (`ano_id`),
  CONSTRAINT `disciplinas_ibfk_1` FOREIGN KEY (`ano_id`) REFERENCES `anos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `disciplinas`
--

LOCK TABLES `disciplinas` WRITE;
/*!40000 ALTER TABLE `disciplinas` DISABLE KEYS */;
INSERT INTO `disciplinas` VALUES (1,'MAT1','Matemática',1,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(2,'FIS1','Física',1,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(3,'POR1','Português',1,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(4,'TEC1','Tecnologias Informáticas',1,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(5,'API1','Aplicações Informáticas',1,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(6,'GEO1','Geométrica Descritivas A e B',1,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(7,'ING1','Inglês',1,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(8,'QUI1','Química',1,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(9,'MET1','Metodologia Científica — Guia para Eficiências nos Estudos',1,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(10,'AMA2','Análise Matemática',2,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(11,'ARC2','Fundamentos de Arquitetura de Computadores',2,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(12,'ALG2','Álgebra Linear, Geométrica Analítica Vetorial',2,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(13,'MEC2','Mecânica e Electricidade',2,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(14,'IPR2','Introdução a Programação',2,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(15,'CIR2','Circuitos para Comunicações',2,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(16,'POO2','Programação Orientada a Objectos',2,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(17,'AED2','Algoritmos e Estruturas de Dados',2,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(18,'POR2','Português',2,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(19,'ING2','Inglês',2,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(20,'GCE3','Gestão e Contabilidade Empresarial',3,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(21,'BD3','Bases de Dados',3,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(22,'SO3','Sistemas Operativos',3,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(23,'TC3','Teoria da Computação',3,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(24,'HARD3','Hardware e Microprocessador',3,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(25,'RD13','Redes Digitais — Fundamentos',3,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(26,'CDS3','Concepção e Desenvolvimento de Sistemas',3,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(27,'PR3','Programação em Rede - HTML e CSS',3,2,0.0,'',1,'2026-03-11 01:15:19'),(28,'EDP4','EDP',4,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(29,'MCG4','Multimédia e Computação Gráfica',4,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(30,'RD24','Redes Digitais — Sistemas, Aplicação e Serviços',4,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(31,'IA4','Inteligência Artificial',4,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(32,'MC4','MC',4,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(33,'ES4','Engenharia de Software',4,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(34,'TSI4','Tecnologia para Sistemas Inteligentes',4,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(35,'CDSI4','Concepção e Desenvolvimento de Sistemas Informáticos',4,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(36,'IPM4','Interação Pessoa–Máquina',4,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(37,'PI4','Processamento de Informação',4,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(38,'MET4','Metodologia Científica',4,NULL,NULL,NULL,1,'2026-03-11 01:15:19'),(39,'','Ética e Deontologia Profissional',4,NULL,NULL,NULL,1,'2026-03-22 21:49:47'),(40,'IGE','Introdução à Gestão Empresarial',1,NULL,NULL,NULL,1,'2026-03-24 17:46:56'),(41,'JAVASCR','Programação em Rede - JavaScript',3,NULL,NULL,NULL,1,'2026-03-24 17:46:56'),(42,'PHP','Programação em Rede - PHP',3,NULL,NULL,NULL,1,'2026-03-24 17:46:56'),(43,'RD2','Redes Digitais — Sistemas e Serviços',4,NULL,NULL,NULL,1,'2026-03-24 17:46:56'),(44,'SID','Sistemas de Informação Distribuídas',4,NULL,NULL,NULL,1,'2026-03-24 17:46:56'),(45,'SQLSRV','Administração de SQL Server',5,NULL,NULL,NULL,1,'2026-03-24 17:46:56'),(46,'AO','Arquitetura de Operações',5,NULL,NULL,NULL,1,'2026-03-24 17:46:56'),(47,'JAVASTD','Programação Java Standard',5,NULL,NULL,NULL,1,'2026-03-24 17:46:56'),(48,'VBNET','Programação em VB.NET',5,NULL,NULL,NULL,1,'2026-03-24 17:46:56'),(49,'MA','Manutenção Avançada',5,NULL,NULL,NULL,1,'2026-03-24 17:46:56'),(50,'IS','Infraestrutura de Servidores',5,NULL,NULL,NULL,1,'2026-03-24 17:46:56'),(51,'AD','Active Directory',5,NULL,NULL,NULL,1,'2026-03-24 17:46:56'),(52,'SR','Segurança de Redes',5,NULL,NULL,NULL,1,'2026-03-24 17:46:56'),(53,'LINUX','Administração Linux',5,NULL,NULL,NULL,1,'2026-03-24 17:46:56'),(54,'WT','Web Technologies',5,NULL,NULL,NULL,1,'2026-03-24 17:46:56'),(55,'QUIM','QUIM',1,60,NULL,NULL,1,'2026-03-24 20:46:28'),(56,'TI','TI',1,60,NULL,NULL,1,'2026-03-24 20:46:28'),(57,'POR','POR',1,60,NULL,NULL,1,'2026-03-24 20:46:28'),(58,'FIS','FIS',1,60,NULL,NULL,1,'2026-03-24 20:46:28'),(59,'MAT','MAT',1,60,NULL,NULL,1,'2026-03-24 20:46:28'),(60,'ING','ING',1,60,NULL,NULL,1,'2026-03-24 20:46:28'),(61,'APL','APL',1,60,NULL,NULL,1,'2026-03-24 20:46:28'),(62,'GDA','GDA',1,60,NULL,NULL,1,'2026-03-24 20:46:28'),(63,'AED','AED',2,60,NULL,NULL,1,'2026-03-24 20:46:28'),(64,'POO','POO',2,60,NULL,NULL,1,'2026-03-24 20:46:28'),(65,'ALGA','ALGA',2,60,NULL,NULL,1,'2026-03-24 20:46:28'),(66,'ECC','ECC',2,60,NULL,NULL,1,'2026-03-24 20:46:28'),(67,'HM','HM',3,60,NULL,NULL,1,'2026-03-24 20:46:28'),(68,'FBD','FBD',3,60,NULL,NULL,1,'2026-03-24 20:46:28'),(69,'TC','TC',3,60,NULL,NULL,1,'2026-03-24 20:46:28'),(70,'RD1','RD1',3,60,NULL,NULL,1,'2026-03-24 20:46:28'),(71,'CDSI','CDSI',3,60,NULL,NULL,1,'2026-03-24 20:46:28'),(72,'JS','JS',3,60,NULL,NULL,1,'2026-03-24 20:46:28'),(73,'SO','SO',3,60,NULL,NULL,1,'2026-03-24 20:46:28'),(74,'IA','IA',4,60,NULL,NULL,1,'2026-03-24 20:46:28'),(75,'MC','MC',4,60,NULL,NULL,1,'2026-03-24 20:46:28'),(76,'ES','ES',4,60,NULL,NULL,1,'2026-03-24 20:46:28'),(77,'MCG','MCG',4,60,NULL,NULL,1,'2026-03-24 20:46:28'),(78,'PI','PI',4,60,NULL,NULL,1,'2026-03-24 20:46:28'),(79,'TSI','TSI',4,60,NULL,NULL,1,'2026-03-24 20:46:28'),(80,'IPM','IPM',4,60,NULL,NULL,1,'2026-03-24 20:46:28'),(81,'SQL Server','SQL Server',5,60,NULL,NULL,1,'2026-03-24 20:46:28');
/*!40000 ALTER TABLE `disciplinas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documentos_matricula`
--

DROP TABLE IF EXISTS `documentos_matricula`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documentos_matricula` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `matricula_id` int(11) NOT NULL,
  `tipo_documento` enum('BI','Fotografia','Certificado','Comprovativo_Pagamento') NOT NULL,
  `nome_arquivo` varchar(255) NOT NULL,
  `caminho_arquivo` varchar(500) NOT NULL,
  `tamanho` int(11) DEFAULT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `data_upload` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_matricula` (`matricula_id`),
  CONSTRAINT `documentos_matricula_ibfk_1` FOREIGN KEY (`matricula_id`) REFERENCES `matriculas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documentos_matricula`
--

LOCK TABLES `documentos_matricula` WRITE;
/*!40000 ALTER TABLE `documentos_matricula` DISABLE KEYS */;
INSERT INTO `documentos_matricula` VALUES (1,182,'BI','182_doc_bi_f5ef71ca.jpg','public/uploads/matriculas/182_doc_bi_f5ef71ca.jpg',NULL,NULL,'2026-03-24 21:51:49'),(2,182,'Fotografia','182_doc_foto_31c6da7d.jpg','public/uploads/matriculas/182_doc_foto_31c6da7d.jpg',NULL,NULL,'2026-03-24 21:51:49'),(3,182,'Certificado','182_doc_cert_a88d774f.pdf','public/uploads/matriculas/182_doc_cert_a88d774f.pdf',NULL,NULL,'2026-03-24 21:51:49'),(4,182,'Comprovativo_Pagamento','182_doc_comprovativo_43e96bcf.jpg','public/uploads/matriculas/182_doc_comprovativo_43e96bcf.jpg',NULL,NULL,'2026-03-24 21:51:49'),(5,183,'BI','183_doc_bi_ecad9579.pdf','public/uploads/matriculas/183_doc_bi_ecad9579.pdf',NULL,NULL,'2026-03-26 23:35:13'),(6,183,'Fotografia','183_doc_foto_11e8f2c8.png','public/uploads/matriculas/183_doc_foto_11e8f2c8.png',NULL,NULL,'2026-03-26 23:35:13'),(7,183,'Certificado','183_doc_cert_479520b7.pdf','public/uploads/matriculas/183_doc_cert_479520b7.pdf',NULL,NULL,'2026-03-26 23:35:13'),(8,183,'Comprovativo_Pagamento','183_doc_comprovativo_7847886a.pdf','public/uploads/matriculas/183_doc_comprovativo_7847886a.pdf',NULL,NULL,'2026-03-26 23:35:13'),(9,194,'BI','ghs_69ca53c83f2520.83968533.pdf','public/uploads/matriculas/ghs_69ca53c83f2520.83968533.pdf',NULL,NULL,'2026-03-30 10:43:20'),(10,194,'Fotografia','ghs_69ca53c840e517.17025518.jpg','public/uploads/matriculas/ghs_69ca53c840e517.17025518.jpg',NULL,NULL,'2026-03-30 10:43:20'),(11,194,'Certificado','ghs_69ca53c8424317.94459982.pdf','public/uploads/matriculas/ghs_69ca53c8424317.94459982.pdf',NULL,NULL,'2026-03-30 10:43:20'),(12,194,'Comprovativo_Pagamento','ghs_69ca53c8438404.70583415.pdf','public/uploads/matriculas/ghs_69ca53c8438404.70583415.pdf',NULL,NULL,'2026-03-30 10:43:20'),(13,195,'BI','ghs_69ca59de22c172.24655923.pdf','public/uploads/matriculas/ghs_69ca59de22c172.24655923.pdf',NULL,NULL,'2026-03-30 11:09:18'),(14,195,'Fotografia','ghs_69ca59de245027.03780594.png','public/uploads/matriculas/ghs_69ca59de245027.03780594.png',NULL,NULL,'2026-03-30 11:09:18'),(15,195,'Certificado','ghs_69ca59de258611.00773338.pdf','public/uploads/matriculas/ghs_69ca59de258611.00773338.pdf',NULL,NULL,'2026-03-30 11:09:18'),(16,195,'Comprovativo_Pagamento','ghs_69ca59de270eb4.48415714.png','public/uploads/matriculas/ghs_69ca59de270eb4.48415714.png',NULL,NULL,'2026-03-30 11:09:18'),(17,174,'BI','ghs_69cca17532dc47.70153043.jpg','public/uploads/matriculas/ghs_69cca17532dc47.70153043.jpg',NULL,NULL,'2026-04-01 04:39:17'),(18,174,'Fotografia','ghs_69cca175345d19.58333042.jpg','public/uploads/matriculas/ghs_69cca175345d19.58333042.jpg',NULL,NULL,'2026-04-01 04:39:17'),(19,174,'Certificado','ghs_69cca17535c935.18983496.jpg','public/uploads/matriculas/ghs_69cca17535c935.18983496.jpg',NULL,NULL,'2026-04-01 04:39:17'),(20,174,'Comprovativo_Pagamento','ghs_69cca175371a14.19418858.jpg','public/uploads/matriculas/ghs_69cca175371a14.19418858.jpg',NULL,NULL,'2026-04-01 04:39:17'),(21,176,'BI','ghs_69ccbfa371d074.74808561.jpg','public/uploads/matriculas/ghs_69ccbfa371d074.74808561.jpg',NULL,NULL,'2026-04-01 06:48:03'),(22,176,'Fotografia','ghs_69ccbfa373a5b3.01147178.jpg','public/uploads/matriculas/ghs_69ccbfa373a5b3.01147178.jpg',NULL,NULL,'2026-04-01 06:48:03'),(23,176,'Certificado','ghs_69ccbfa3750317.06011470.pdf','public/uploads/matriculas/ghs_69ccbfa3750317.06011470.pdf',NULL,NULL,'2026-04-01 06:48:03'),(24,176,'Comprovativo_Pagamento','ghs_69ccbfa3765ae7.63145555.jpg','public/uploads/matriculas/ghs_69ccbfa3765ae7.63145555.jpg',NULL,NULL,'2026-04-01 06:48:03'),(25,177,'BI','ghs_69cf875d9a1427.20487876.pdf','public/uploads/matriculas/ghs_69cf875d9a1427.20487876.pdf',NULL,NULL,'2026-04-03 09:24:45'),(26,177,'Fotografia','ghs_69cf875d9d1582.41573357.png','public/uploads/matriculas/ghs_69cf875d9d1582.41573357.png',NULL,NULL,'2026-04-03 09:24:45'),(27,177,'Certificado','ghs_69cf875d9e7759.61366323.jpg','public/uploads/matriculas/ghs_69cf875d9e7759.61366323.jpg',NULL,NULL,'2026-04-03 09:24:45'),(28,177,'Comprovativo_Pagamento','ghs_69cf875d9fea29.76181518.jpg','public/uploads/matriculas/ghs_69cf875d9fea29.76181518.jpg',NULL,NULL,'2026-04-03 09:24:45'),(29,178,'BI','ghs_69cf93c7a58f82.04530102.jpg','public/uploads/matriculas/ghs_69cf93c7a58f82.04530102.jpg',NULL,NULL,'2026-04-03 10:17:43'),(30,178,'Fotografia','ghs_69cf93c7a77601.54420826.jpg','public/uploads/matriculas/ghs_69cf93c7a77601.54420826.jpg',NULL,NULL,'2026-04-03 10:17:43'),(31,178,'Certificado','ghs_69cf93c7a8fa82.20817322.png','public/uploads/matriculas/ghs_69cf93c7a8fa82.20817322.png',NULL,NULL,'2026-04-03 10:17:43'),(32,178,'Comprovativo_Pagamento','ghs_69cf93c7ab2066.12758236.jpg','public/uploads/matriculas/ghs_69cf93c7ab2066.12758236.jpg',NULL,NULL,'2026-04-03 10:17:43');
/*!40000 ALTER TABLE `documentos_matricula` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `especializacoes`
--

DROP TABLE IF EXISTS `especializacoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `especializacoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `descricao` text DEFAULT NULL,
  `vagas` int(11) DEFAULT 30,
  `ativa` tinyint(1) DEFAULT 1,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `idx_codigo` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `especializacoes`
--

LOCK TABLES `especializacoes` WRITE;
/*!40000 ALTER TABLE `especializacoes` DISABLE KEYS */;
INSERT INTO `especializacoes` VALUES (1,'ESP1','Hardware & Robótica','Especialização em hardware, robótica e sistemas embarcados',30,1,'2026-03-11 01:15:18'),(2,'ESP2','Programação','Especialização em desenvolvimento de software e aplicações',30,1,'2026-03-11 01:15:18'),(3,'','Banco de Dados',NULL,30,1,'2026-04-01 03:28:34'),(4,'ESP4','Redes de Computadores','Especialização em infraestrutura e segurança de redes',30,1,'2026-03-11 01:15:18'),(5,'ESP5','Engenharia Médica','Especialização em tecnologia aplicada à saúde',30,1,'2026-03-11 01:15:18'),(6,'ESP3','Banco de Dados','',30,1,'2026-03-22 20:03:52');
/*!40000 ALTER TABLE `especializacoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estudantes`
--

DROP TABLE IF EXISTS `estudantes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estudantes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `bi` (`bi`),
  KEY `utilizador_id` (`utilizador_id`),
  KEY `idx_bi` (`bi`),
  KEY `idx_telefone` (`telefone`),
  CONSTRAINT `estudantes_ibfk_1` FOREIGN KEY (`utilizador_id`) REFERENCES `utilizadores` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=178 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estudantes`
--

LOCK TABLES `estudantes` WRITE;
/*!40000 ALTER TABLE `estudantes` DISABLE KEYS */;
INSERT INTO `estudantes` VALUES (1,2,'BI21383','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30'),(2,3,'BI69618','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30'),(3,4,'BI73288','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30'),(4,5,'BI20072','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30'),(5,6,'BI71182','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30'),(6,7,'BI68668','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30'),(7,8,'BI79266','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30'),(8,9,'BI67248','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30'),(9,10,'BI13110','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30'),(10,11,'BI34601','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30'),(11,12,'BI34777','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30'),(12,13,'BI91738','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30'),(13,14,'BI63805','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31'),(14,15,'BI44963','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31'),(15,16,'BI12882','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31'),(16,17,'BI91778','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31'),(17,18,'BI42355','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31'),(18,19,'BI75042','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31'),(19,20,'BI64906','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31'),(20,21,'BI10676','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31'),(21,22,'BI68186','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31'),(22,23,'BI20934','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31'),(23,24,'BI57066','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31'),(24,25,'BI10857','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31'),(25,26,'BI77971','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31'),(26,27,'BI65280','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31'),(27,28,'BI87082','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32'),(28,29,'BI97110','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32'),(29,30,'BI26343','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32'),(30,31,'BI61871','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32'),(31,32,'BI43080','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32'),(32,33,'BI70802','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32'),(33,34,'BI15265','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32'),(34,35,'BI40836','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32'),(35,36,'BI71687','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32'),(36,37,'BI61562','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32'),(37,38,'BI20054','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32'),(38,39,'BI87752','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32'),(39,40,'BI76547','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32'),(40,41,'BI88664','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32'),(41,42,'BI25181','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32'),(42,43,'BI10237','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33'),(43,44,'BI90646','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33'),(44,45,'BI90943','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33'),(45,46,'BI97173','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33'),(46,47,'BI51928','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33'),(47,48,'BI33220','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33'),(48,49,'BI98195','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33'),(49,50,'BI53164','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33'),(50,51,'BI95224','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33'),(51,52,'BI61892','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33'),(52,53,'BI83926','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33'),(53,54,'BI94706','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33'),(54,55,'BI85276','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33'),(55,56,'BI71103','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33'),(56,57,'BI62699','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33'),(57,58,'BI25200','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33'),(58,59,'BI88268','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34'),(59,60,'BI59971','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34'),(60,61,'BI90705','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34'),(61,62,'BI97129','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34'),(62,63,'BI18247','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34'),(63,64,'BI20594','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34'),(64,65,'BI58459','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34'),(65,66,'BI39487','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34'),(66,67,'BI88983','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34'),(67,68,'BI27752','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34'),(68,69,'BI81358','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34'),(69,70,'BI75720','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34'),(70,71,'BI33847','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34'),(71,72,'BI56154','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34'),(72,73,'BI99061','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35'),(73,74,'BI52252','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35'),(74,75,'BI40497','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35'),(75,76,'BI78570','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35'),(76,77,'BI25977','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35'),(77,78,'BI52601','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35'),(78,79,'BI60019','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35'),(79,80,'BI57878','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35'),(80,81,'BI45783','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35'),(81,82,'BI36669','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35'),(82,83,'BI70363','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35'),(83,84,'BI20336','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35'),(84,85,'BI32372','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35'),(85,86,'BI90636','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35'),(86,87,'BI60786','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35'),(87,88,'BI52419','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35'),(88,89,'BI26786','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36'),(89,90,'BI59956','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36'),(90,91,'BI57041','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36'),(91,92,'BI99821','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36'),(92,93,'BI26579','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36'),(93,94,'BI91751','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36'),(94,95,'BI32603','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36'),(95,96,'BI46717','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36'),(96,97,'BI26940','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36'),(97,98,'BI25593','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36'),(98,99,'BI41055','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36'),(99,100,'BI60080','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36'),(100,101,'BI21626','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36'),(101,102,'BI55216','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36'),(102,103,'BI97721','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36'),(103,104,'BI25375','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37'),(104,105,'BI29674','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37'),(105,106,'BI79657','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37'),(106,107,'BI28278','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37'),(107,108,'BI37615','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37'),(108,109,'BI46474','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37'),(109,110,'BI93048','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37'),(110,111,'BI24990','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37'),(111,112,'BI50406','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37'),(112,113,'BI73569','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37'),(113,114,'BI60959','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37'),(114,115,'BI22643','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37'),(115,116,'BI15058','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37'),(116,117,'BI84733','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37'),(117,118,'BI36204','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37'),(118,119,'BI31445','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38'),(119,120,'BI61039','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38'),(120,121,'BI33241','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38'),(121,122,'BI88010','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38'),(122,123,'BI94090','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38'),(123,124,'BI98008','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38'),(124,125,'BI31114','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38'),(125,126,'BI62440','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38'),(126,127,'BI53550','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38'),(127,128,'BI58717','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38'),(128,129,'BI44408','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38'),(129,130,'BI73778','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38'),(130,131,'BI66404','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38'),(131,132,'BI49876','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38'),(132,133,'BI89986','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38'),(133,134,'BI10012','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39'),(134,135,'BI14045','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39'),(135,136,'BI50754','2000-02-12','Guineense','Masculino','Solteiro','','','','','Bissau','','',NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 04:52:58'),(136,137,'BI15411','2002-12-13','Guineense','Masculino','Solteiro','','','','','Bissau','','',NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 05:35:48'),(137,138,'BI67081','2000-02-09','Guineense','Masculino','Solteiro','','','','','Bissau','','',NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:51:46'),(138,139,'BI75192','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39'),(139,140,'BI52743','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39'),(140,141,'BI41000','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39'),(141,142,'BI27765','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39'),(142,143,'BI94896','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39'),(143,144,'BI10918','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39'),(144,145,'BI45208','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39'),(145,146,'BI52114','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39'),(146,147,'BI57510','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39'),(147,148,'BI46101','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39'),(148,149,'BI33336','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40'),(149,150,'BI74849','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40'),(150,151,'BI30734','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40'),(151,152,'BI80877','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40'),(152,153,'BI42883','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40'),(153,154,'BI18163','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40'),(154,155,'BI89004','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40'),(155,156,'BI86531','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40'),(156,157,'BI36170','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40'),(157,158,'BI56150','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40'),(158,159,'BI78824','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40'),(159,160,'BI72040','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40'),(160,161,'BI79433','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40'),(161,162,'BI97235','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40'),(162,163,'BI50759','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40'),(163,164,'BI59484','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:41','2026-04-01 03:43:41'),(164,165,'BI54924','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:41','2026-04-01 03:43:41'),(165,166,'BI58582','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:41','2026-04-01 03:43:41'),(166,167,'BI20150','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:41','2026-04-01 03:43:41'),(167,168,'BI56801','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:41','2026-04-01 03:43:41'),(168,169,'BI37663','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:41','2026-04-01 03:43:41'),(169,170,'BI30777','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:41','2026-04-01 03:43:41'),(170,171,'BI68101','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:41','2026-04-01 03:43:41'),(171,172,'BI98975','0000-00-00','Guineense','Masculino','Solteiro','',NULL,NULL,NULL,'Bissau',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:41','2026-04-01 03:43:41'),(173,180,'BI00115','2007-02-12','Guineense','Feminino','','9566432112',NULL,'Cupilum',NULL,'Bissau','Luizela S.P Tecanhe','956674373','Casa Manuel',2025,15.00,NULL,'2026-04-01 04:39:17','2026-04-01 04:39:17'),(174,187,'000189565','2023-12-25','Guineense','Feminino','','+245956623129',NULL,'Batau',NULL,'Bissau','Diosives Pedro Nunes Crobute','+245956623129','Liceu Dr. Rui Barcelos Da Cunha',2020,14.00,NULL,'2026-04-01 06:48:03','2026-04-01 06:48:03'),(176,190,'2222','1997-12-01','Guineense','Masculino','','2345',NULL,'Mindara',NULL,'Bissau','Black Seven','987654','Bimantechs',2023,17.00,NULL,'2026-04-03 09:24:45','2026-04-03 09:24:45'),(177,191,'22221','2000-12-01','Guineense','Masculino','Solteiro','95555555',NULL,'Mindara',NULL,'Bissau','Diolindo','967777777','Barcelos',2022,15.00,NULL,'2026-04-03 10:17:43','2026-04-03 10:17:43');
/*!40000 ALTER TABLE `estudantes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eventos`
--

DROP TABLE IF EXISTS `eventos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `eventos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `data_evento` datetime NOT NULL,
  `tipo` enum('Aula Extra','Exame','Trabalho','Reuniao','Feriado','Outro') DEFAULT 'Outro',
  `cor` varchar(10) DEFAULT '#3b82f6',
  `destinatario_tipo` enum('Global','Ano','Turma','Individual') DEFAULT 'Global',
  `destinatario_id` int(11) DEFAULT NULL,
  `criado_por` int(11) NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eventos`
--

LOCK TABLES `eventos` WRITE;
/*!40000 ALTER TABLE `eventos` DISABLE KEYS */;
/*!40000 ALTER TABLE `eventos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eventos_calendario`
--

DROP TABLE IF EXISTS `eventos_calendario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `eventos_calendario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `criado_por` (`criado_por`),
  KEY `idx_tipo` (`tipo`),
  KEY `idx_data_inicio` (`data_inicio`),
  KEY `idx_data_fim` (`data_fim`),
  CONSTRAINT `eventos_calendario_ibfk_1` FOREIGN KEY (`criado_por`) REFERENCES `utilizadores` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eventos_calendario`
--

LOCK TABLES `eventos_calendario` WRITE;
/*!40000 ALTER TABLE `eventos_calendario` DISABLE KEYS */;
/*!40000 ALTER TABLE `eventos_calendario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `frequencias`
--

DROP TABLE IF EXISTS `frequencias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `frequencias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sumario_id` int(11) DEFAULT NULL,
  `estudante_id` int(11) NOT NULL,
  `turma_id` int(11) NOT NULL,
  `disciplina_id` int(11) NOT NULL,
  `data` date NOT NULL,
  `status` enum('P','F','J') DEFAULT 'P',
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `confirmado_admin` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `estudante_id` (`estudante_id`),
  KEY `turma_id` (`turma_id`),
  KEY `disciplina_id` (`disciplina_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `frequencias`
--

LOCK TABLES `frequencias` WRITE;
/*!40000 ALTER TABLE `frequencias` DISABLE KEYS */;
/*!40000 ALTER TABLE `frequencias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horarios`
--

DROP TABLE IF EXISTS `horarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `horarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `turma_id` int(11) NOT NULL,
  `disciplina_id` int(11) NOT NULL,
  `professor_id` int(11) DEFAULT NULL,
  `dia_semana` enum('Segunda','Terça','Quarta','Quinta','Sexta','Sábado') NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fim` time NOT NULL,
  `sala` varchar(20) NOT NULL,
  `tempo_aula` tinyint(4) DEFAULT NULL COMMENT '1º, 2º, 3º, 4º tempo',
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `disciplina_id` (`disciplina_id`),
  KEY `idx_turma` (`turma_id`),
  KEY `idx_professor` (`professor_id`),
  KEY `idx_dia` (`dia_semana`),
  KEY `idx_horarios_turma_dia` (`turma_id`,`dia_semana`),
  KEY `idx_horarios_professor` (`professor_id`),
  CONSTRAINT `horarios_ibfk_1` FOREIGN KEY (`turma_id`) REFERENCES `turmas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `horarios_ibfk_2` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `horarios_ibfk_3` FOREIGN KEY (`professor_id`) REFERENCES `professores` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3370 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horarios`
--

LOCK TABLES `horarios` WRITE;
/*!40000 ALTER TABLE `horarios` DISABLE KEYS */;
INSERT INTO `horarios` VALUES (3158,10,40,NULL,'Segunda','07:20:00','08:50:00','lab1',1,'2026-03-27 02:14:07'),(3159,10,55,NULL,'Segunda','08:55:00','10:25:00','S1',2,'2026-03-27 02:14:07'),(3160,10,56,NULL,'Segunda','10:30:00','12:00:00','S1',3,'2026-03-27 02:14:07'),(3161,10,57,NULL,'Segunda','12:05:00','13:35:00','S1',4,'2026-03-27 02:14:07'),(3162,10,58,NULL,'Terça','07:20:00','08:50:00','S1',1,'2026-03-27 02:14:07'),(3163,10,1,NULL,'Terça','08:55:00','10:25:00','S1',2,'2026-03-27 02:14:07'),(3164,10,56,NULL,'Terça','10:30:00','12:00:00','S1',3,'2026-03-27 02:14:07'),(3165,10,1,NULL,'Quarta','07:20:00','08:50:00','S1',1,'2026-03-27 02:14:07'),(3166,10,58,NULL,'Quarta','08:55:00','10:25:00','S1',2,'2026-03-27 02:14:07'),(3167,10,60,NULL,'Quarta','10:30:00','12:00:00','S1',3,'2026-03-27 02:14:07'),(3168,10,40,NULL,'Quinta','07:20:00','08:50:00','lab1',1,'2026-03-27 02:14:07'),(3169,10,61,NULL,'Quinta','08:55:00','10:25:00','S1',2,'2026-03-27 02:14:07'),(3170,10,62,NULL,'Quinta','10:30:00','12:00:00','S1',3,'2026-03-27 02:14:07'),(3171,10,55,NULL,'Quinta','12:05:00','13:35:00','S1',4,'2026-03-27 02:14:07'),(3172,10,1,NULL,'Sexta','07:20:00','08:50:00','S1',1,'2026-03-27 02:14:07'),(3173,10,62,NULL,'Sexta','08:55:00','10:25:00','S1',2,'2026-03-27 02:14:07'),(3174,10,57,NULL,'Sexta','10:30:00','12:00:00','S1',3,'2026-03-27 02:14:07'),(3175,10,60,NULL,'Sexta','12:05:00','13:35:00','S1',4,'2026-03-27 02:14:07'),(3176,11,56,NULL,'Segunda','14:35:00','16:05:00','S1',2,'2026-03-27 02:14:07'),(3177,11,62,NULL,'Segunda','16:10:00','17:40:00','S1',3,'2026-03-27 02:14:07'),(3178,11,40,NULL,'Segunda','17:45:00','19:15:00','LAB1',4,'2026-03-27 02:14:07'),(3179,11,55,NULL,'Terça','13:00:00','14:30:00','S1',1,'2026-03-27 02:14:07'),(3180,11,61,NULL,'Terça','14:35:00','16:05:00','S1',2,'2026-03-27 02:14:07'),(3181,11,40,NULL,'Terça','16:10:00','17:40:00','LAB1',3,'2026-03-27 02:14:07'),(3182,11,58,NULL,'Terça','17:45:00','19:15:00','S1',4,'2026-03-27 02:14:07'),(3183,11,57,NULL,'Quarta','13:00:00','14:30:00','S1',1,'2026-03-27 02:14:07'),(3184,11,56,NULL,'Quarta','14:35:00','16:05:00','S1',2,'2026-03-27 02:14:07'),(3185,11,1,NULL,'Quarta','16:10:00','17:40:00','S1',3,'2026-03-27 02:14:07'),(3186,11,58,NULL,'Quarta','17:45:00','19:15:00','S1',4,'2026-03-27 02:14:07'),(3187,11,62,NULL,'Quinta','14:35:00','16:05:00','S1',2,'2026-03-27 02:14:07'),(3188,11,1,NULL,'Quinta','16:10:00','17:40:00','S1',3,'2026-03-27 02:14:07'),(3189,11,60,NULL,'Quinta','17:45:00','19:15:00','S1',4,'2026-03-27 02:14:07'),(3190,11,57,NULL,'Sexta','13:00:00','14:30:00','S1',1,'2026-03-27 02:14:07'),(3191,11,55,NULL,'Sexta','14:35:00','16:05:00','S1',2,'2026-03-27 02:14:07'),(3192,11,1,NULL,'Sexta','16:10:00','17:40:00','S1',3,'2026-03-27 02:14:07'),(3193,11,60,NULL,'Sexta','17:45:00','19:15:00','S1',4,'2026-03-27 02:14:07'),(3194,12,55,NULL,'Segunda','17:45:00','19:15:00','S1',1,'2026-03-27 02:14:07'),(3195,12,40,NULL,'Segunda','19:20:00','20:50:00','LAB1',2,'2026-03-27 02:14:07'),(3196,12,56,NULL,'Segunda','20:55:00','22:25:00','S1',3,'2026-03-27 02:14:07'),(3197,12,62,NULL,'Segunda','22:30:00','00:00:00','S1',4,'2026-03-27 02:14:07'),(3198,12,1,NULL,'Terça','17:45:00','19:15:00','SR',1,'2026-03-27 02:14:07'),(3199,12,58,NULL,'Terça','19:20:00','20:50:00','S1',2,'2026-03-27 02:14:07'),(3200,12,62,NULL,'Terça','20:55:00','22:25:00','S1',3,'2026-03-27 02:14:07'),(3201,12,57,NULL,'Quarta','17:45:00','19:15:00','SR',1,'2026-03-27 02:14:07'),(3202,12,58,NULL,'Quarta','19:20:00','20:50:00','S1',2,'2026-03-27 02:14:07'),(3203,12,40,NULL,'Quarta','20:55:00','22:25:00','LAB1',3,'2026-03-27 02:14:07'),(3204,12,60,NULL,'Quarta','22:30:00','00:00:00','S1',4,'2026-03-27 02:14:07'),(3205,12,1,NULL,'Quinta','17:45:00','19:15:00','SR',1,'2026-03-27 02:14:07'),(3206,12,55,NULL,'Quinta','19:20:00','20:50:00','S1',2,'2026-03-27 02:14:07'),(3207,12,61,NULL,'Quinta','20:55:00','22:25:00','S1',3,'2026-03-27 02:14:07'),(3208,12,1,NULL,'Sexta','17:45:00','19:15:00','SR',1,'2026-03-27 02:14:07'),(3209,12,60,NULL,'Sexta','19:20:00','20:50:00','S1',2,'2026-03-27 02:14:07'),(3210,12,56,NULL,'Sexta','20:55:00','22:25:00','S1',3,'2026-03-27 02:14:07'),(3211,12,57,NULL,'Sexta','22:30:00','00:00:00','S1',4,'2026-03-27 02:14:07'),(3212,13,1,NULL,'Segunda','07:20:00','08:50:00','S2',1,'2026-03-27 02:14:07'),(3213,13,40,NULL,'Segunda','08:55:00','10:25:00','LAB1',2,'2026-03-27 02:14:07'),(3214,13,64,NULL,'Segunda','10:30:00','12:00:00','S2',3,'2026-03-27 02:14:07'),(3215,13,57,NULL,'Terça','07:20:00','08:50:00','S2',1,'2026-03-27 02:14:07'),(3216,13,63,NULL,'Terça','08:55:00','10:25:00','LAB1',2,'2026-03-27 02:14:07'),(3217,13,65,NULL,'Terça','10:30:00','12:00:00','S2',3,'2026-03-27 02:14:07'),(3218,13,1,NULL,'Terça','12:05:00','13:35:00','S3',4,'2026-03-27 02:14:07'),(3219,13,57,NULL,'Quarta','07:20:00','08:50:00','S2',1,'2026-03-27 02:14:07'),(3220,13,63,NULL,'Quarta','08:55:00','10:25:00','LAB1',2,'2026-03-27 02:14:07'),(3221,13,65,NULL,'Quarta','10:30:00','12:00:00','S2',3,'2026-03-27 02:14:07'),(3222,13,1,NULL,'Quarta','12:05:00','13:35:00','S2',4,'2026-03-27 02:14:07'),(3223,13,66,NULL,'Quinta','07:20:00','08:50:00','S2',1,'2026-03-27 02:14:07'),(3224,13,66,NULL,'Quinta','08:55:00','10:25:00','S2',2,'2026-03-27 02:14:07'),(3225,13,65,NULL,'Quinta','10:30:00','12:00:00','S2',3,'2026-03-27 02:14:07'),(3226,13,60,NULL,'Quinta','12:05:00','13:35:00','S2',4,'2026-03-27 02:14:07'),(3227,13,60,NULL,'Sexta','07:20:00','08:50:00','S2',1,'2026-03-27 02:14:07'),(3228,13,40,NULL,'Sexta','08:55:00','10:25:00','LAB1',2,'2026-03-27 02:14:07'),(3229,13,64,NULL,'Sexta','10:30:00','12:00:00','S2',3,'2026-03-27 02:14:07'),(3230,14,57,NULL,'Segunda','13:00:00','14:30:00','S2',1,'2026-03-27 02:14:07'),(3231,14,66,NULL,'Segunda','14:35:00','16:05:00','S2',2,'2026-03-27 02:14:07'),(3232,14,63,NULL,'Segunda','16:10:00','17:40:00','LAB2',3,'2026-03-27 02:14:07'),(3233,14,57,NULL,'Terça','13:00:00','14:30:00','BIB',1,'2026-03-27 02:14:07'),(3234,14,1,NULL,'Terça','14:35:00','16:05:00','S2',2,'2026-03-27 02:14:07'),(3235,14,65,NULL,'Terça','16:10:00','17:40:00','S2',3,'2026-03-27 02:14:07'),(3236,14,66,NULL,'Quarta','13:00:00','14:30:00','S3',1,'2026-03-27 02:14:07'),(3237,14,63,NULL,'Quarta','14:35:00','16:05:00','LAB1',2,'2026-03-27 02:14:07'),(3238,14,40,NULL,'Quarta','16:10:00','17:40:00','LAB1',3,'2026-03-27 02:14:07'),(3239,14,60,NULL,'Quarta','17:45:00','19:15:00','S2',4,'2026-03-27 02:14:07'),(3240,14,64,NULL,'Quinta','13:00:00','14:30:00','S3',1,'2026-03-27 02:14:07'),(3241,14,1,NULL,'Quinta','14:35:00','16:05:00','S2',2,'2026-03-27 02:14:07'),(3242,14,65,NULL,'Quinta','16:10:00','17:40:00','S2',3,'2026-03-27 02:14:07'),(3243,14,60,NULL,'Quinta','17:45:00','19:15:00','S2',4,'2026-03-27 02:14:07'),(3244,14,65,NULL,'Sexta','13:00:00','14:30:00','S2',1,'2026-03-27 02:14:07'),(3245,14,1,NULL,'Sexta','14:35:00','16:05:00','S2',2,'2026-03-27 02:14:07'),(3246,14,40,NULL,'Sexta','16:10:00','17:40:00','LAB1',3,'2026-03-27 02:14:07'),(3247,14,64,NULL,'Sexta','17:45:00','19:15:00','S2',4,'2026-03-27 02:14:07'),(3248,15,64,NULL,'Segunda','17:45:00','19:15:00','S2',1,'2026-03-27 02:14:07'),(3249,15,63,NULL,'Segunda','19:20:00','20:50:00','S2',2,'2026-03-27 02:14:07'),(3250,15,66,NULL,'Segunda','20:55:00','22:25:00','S2',3,'2026-03-27 02:14:07'),(3251,15,40,NULL,'Segunda','22:30:00','00:00:00','LAB1',4,'2026-03-27 02:14:07'),(3252,15,65,NULL,'Terça','17:45:00','19:15:00','S2',1,'2026-03-27 02:14:07'),(3253,15,1,NULL,'Terça','19:20:00','20:50:00','S2',2,'2026-03-27 02:14:07'),(3254,15,60,NULL,'Terça','20:55:00','22:25:00','S2',3,'2026-03-27 02:14:07'),(3255,15,57,NULL,'Terça','22:30:00','00:00:00','S2',4,'2026-03-27 02:14:07'),(3256,15,64,NULL,'Quarta','17:45:00','19:15:00','BIB',1,'2026-03-27 02:14:07'),(3257,15,1,NULL,'Quarta','19:20:00','20:50:00','S2',2,'2026-03-27 02:14:08'),(3258,15,65,NULL,'Quarta','20:55:00','22:25:00','S2',3,'2026-03-27 02:14:08'),(3259,15,63,NULL,'Quinta','17:45:00','19:15:00','LAB1',1,'2026-03-27 02:14:08'),(3260,15,66,NULL,'Quinta','19:20:00','20:50:00','S2',2,'2026-03-27 02:14:08'),(3261,15,40,NULL,'Quinta','20:55:00','22:25:00','LAB1',3,'2026-03-27 02:14:08'),(3262,15,57,NULL,'Quinta','22:30:00','00:00:00','S2',4,'2026-03-27 02:14:08'),(3263,15,65,NULL,'Sexta','17:45:00','19:15:00','BIB',1,'2026-03-27 02:14:08'),(3264,15,1,NULL,'Sexta','19:20:00','20:50:00','S2',2,'2026-03-27 02:14:08'),(3265,15,60,NULL,'Sexta','20:55:00','22:25:00','S2',3,'2026-03-27 02:14:08'),(3266,16,67,NULL,'Segunda','07:20:00','08:50:00','S3',1,'2026-03-27 02:14:08'),(3267,16,73,NULL,'Segunda','08:55:00','10:25:00','S3',2,'2026-03-27 02:14:08'),(3268,16,41,NULL,'Segunda','10:30:00','12:00:00','LAB1',3,'2026-03-27 02:14:08'),(3269,16,68,NULL,'Segunda','12:05:00','13:35:00','S3',4,'2026-03-27 02:14:08'),(3270,16,71,NULL,'Terça','07:20:00','08:50:00','S3',1,'2026-03-27 02:14:08'),(3271,16,42,NULL,'Terça','08:55:00','10:25:00','S3',2,'2026-03-27 02:14:08'),(3272,16,70,NULL,'Terça','10:30:00','12:00:00','LAB1',3,'2026-03-27 02:14:08'),(3273,16,67,NULL,'Terça','12:05:00','13:35:00','S3',4,'2026-03-27 02:14:08'),(3274,16,42,NULL,'Quarta','07:20:00','08:50:00','LAB1',1,'2026-03-27 02:14:08'),(3275,16,73,NULL,'Quarta','08:55:00','10:25:00','S3',2,'2026-03-27 02:14:08'),(3276,16,68,NULL,'Quarta','10:30:00','12:00:00','S3',3,'2026-03-27 02:14:08'),(3277,16,71,NULL,'Quinta','07:20:00','08:50:00','S3',1,'2026-03-27 02:14:08'),(3278,16,69,NULL,'Quinta','08:55:00','10:25:00','S3',2,'2026-03-27 02:14:08'),(3279,16,41,NULL,'Quinta','10:30:00','12:00:00','S3',3,'2026-03-27 02:14:08'),(3280,16,42,NULL,'Sexta','07:20:00','08:50:00','LAB1',1,'2026-03-27 02:14:08'),(3281,16,68,NULL,'Sexta','08:55:00','10:25:00','S3',2,'2026-03-27 02:14:08'),(3282,16,70,NULL,'Sexta','10:30:00','12:00:00','S3',3,'2026-03-27 02:14:08'),(3283,16,69,NULL,'Sexta','12:05:00','13:35:00','S3',4,'2026-03-27 02:14:08'),(3284,9,67,NULL,'Segunda','13:00:00','14:30:00','BIB',1,'2026-03-27 02:14:08'),(3285,9,69,NULL,'Segunda','14:35:00','16:05:00','S3',2,'2026-03-27 02:14:08'),(3286,9,73,NULL,'Segunda','16:10:00','17:40:00','S3',3,'2026-03-27 02:14:08'),(3287,9,42,NULL,'Segunda','17:45:00','19:15:00','S3',4,'2026-03-27 02:14:08'),(3288,9,70,NULL,'Terça','14:35:00','16:05:00','S3',2,'2026-03-27 02:14:08'),(3289,9,42,NULL,'Terça','16:10:00','17:40:00','S3',3,'2026-03-27 02:14:08'),(3290,9,71,NULL,'Terça','17:45:00','19:15:00','LAB1',4,'2026-03-27 02:14:08'),(3291,9,70,NULL,'Quarta','13:00:00','14:30:00','LAB1',1,'2026-03-27 02:14:08'),(3292,9,68,NULL,'Quarta','14:35:00','16:05:00','S3',2,'2026-03-27 02:14:08'),(3293,9,73,NULL,'Quarta','16:10:00','17:40:00','S3',3,'2026-03-27 02:14:08'),(3294,9,69,NULL,'Quarta','17:45:00','19:15:00','S3',4,'2026-03-27 02:14:08'),(3295,9,42,NULL,'Quinta','13:00:00','14:30:00','BIB',1,'2026-03-27 02:14:08'),(3296,9,68,NULL,'Quinta','14:35:00','16:05:00','S3',2,'2026-03-27 02:14:08'),(3297,9,41,NULL,'Quinta','16:10:00','17:40:00','S3',3,'2026-03-27 02:14:08'),(3298,9,71,NULL,'Quinta','17:45:00','19:15:00','S3',4,'2026-03-27 02:14:08'),(3299,9,67,NULL,'Sexta','14:35:00','16:05:00','S3',2,'2026-03-27 02:14:08'),(3300,9,68,NULL,'Sexta','16:10:00','17:40:00','S3',3,'2026-03-27 02:14:08'),(3301,9,41,NULL,'Sexta','17:45:00','19:15:00','LAB1',4,'2026-03-27 02:14:08'),(3302,7,78,NULL,'Segunda','13:00:00','14:30:00','LAB3',1,'2026-03-27 02:14:08'),(3303,7,43,NULL,'Segunda','14:35:00','16:05:00','LAB1',2,'2026-03-27 02:14:08'),(3304,7,80,NULL,'Segunda','16:10:00','17:40:00','LAB3',3,'2026-03-27 02:14:08'),(3305,7,44,NULL,'Segunda','17:45:00','19:15:00','LAB3',4,'2026-03-27 02:14:08'),(3306,7,77,NULL,'Terça','13:00:00','14:30:00','LAB1',1,'2026-03-27 02:14:08'),(3307,7,74,NULL,'Terça','14:35:00','16:05:00','LAB3',2,'2026-03-27 02:14:08'),(3308,7,79,NULL,'Terça','16:10:00','17:40:00','LAB3',3,'2026-03-27 02:14:08'),(3309,7,75,NULL,'Terça','17:45:00','19:15:00','LAB3',4,'2026-03-27 02:14:08'),(3310,7,77,NULL,'Quarta','13:00:00','14:30:00','LAB3',1,'2026-03-27 02:14:08'),(3311,7,43,NULL,'Quarta','14:35:00','16:05:00','LAB2',2,'2026-03-27 02:14:08'),(3312,7,76,NULL,'Quarta','16:10:00','17:40:00','LAB3',3,'2026-03-27 02:14:08'),(3313,7,44,NULL,'Quarta','17:45:00','19:15:00','LAB3',4,'2026-03-27 02:14:08'),(3314,7,80,NULL,'Quinta','13:00:00','14:30:00','LAB3',1,'2026-03-27 02:14:08'),(3315,7,75,NULL,'Quinta','14:35:00','16:05:00','LAB3',2,'2026-03-27 02:14:08'),(3316,7,79,NULL,'Quinta','16:10:00','17:40:00','LAB3',3,'2026-03-27 02:14:08'),(3317,7,78,NULL,'Sexta','13:00:00','14:30:00','LAB3',1,'2026-03-27 02:14:08'),(3318,7,76,NULL,'Sexta','14:35:00','16:05:00','LAB3',2,'2026-03-27 02:14:08'),(3319,7,74,NULL,'Sexta','16:10:00','17:40:00','LAB3',3,'2026-03-27 02:14:08'),(3320,18,74,NULL,'Segunda','17:45:00','19:15:00','BIB',1,'2026-03-27 02:14:08'),(3321,18,76,NULL,'Segunda','19:20:00','20:50:00','LAB3',2,'2026-03-27 02:14:08'),(3322,18,79,NULL,'Segunda','20:55:00','22:25:00','LAB3',3,'2026-03-27 02:14:08'),(3323,18,80,NULL,'Segunda','22:30:00','00:00:00','LAB2',4,'2026-03-27 02:14:08'),(3324,18,78,NULL,'Terça','17:45:00','19:15:00','BIB',1,'2026-03-27 02:14:08'),(3325,18,79,NULL,'Terça','19:20:00','20:50:00','LAB3',2,'2026-03-27 02:14:08'),(3326,18,43,NULL,'Terça','20:55:00','22:25:00','LAB1',3,'2026-03-27 02:14:08'),(3327,18,77,NULL,'Terça','22:30:00','00:00:00','LAB3',4,'2026-03-27 02:14:08'),(3328,18,44,NULL,'Quarta','19:20:00','20:50:00','LAB3',2,'2026-03-27 02:14:08'),(3329,18,75,NULL,'Quarta','20:55:00','22:25:00','LAB3',3,'2026-03-27 02:14:08'),(3330,18,77,NULL,'Quarta','22:30:00','00:00:00','LAB3',4,'2026-03-27 02:14:08'),(3331,18,74,NULL,'Quinta','17:45:00','19:15:00','BIB',1,'2026-03-27 02:14:08'),(3332,18,75,NULL,'Quinta','19:20:00','20:50:00','LAB3',2,'2026-03-27 02:14:08'),(3333,18,78,NULL,'Quinta','20:55:00','22:25:00','LAB3',3,'2026-03-27 02:14:08'),(3334,18,80,NULL,'Quinta','22:30:00','00:00:00','LAB3',4,'2026-03-27 02:14:08'),(3335,18,44,NULL,'Sexta','17:45:00','19:15:00','S3',1,'2026-03-27 02:14:08'),(3336,18,43,NULL,'Sexta','19:20:00','20:50:00','LAB3',2,'2026-03-27 02:14:08'),(3337,18,76,NULL,'Sexta','20:55:00','22:25:00','LAB3',3,'2026-03-27 02:14:08'),(3338,19,45,NULL,'Segunda','17:45:00','19:15:00','LAB2',1,'2026-03-27 02:14:08'),(3339,19,46,NULL,'Segunda','19:20:00','20:50:00','S3',2,'2026-03-27 02:14:08'),(3340,19,47,NULL,'Segunda','20:55:00','22:25:00','LAB1',3,'2026-03-27 02:14:08'),(3341,19,45,NULL,'Terça','17:45:00','19:15:00','S8',1,'2026-03-27 02:14:08'),(3342,19,48,NULL,'Terça','19:20:00','20:50:00','LAB1',2,'2026-03-27 02:14:08'),(3343,19,49,NULL,'Terça','20:55:00','22:25:00','LAB3',3,'2026-03-27 02:14:08'),(3344,19,46,NULL,'Quarta','17:45:00','19:15:00','LAB2',1,'2026-03-27 02:14:08'),(3345,19,47,NULL,'Quarta','19:20:00','20:50:00','LAB1',2,'2026-03-27 02:14:08'),(3346,19,49,NULL,'Quarta','20:55:00','22:25:00','S3',3,'2026-03-27 02:14:08'),(3347,19,45,NULL,'Quinta','17:45:00','19:15:00','LAB2',1,'2026-03-27 02:14:08'),(3348,19,47,NULL,'Quinta','19:20:00','20:50:00','S3',2,'2026-03-27 02:14:08'),(3349,19,48,NULL,'Quinta','20:55:00','22:25:00','LAB2',3,'2026-03-27 02:14:08'),(3350,19,46,NULL,'Sexta','17:45:00','19:15:00','LAB2',1,'2026-03-27 02:14:08'),(3351,19,48,NULL,'Sexta','19:20:00','20:50:00','LAB1',2,'2026-03-27 02:14:08'),(3352,19,75,NULL,'Sexta','20:55:00','22:25:00','LAB1',3,'2026-03-27 02:14:08'),(3353,19,75,NULL,'Sexta','22:30:00','00:00:00','S3',4,'2026-03-27 02:14:08'),(3354,20,50,NULL,'Segunda','13:00:00','14:30:00','LAB1',1,'2026-03-27 02:14:08'),(3355,20,53,NULL,'Segunda','14:35:00','16:05:00','LAB3',2,'2026-03-27 02:14:08'),(3356,20,46,NULL,'Segunda','16:10:00','17:40:00','LAB1',3,'2026-03-27 02:14:08'),(3357,20,52,NULL,'Terça','13:00:00','14:30:00','LAB3',1,'2026-03-27 02:14:08'),(3358,20,75,NULL,'Terça','14:35:00','16:05:00','BIB',2,'2026-03-27 02:14:08'),(3359,20,53,NULL,'Terça','16:10:00','17:40:00','LAB2',3,'2026-03-27 02:14:08'),(3360,20,54,NULL,'Quarta','13:00:00','14:30:00','BIB',1,'2026-03-27 02:14:08'),(3361,20,52,NULL,'Quarta','14:35:00','16:05:00','LAB3',2,'2026-03-27 02:14:08'),(3362,20,50,NULL,'Quarta','16:10:00','17:40:00','S2',3,'2026-03-27 02:14:08'),(3363,20,52,NULL,'Quinta','14:35:00','16:05:00','BIB',2,'2026-03-27 02:14:08'),(3364,20,46,NULL,'Quinta','16:10:00','17:40:00','LAB1',3,'2026-03-27 02:14:08'),(3365,20,54,NULL,'Quinta','17:45:00','19:15:00','LAB3',4,'2026-03-27 02:14:08'),(3366,20,50,NULL,'Sexta','13:00:00','14:30:00','LAB2',1,'2026-03-27 02:14:08'),(3367,20,53,NULL,'Sexta','14:35:00','16:05:00','LAB1',2,'2026-03-27 02:14:08'),(3368,20,54,NULL,'Sexta','16:10:00','17:40:00','S2',3,'2026-03-27 02:14:08'),(3369,20,75,NULL,'Sexta','17:45:00','19:15:00','LAB3',4,'2026-03-27 02:14:08');
/*!40000 ALTER TABLE `horarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horarios_modelo`
--

DROP TABLE IF EXISTS `horarios_modelo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `horarios_modelo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ano_id` int(11) NOT NULL,
  `dia_semana` varchar(20) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fim` time NOT NULL,
  `disciplina_id` int(11) DEFAULT NULL,
  `sala` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ano_id` (`ano_id`),
  CONSTRAINT `horarios_modelo_ibfk_1` FOREIGN KEY (`ano_id`) REFERENCES `anos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horarios_modelo`
--

LOCK TABLES `horarios_modelo` WRITE;
/*!40000 ALTER TABLE `horarios_modelo` DISABLE KEYS */;
INSERT INTO `horarios_modelo` VALUES (20,1,'Sexta','13:00:00','14:30:00',2,'Lab1, sala 4'),(21,1,'Segunda','14:35:00','16:05:00',6,'Lab1, sala 6'),(23,4,'Quinta','07:20:00','08:50:00',36,'Lab1');
/*!40000 ALTER TABLE `horarios_modelo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leitura_comunicados`
--

DROP TABLE IF EXISTS `leitura_comunicados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leitura_comunicados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comunicado_id` int(11) NOT NULL,
  `utilizador_id` int(11) NOT NULL,
  `data_leitura` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_comunicado_utilizador` (`comunicado_id`,`utilizador_id`),
  KEY `utilizador_id` (`utilizador_id`),
  KEY `idx_naolidos` (`comunicado_id`,`utilizador_id`),
  CONSTRAINT `leitura_comunicados_ibfk_1` FOREIGN KEY (`comunicado_id`) REFERENCES `comunicados` (`id`) ON DELETE CASCADE,
  CONSTRAINT `leitura_comunicados_ibfk_2` FOREIGN KEY (`utilizador_id`) REFERENCES `utilizadores` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leitura_comunicados`
--

LOCK TABLES `leitura_comunicados` WRITE;
/*!40000 ALTER TABLE `leitura_comunicados` DISABLE KEYS */;
/*!40000 ALTER TABLE `leitura_comunicados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leitura_comunicados_excluidos`
--

DROP TABLE IF EXISTS `leitura_comunicados_excluidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leitura_comunicados_excluidos` (
  `utilizador_id` int(11) NOT NULL,
  `comunicado_id` int(11) NOT NULL,
  PRIMARY KEY (`utilizador_id`,`comunicado_id`),
  KEY `comunicado_id` (`comunicado_id`),
  CONSTRAINT `leitura_comunicados_excluidos_ibfk_1` FOREIGN KEY (`utilizador_id`) REFERENCES `utilizadores` (`id`) ON DELETE CASCADE,
  CONSTRAINT `leitura_comunicados_excluidos_ibfk_2` FOREIGN KEY (`comunicado_id`) REFERENCES `comunicados` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leitura_comunicados_excluidos`
--

LOCK TABLES `leitura_comunicados_excluidos` WRITE;
/*!40000 ALTER TABLE `leitura_comunicados_excluidos` DISABLE KEYS */;
/*!40000 ALTER TABLE `leitura_comunicados_excluidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_acessos`
--

DROP TABLE IF EXISTS `log_acessos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_acessos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utilizador_id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` text DEFAULT NULL,
  `data_acesso` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `utilizador_id` (`utilizador_id`),
  CONSTRAINT `log_acessos_ibfk_1` FOREIGN KEY (`utilizador_id`) REFERENCES `utilizadores` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=164 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_acessos`
--

LOCK TABLES `log_acessos` WRITE;
/*!40000 ALTER TABLE `log_acessos` DISABLE KEYS */;
INSERT INTO `log_acessos` VALUES (110,1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-01 03:34:46'),(112,138,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-01 03:52:06'),(113,177,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-01 04:19:41'),(114,177,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-01 04:32:10'),(115,136,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-01 04:56:09'),(116,186,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-01 05:15:55'),(117,137,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-01 05:37:34'),(118,177,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-01 06:48:46'),(119,137,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-01 07:22:04'),(120,138,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-01 07:24:19'),(121,1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-01 12:44:06'),(122,136,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-01 12:44:45'),(123,186,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-01 12:46:19'),(124,136,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-01 12:46:42'),(125,1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-01 12:48:42'),(126,186,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-01 15:49:36'),(127,1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-01 15:50:30'),(128,136,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-01 15:50:39'),(129,186,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-01 17:06:41'),(130,177,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-01 17:31:20'),(131,1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-01 17:32:48'),(132,186,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-01 17:34:04'),(133,136,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-02 01:12:43'),(134,1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-02 01:16:34'),(135,189,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-02 01:22:22'),(136,1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-03 09:06:18'),(137,138,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-03 09:11:37'),(138,186,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-03 09:33:21'),(139,1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-03 09:47:53'),(140,138,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-03 10:55:13'),(141,1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-04 01:07:36'),(142,138,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-04 01:09:24'),(143,186,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-04 01:10:32'),(144,177,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-04 02:57:35'),(145,1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-04 03:05:08'),(146,1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-04 03:18:41'),(147,138,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-04 03:32:19'),(148,138,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-04 09:27:56'),(149,1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-04 10:11:00'),(150,186,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-04 13:29:13'),(151,138,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-04 13:32:31'),(152,186,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-04 15:10:16'),(153,186,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-05 14:55:59'),(154,138,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-05 14:57:48'),(155,177,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-05 15:23:18'),(156,138,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-05 15:23:57'),(157,186,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-08 00:20:01'),(158,1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-08 00:33:49'),(159,1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-08 00:54:01'),(160,138,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-08 00:55:45'),(161,186,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-08 00:58:07'),(162,186,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-08 02:01:59'),(163,186,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','2026-04-08 02:46:28');
/*!40000 ALTER TABLE `log_acessos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs_acesso`
--

DROP TABLE IF EXISTS `logs_acesso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs_acesso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utilizador_id` int(11) DEFAULT NULL,
  `acao` varchar(100) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `user_agent` text DEFAULT NULL,
  `detalhes` text DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_utilizador` (`utilizador_id`),
  KEY `idx_data` (`data_criacao`),
  KEY `idx_acao` (`acao`),
  CONSTRAINT `logs_acesso_ibfk_1` FOREIGN KEY (`utilizador_id`) REFERENCES `utilizadores` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs_acesso`
--

LOCK TABLES `logs_acesso` WRITE;
/*!40000 ALTER TABLE `logs_acesso` DISABLE KEYS */;
/*!40000 ALTER TABLE `logs_acesso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs_atividades`
--

DROP TABLE IF EXISTS `logs_atividades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs_atividades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utilizador_id` int(11) NOT NULL,
  `acao` varchar(255) NOT NULL,
  `detalhes` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `utilizador_id` (`utilizador_id`),
  CONSTRAINT `logs_atividades_ibfk_1` FOREIGN KEY (`utilizador_id`) REFERENCES `utilizadores` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=238 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs_atividades`
--

LOCK TABLES `logs_atividades` WRITE;
/*!40000 ALTER TABLE `logs_atividades` DISABLE KEYS */;
INSERT INTO `logs_atividades` VALUES (174,1,'Remover Estudante','{\"user_id\":\"376\"}','::1','2026-04-01 03:40:52'),(175,1,'Remover Estudante','{\"user_id\":\"547\"}','::1','2026-04-01 03:41:23'),(176,1,'Atualizar Estudante Admin','{\"user_id\":\"138\"}','::1','2026-04-01 03:51:46'),(177,177,'Rejeitar Matrícula Secretaria','{\"matricula_id\":\"173\",\"motivo\":\"N\\u00e3o vejo nenhum documento.\"}','::1','2026-04-01 04:32:54'),(178,1,'Remover Estudante','{\"user_id\":\"176\"}','::1','2026-04-01 04:33:46'),(179,1,'Aprovar Matrícula','{\"matricula_id\":\"174\",\"aluno\":\"lurdes@gmail.com\"}','::1','2026-04-01 04:40:04'),(180,1,'Aprovar Matrícula','{\"matricula_id\":\"174\",\"aluno\":\"lurdes@gmail.com\"}','::1','2026-04-01 04:40:06'),(181,1,'Atualizar Estudante Admin','{\"user_id\":\"136\"}','::1','2026-04-01 04:52:58'),(182,1,'Atualizar Professor v2','{\"nome\":\"Teste Professor AI\"}','::1','2026-04-01 05:04:27'),(183,1,'Alocar Aluno Interno','{\"estudante_id\":173,\"turma_id\":10}','::1','2026-04-01 05:10:41'),(184,1,'Criar Professor v2','{\"nome\":\"Domingos Correia\"}','::1','2026-04-01 05:13:54'),(185,1,'Atualizar Professor v2','{\"nome\":\"Teste Professor\"}','::1','2026-04-01 05:14:41'),(186,186,'Lançar Nota','{\"turma_id\":\"7\"}','::1','2026-04-01 05:16:47'),(187,136,'Estudante Feedback de Nota','{\"status\":\"Reclamado\"}','::1','2026-04-01 05:18:06'),(188,186,'Resposta à Reclamação','{\"estudante_id\":\"135\"}','::1','2026-04-01 05:19:43'),(189,1,'Remover Professor','{\"id\":\"17\"}','::1','2026-04-01 05:23:33'),(190,1,'Confirmar Lote de Notas','{\"turma_id\":\"7\",\"disciplina_id\":\"30\"}','::1','2026-04-01 05:28:17'),(191,186,'Lançar Nota','{\"turma_id\":\"7\"}','::1','2026-04-01 05:29:56'),(192,1,'Atualizar Estudante Admin','{\"user_id\":\"137\"}','::1','2026-04-01 05:35:48'),(193,137,'Estudante Feedback de Nota','{\"status\":\"Reclamado\"}','::1','2026-04-01 05:37:48'),(194,186,'Resposta à Reclamação','{\"estudante_id\":\"136\"}','::1','2026-04-01 05:38:05'),(195,177,'Validar Pagamento Secretaria','{\"pagamento_id\":\"1\"}','::1','2026-04-01 06:51:41'),(196,1,'Aprovar Matrícula','{\"matricula_id\":\"176\",\"aluno\":\"darlenemendesnunes@hotmail.com\"}','::1','2026-04-01 07:03:46'),(197,1,'Aprovar Matrícula','{\"matricula_id\":\"176\",\"aluno\":\"darlenemendesnunes@hotmail.com\"}','::1','2026-04-01 07:03:48'),(198,186,'Lançar Nota','{\"turma_id\":\"7\"}','::1','2026-04-01 07:24:46'),(199,1,'Validar Pagamento Admin','{\"pagamento_id\":\"2\"}','::1','2026-04-01 07:28:34'),(200,1,'Atualizar Professor v2','{\"nome\":\"Domingos Correia\"}','::1','2026-04-02 01:17:44'),(201,1,'Criar Professor v2','{\"nome\":\"Samba Djob\"}','::1','2026-04-02 01:21:49'),(202,189,'Lançar Nota','{\"turma_id\":\"7\"}','::1','2026-04-02 01:23:37'),(203,189,'Lançar Nota','{\"turma_id\":\"7\"}','::1','2026-04-02 01:30:10'),(204,1,'Apagar Pagamento Rejeitado','{\"id\":\"3\"}','::1','2026-04-02 01:42:51'),(205,1,'Validar Pagamento Admin','{\"pagamento_id\":\"4\"}','::1','2026-04-02 01:47:50'),(206,1,'Aprovar Matrícula','{\"matricula_id\":\"177\",\"aluno\":\"edegar@gmail.com\"}','::1','2026-04-03 10:29:36'),(207,1,'Aprovar Matrícula','{\"matricula_id\":\"177\",\"aluno\":\"edegar@gmail.com\"}','::1','2026-04-03 10:29:38'),(208,186,'Lançar Nota','{\"estudante_id\":\"137\"}','::1','2026-04-04 01:12:02'),(209,186,'Lançar Nota','{\"estudante_id\":\"137\"}','::1','2026-04-04 01:12:12'),(210,177,'Rejeitar Matrícula Secretaria','{\"matricula_id\":\"178\",\"motivo\":\"falta de documentos exigidos pela escola\"}','::1','2026-04-04 03:07:31'),(211,177,'Rejeitar Matrícula Secretaria','{\"matricula_id\":\"178\",\"motivo\":\"falta de documentos exigidos pela escola\"}','::1','2026-04-04 03:07:33'),(212,177,'Rejeitar Matrícula Secretaria','{\"matricula_id\":\"178\",\"motivo\":\"falta de documentos exigidos pela escola\"}','::1','2026-04-04 03:07:35'),(213,177,'Rejeitar Matrícula Secretaria','{\"matricula_id\":\"178\",\"motivo\":\"falta de documentos exigidos pela escola\"}','::1','2026-04-04 03:07:37'),(214,177,'Rejeitar Matrícula Secretaria','{\"matricula_id\":\"178\",\"motivo\":\"falta de documentos exigidos pela escola\"}','::1','2026-04-04 03:07:40'),(215,177,'Rejeitar Matrícula Secretaria','{\"matricula_id\":\"178\",\"motivo\":\"falta de documentos exigidos pela escola\"}','::1','2026-04-04 03:07:42'),(216,177,'Rejeitar Matrícula Secretaria','{\"matricula_id\":\"178\",\"motivo\":\"falta de documentos exigidos pela escola\"}','::1','2026-04-04 03:07:44'),(217,177,'Rejeitar Matrícula Secretaria','{\"matricula_id\":\"178\",\"motivo\":\"falta de documentos exigidos pela escola\"}','::1','2026-04-04 03:07:46'),(218,177,'Rejeitar Matrícula Secretaria','{\"matricula_id\":\"178\",\"motivo\":\"falta de documentos exigidos pela escola\"}','::1','2026-04-04 03:07:48'),(219,177,'Rejeitar Matrícula Secretaria','{\"matricula_id\":\"178\",\"motivo\":\"falta de documentos exigidos pela escola\"}','::1','2026-04-04 03:07:50'),(220,177,'Rejeitar Matrícula Secretaria','{\"matricula_id\":\"178\",\"motivo\":\"falta de documentos exigidos pela escola\"}','::1','2026-04-04 03:07:52'),(221,177,'Rejeitar Matrícula Secretaria','{\"matricula_id\":\"178\",\"motivo\":\"falta de documentos exigidos pela escola\"}','::1','2026-04-04 03:07:54'),(222,177,'Rejeitar Matrícula Secretaria','{\"matricula_id\":\"178\",\"motivo\":\"falta de documentos exigidos pela escola\"}','::1','2026-04-04 03:07:56'),(223,177,'Rejeitar Matrícula Secretaria','{\"matricula_id\":\"178\",\"motivo\":\"falta de documentos exigidos pela escola\"}','::1','2026-04-04 03:07:58'),(224,177,'Rejeitar Matrícula Secretaria','{\"matricula_id\":\"178\",\"motivo\":\"falta de documentos exigidos pela escola\"}','::1','2026-04-04 03:08:01'),(225,177,'Rejeitar Matrícula Secretaria','{\"matricula_id\":\"178\",\"motivo\":\"falta de documentos exigidos pela escola\"}','::1','2026-04-04 03:08:03'),(226,177,'Rejeitar Matrícula Secretaria','{\"matricula_id\":\"178\",\"motivo\":\"falta de documentos exigidos pela escola\"}','::1','2026-04-04 03:08:05'),(227,177,'Rejeitar Matrícula Secretaria','{\"matricula_id\":\"178\",\"motivo\":\"falta de documentos exigidos pela escola\"}','::1','2026-04-04 03:08:07'),(228,177,'Rejeitar Matrícula Secretaria','{\"matricula_id\":\"178\",\"motivo\":\"falta de documentos exigidos pela escola\"}','::1','2026-04-04 03:08:09'),(229,177,'Rejeitar Matrícula Secretaria','{\"matricula_id\":\"178\",\"motivo\":\"falta de documentos exigidos pela escola\"}','::1','2026-04-04 03:08:11'),(230,177,'Validar Pagamento Secretaria','{\"pagamento_id\":\"14\"}','::1','2026-04-05 15:32:02'),(231,177,'Validar Pagamento Secretaria','{\"pagamento_id\":\"13\"}','::1','2026-04-05 15:32:10'),(232,177,'Validar Pagamento Secretaria','{\"pagamento_id\":\"12\"}','::1','2026-04-05 15:32:19'),(233,177,'Validar Pagamento Secretaria','{\"pagamento_id\":\"11\"}','::1','2026-04-05 15:32:26'),(234,177,'Validar Pagamento Secretaria','{\"pagamento_id\":\"10\"}','::1','2026-04-05 15:32:35'),(235,177,'Validar Pagamento Secretaria','{\"pagamento_id\":\"9\"}','::1','2026-04-05 15:32:43'),(236,1,'Regularizar TAE + Selos','{\"estudante_id\":176}','::1','2026-04-08 02:14:05'),(237,1,'Regularizar TAE + Selos','{\"estudante_id\":176}','::1','2026-04-08 02:14:30');
/*!40000 ALTER TABLE `logs_atividades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs_notas`
--

DROP TABLE IF EXISTS `logs_notas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs_notas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nota_id` int(11) DEFAULT NULL,
  `estudante_id` int(11) DEFAULT NULL,
  `turma_id` int(11) DEFAULT NULL,
  `disciplina_id` int(11) DEFAULT NULL,
  `tipo_avaliacao_id` int(11) DEFAULT NULL,
  `valor_anterior` decimal(5,2) DEFAULT NULL,
  `valor_novo` decimal(5,2) DEFAULT NULL,
  `alterado_por` int(11) DEFAULT NULL,
  `motivo` text DEFAULT NULL,
  `data_alteracao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs_notas`
--

LOCK TABLES `logs_notas` WRITE;
/*!40000 ALTER TABLE `logs_notas` DISABLE KEYS */;
INSERT INTO `logs_notas` VALUES (1,16,135,7,29,1,1.20,2.00,189,'Lançamento/Correção Professor','2026-04-02 01:30:09'),(2,17,135,7,29,2,2.90,3.00,189,'Lançamento/Correção Professor','2026-04-02 01:30:09'),(3,18,135,7,29,3,3.00,2.00,189,'Lançamento/Correção Professor','2026-04-02 01:30:09'),(4,19,135,7,29,4,7.00,10.00,189,'Lançamento/Correção Professor','2026-04-02 01:30:09'),(5,11,137,7,30,1,1.00,2.00,186,'Lançamento/Correção Professor','2026-04-04 01:12:01'),(6,12,137,7,30,2,1.00,2.00,186,'Lançamento/Correção Professor','2026-04-04 01:12:01'),(7,13,137,7,30,3,1.00,3.90,186,'Lançamento/Correção Professor','2026-04-04 01:12:01'),(8,14,137,7,30,4,1.00,8.00,186,'Lançamento/Correção Professor','2026-04-04 01:12:01'),(9,15,137,7,30,5,16.00,16.00,186,'Lançamento/Correção Professor','2026-04-04 01:12:01'),(10,11,137,7,30,1,2.00,2.00,186,'Lançamento/Correção Professor','2026-04-04 01:12:11'),(11,12,137,7,30,2,2.00,2.00,186,'Lançamento/Correção Professor','2026-04-04 01:12:11'),(12,13,137,7,30,3,3.90,3.90,186,'Lançamento/Correção Professor','2026-04-04 01:12:11'),(13,14,137,7,30,4,8.00,8.00,186,'Lançamento/Correção Professor','2026-04-04 01:12:11'),(14,15,137,7,30,5,16.00,16.00,186,'Lançamento/Correção Professor','2026-04-04 01:12:11');
/*!40000 ALTER TABLE `logs_notas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `materiais`
--

DROP TABLE IF EXISTS `materiais`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `materiais` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `turma_id` int(11) NOT NULL,
  `disciplina_id` int(11) NOT NULL,
  `professor_id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `nome_ficheiro` varchar(255) NOT NULL,
  `caminho_ficheiro` varchar(255) NOT NULL,
  `tipo_ficheiro` varchar(50) DEFAULT NULL,
  `data_upload` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `turma_id` (`turma_id`),
  KEY `disciplina_id` (`disciplina_id`),
  KEY `professor_id` (`professor_id`),
  CONSTRAINT `materiais_ibfk_1` FOREIGN KEY (`turma_id`) REFERENCES `turmas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `materiais_ibfk_2` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `materiais_ibfk_3` FOREIGN KEY (`professor_id`) REFERENCES `professores` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `materiais`
--

LOCK TABLES `materiais` WRITE;
/*!40000 ALTER TABLE `materiais` DISABLE KEYS */;
INSERT INTO `materiais` VALUES (1,7,1,1,'Façam exercícios','Análise de matemática I.pdf','public/uploads/materiais/1774218961_Análise de matemática I.pdf','pdf','2026-03-22 22:36:01'),(2,7,30,1,'Façam exercícios','Recibo de Pagamento - GHS.pdf','public/uploads/materiais/MAT_1774363631_9f33345a3260.pdf','pdf','2026-03-24 14:47:11');
/*!40000 ALTER TABLE `materiais` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `matriculas`
--

DROP TABLE IF EXISTS `matriculas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `matriculas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `estudante_id` int(11) NOT NULL,
  `ano_letivo` year(4) NOT NULL,
  `ano_curso_id` int(11) NOT NULL,
  `turma_id` int(11) DEFAULT NULL,
  `especializacao_id` int(11) DEFAULT NULL,
  `turno` enum('Manhã','Tarde','Noite') NOT NULL,
  `grupo` varchar(50) DEFAULT 'G1',
  `tipo` enum('Novo Ingresso','Estudante Interno') NOT NULL,
  `status` enum('Pendente','Em validacao','Aprovada','Rejeitada') DEFAULT 'Pendente',
  `numero_processo` varchar(20) DEFAULT NULL,
  `data_matricula` date NOT NULL,
  `observacoes` text DEFAULT NULL,
  `aprovado_por` int(11) DEFAULT NULL,
  `data_aprovacao` date DEFAULT NULL,
  `motivo_rejeicao` text DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero_processo` (`numero_processo`),
  KEY `ano_curso_id` (`ano_curso_id`),
  KEY `turma_id` (`turma_id`),
  KEY `especializacao_id` (`especializacao_id`),
  KEY `aprovado_por` (`aprovado_por`),
  KEY `idx_estudante_ano` (`estudante_id`,`ano_letivo`),
  KEY `idx_status` (`status`),
  KEY `idx_numero_processo` (`numero_processo`),
  KEY `idx_matriculas_estudante_status` (`estudante_id`,`status`),
  CONSTRAINT `matriculas_ibfk_1` FOREIGN KEY (`estudante_id`) REFERENCES `estudantes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `matriculas_ibfk_2` FOREIGN KEY (`ano_curso_id`) REFERENCES `anos` (`id`),
  CONSTRAINT `matriculas_ibfk_3` FOREIGN KEY (`turma_id`) REFERENCES `turmas` (`id`) ON DELETE SET NULL,
  CONSTRAINT `matriculas_ibfk_4` FOREIGN KEY (`especializacao_id`) REFERENCES `especializacoes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `matriculas_ibfk_5` FOREIGN KEY (`aprovado_por`) REFERENCES `utilizadores` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=179 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `matriculas`
--

LOCK TABLES `matriculas` WRITE;
/*!40000 ALTER TABLE `matriculas` DISABLE KEYS */;
INSERT INTO `matriculas` VALUES (1,1,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30'),(2,2,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30'),(3,3,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30'),(4,4,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30'),(5,5,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30'),(6,6,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30'),(7,7,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30'),(8,8,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30'),(9,9,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30'),(10,10,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30'),(11,11,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30'),(12,12,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30'),(13,13,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31'),(14,14,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31'),(15,15,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31'),(16,16,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31'),(17,17,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31'),(18,18,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31'),(19,19,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31'),(20,20,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31'),(21,21,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31'),(22,22,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31'),(23,23,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31'),(24,24,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31'),(25,25,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31'),(26,26,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31'),(27,27,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32'),(28,28,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32'),(29,29,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32'),(30,30,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32'),(31,31,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32'),(32,32,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32'),(33,33,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32'),(34,34,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32'),(35,35,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32'),(36,36,2025,1,10,NULL,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32'),(37,37,2025,1,11,NULL,'Tarde','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32'),(38,38,2025,1,11,NULL,'Tarde','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32'),(39,39,2025,1,11,NULL,'Tarde','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32'),(40,40,2025,1,11,NULL,'Tarde','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32'),(41,41,2025,1,11,NULL,'Tarde','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32'),(42,42,2025,1,11,NULL,'Tarde','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33'),(43,43,2025,1,11,NULL,'Tarde','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33'),(44,44,2025,1,11,NULL,'Tarde','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33'),(45,45,2025,1,11,NULL,'Tarde','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33'),(46,46,2025,1,11,NULL,'Tarde','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33'),(47,47,2025,1,11,NULL,'Tarde','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33'),(48,48,2025,1,11,NULL,'Tarde','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33'),(49,49,2025,1,11,NULL,'Tarde','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33'),(50,50,2025,1,11,NULL,'Tarde','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33'),(51,51,2025,1,11,NULL,'Tarde','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33'),(52,52,2025,1,11,NULL,'Tarde','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33'),(53,53,2025,1,11,NULL,'Tarde','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33'),(54,54,2025,1,11,NULL,'Tarde','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33'),(55,55,2025,1,11,NULL,'Tarde','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33'),(56,56,2025,1,11,NULL,'Tarde','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33'),(57,57,2025,1,11,NULL,'Tarde','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33'),(58,58,2025,1,11,NULL,'Tarde','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34'),(59,59,2025,1,11,NULL,'Tarde','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34'),(60,60,2025,1,11,NULL,'Tarde','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34'),(61,61,2025,1,11,NULL,'Tarde','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34'),(62,62,2025,1,11,NULL,'Tarde','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34'),(63,63,2025,1,11,NULL,'Tarde','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34'),(64,64,2025,1,11,NULL,'Tarde','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34'),(65,65,2025,1,11,NULL,'Tarde','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34'),(66,66,2025,1,11,NULL,'Tarde','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34'),(67,67,2025,1,11,NULL,'Tarde','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34'),(68,68,2025,1,11,NULL,'Tarde','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34'),(69,69,2025,1,11,NULL,'Tarde','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34'),(70,70,2025,1,11,NULL,'Tarde','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34'),(71,71,2025,1,12,NULL,'Noite','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34'),(72,72,2025,1,12,NULL,'Noite','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35'),(73,73,2025,1,12,NULL,'Noite','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35'),(74,74,2025,1,12,NULL,'Noite','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35'),(75,75,2025,1,12,NULL,'Noite','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35'),(76,76,2025,1,12,NULL,'Noite','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35'),(77,77,2025,1,12,NULL,'Noite','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35'),(78,78,2025,1,12,NULL,'Noite','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35'),(79,79,2025,2,13,NULL,'Manhã','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35'),(80,80,2025,2,13,NULL,'Manhã','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35'),(81,81,2025,2,13,NULL,'Manhã','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35'),(82,82,2025,2,13,NULL,'Manhã','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35'),(83,83,2025,2,13,NULL,'Manhã','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35'),(84,84,2025,2,13,NULL,'Manhã','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35'),(85,85,2025,2,13,NULL,'Manhã','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35'),(86,86,2025,2,13,NULL,'Manhã','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35'),(87,87,2025,2,13,NULL,'Manhã','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35'),(88,88,2025,2,13,NULL,'Manhã','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36'),(89,89,2025,2,13,NULL,'Manhã','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36'),(90,90,2025,2,13,NULL,'Manhã','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36'),(91,91,2025,2,13,NULL,'Manhã','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36'),(92,92,2025,2,13,NULL,'Manhã','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36'),(93,93,2025,2,13,NULL,'Manhã','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36'),(94,94,2025,2,13,NULL,'Manhã','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36'),(95,95,2025,2,13,NULL,'Manhã','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36'),(96,96,2025,2,13,NULL,'Manhã','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36'),(97,97,2025,2,13,NULL,'Manhã','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36'),(98,98,2025,2,13,NULL,'Manhã','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36'),(99,99,2025,2,13,NULL,'Manhã','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36'),(100,100,2025,2,13,NULL,'Manhã','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36'),(101,101,2025,2,14,NULL,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36'),(102,102,2025,2,14,NULL,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36'),(103,103,2025,2,14,NULL,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37'),(104,104,2025,2,14,NULL,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37'),(105,105,2025,2,14,NULL,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37'),(106,106,2025,2,14,NULL,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37'),(107,107,2025,2,14,NULL,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37'),(108,108,2025,2,14,NULL,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37'),(109,109,2025,2,14,NULL,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37'),(110,110,2025,2,14,NULL,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37'),(111,111,2025,2,14,NULL,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37'),(112,112,2025,2,14,NULL,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37'),(113,113,2025,2,14,NULL,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37'),(114,114,2025,2,14,NULL,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37'),(115,115,2025,3,16,NULL,'Manhã','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37'),(116,116,2025,3,16,NULL,'Manhã','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37'),(117,117,2025,3,16,NULL,'Manhã','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37'),(118,118,2025,3,16,NULL,'Manhã','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38'),(119,119,2025,3,16,NULL,'Manhã','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38'),(120,120,2025,3,16,NULL,'Manhã','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38'),(121,121,2025,3,16,NULL,'Manhã','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38'),(122,122,2025,3,16,NULL,'Manhã','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38'),(123,123,2025,3,16,NULL,'Manhã','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38'),(124,124,2025,3,16,NULL,'Manhã','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38'),(125,125,2025,3,16,NULL,'Manhã','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38'),(126,126,2025,3,16,NULL,'Manhã','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38'),(127,127,2025,3,9,NULL,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38'),(128,128,2025,3,9,NULL,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38'),(129,129,2025,3,9,NULL,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38'),(130,130,2025,3,9,NULL,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38'),(131,131,2025,3,9,NULL,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38'),(132,132,2025,3,9,NULL,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38'),(133,133,2025,3,9,NULL,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39'),(134,134,2025,3,9,NULL,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39'),(135,135,2025,4,7,NULL,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39'),(136,136,2025,4,7,NULL,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39'),(137,137,2025,4,7,NULL,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39'),(138,138,2025,4,7,NULL,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39'),(139,139,2025,4,7,NULL,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39'),(140,140,2025,4,7,NULL,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39'),(141,141,2025,4,7,NULL,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39'),(142,142,2025,4,7,NULL,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39'),(143,143,2025,4,7,NULL,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39'),(144,144,2025,4,7,NULL,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39'),(145,145,2025,4,7,NULL,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39'),(146,146,2025,4,7,NULL,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39'),(147,147,2025,5,20,4,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39'),(148,148,2025,5,20,4,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40'),(149,149,2025,5,20,4,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40'),(150,150,2025,5,20,4,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40'),(151,151,2025,5,20,4,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40'),(152,152,2025,5,20,4,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40'),(153,153,2025,5,20,4,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40'),(154,154,2025,5,20,4,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40'),(155,155,2025,5,20,4,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40'),(156,156,2025,5,20,4,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40'),(157,157,2025,5,20,4,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40'),(158,158,2025,5,20,4,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40'),(159,159,2025,5,20,4,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40'),(160,160,2025,5,20,4,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40'),(161,161,2025,5,20,4,'Tarde','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40'),(162,162,2025,5,19,3,'Noite','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40'),(163,163,2025,5,19,3,'Noite','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:41','2026-04-01 03:43:41'),(164,164,2025,5,19,3,'Noite','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:41','2026-04-01 03:43:41'),(165,165,2025,5,19,3,'Noite','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:41','2026-04-01 03:43:41'),(166,166,2025,5,19,3,'Noite','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:41','2026-04-01 03:43:41'),(167,167,2025,5,19,3,'Noite','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:41','2026-04-01 03:43:41'),(168,168,2025,5,19,3,'Noite','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:41','2026-04-01 03:43:41'),(169,169,2025,5,19,3,'Noite','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:41','2026-04-01 03:43:41'),(170,170,2025,5,19,3,'Noite','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:41','2026-04-01 03:43:41'),(171,171,2025,5,19,3,'Noite','G1','','Aprovada',NULL,'2026-04-01',NULL,NULL,NULL,NULL,'2026-04-01 03:43:41','2026-04-01 03:43:41'),(174,173,2025,1,NULL,3,'Manhã','G1','Novo Ingresso','Aprovada',NULL,'2026-04-01','',1,'2026-04-01',NULL,'2026-04-01 04:39:17','2026-04-01 04:40:04'),(175,173,2026,1,10,NULL,'Manhã','G1','Estudante Interno','Aprovada',NULL,'2026-04-01','',1,'2026-04-01',NULL,'2026-04-01 05:10:41','2026-04-01 05:10:41'),(176,174,2025,4,7,2,'Tarde','G1','','Aprovada',NULL,'2026-04-01','',1,'2026-04-01',NULL,'2026-04-01 06:48:03','2026-04-01 07:03:46'),(177,176,2025,4,7,4,'Tarde','G1','','Aprovada',NULL,'2026-04-03','',1,'2026-04-03',NULL,'2026-04-03 09:24:45','2026-04-03 10:29:36'),(178,177,2025,1,NULL,2,'Manhã','G1','Novo Ingresso','Rejeitada',NULL,'2026-04-03','',177,'2026-04-04','falta de documentos exigidos pela escola','2026-04-03 10:17:43','2026-04-04 03:07:31');
/*!40000 ALTER TABLE `matriculas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mensagens`
--

DROP TABLE IF EXISTS `mensagens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mensagens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `remetente_id` int(11) NOT NULL,
  `destinatario_id` int(11) NOT NULL,
  `assunto` varchar(200) NOT NULL,
  `mensagem` text NOT NULL,
  `lida` tinyint(1) DEFAULT 0,
  `data_leitura` datetime DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_remetente` (`remetente_id`),
  KEY `idx_destinatario` (`destinatario_id`),
  KEY `idx_lida` (`lida`),
  CONSTRAINT `mensagens_ibfk_1` FOREIGN KEY (`remetente_id`) REFERENCES `utilizadores` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mensagens_ibfk_2` FOREIGN KEY (`destinatario_id`) REFERENCES `utilizadores` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mensagens`
--

LOCK TABLES `mensagens` WRITE;
/*!40000 ALTER TABLE `mensagens` DISABLE KEYS */;
INSERT INTO `mensagens` VALUES (1,177,1,'Notificação de Sistema','A Secretaria REJEITOU a matrícula de Lurdes Pereira Tecanhe. Motivo: Não vejo nenhum documento..',1,'2026-04-01 04:33:24','2026-04-01 04:32:54'),(2,1,177,'Notificação de Sistema','Matrícula validada pelo Admin: Lurdes Pereira Tecanhe (lurdes@gmail.com).',1,'2026-04-01 06:52:00','2026-04-01 04:40:06'),(3,1,177,'Notificação de Sistema','Matrícula validada pelo Admin: Lurdes Pereira Tecanhe (lurdes@gmail.com).',1,'2026-04-01 06:52:00','2026-04-01 04:40:08'),(4,186,136,'Resposta a Reclamação de Nota','O Professor respondeu à sua reclamação de nota: \"Não há irregularidade com sua nota\"',0,NULL,'2026-04-01 05:19:43'),(5,186,137,'Resposta a Reclamação de Nota','O Professor respondeu à sua reclamação de nota: \"fghjkl\"',0,NULL,'2026-04-01 05:38:05'),(6,1,186,'Nova Contestação de Nota — Redes Digitais — Sistemas, Aplicação e Serviços','O(A) aluno(a) Dingana Nimina Embana contestou a sua avaliação em Redes Digitais — Sistemas, Aplicação e Serviços.\nPor favor, aceda ao seu portal para responder dentro do prazo.',0,NULL,'2026-04-01 06:33:52'),(7,1,137,'Actualização da Contestação — Redes Digitais — Sistemas, Aplicação e Serviços','O docente respondeu à sua contestação. Pode aceitar ou apresentar contra-argumentação.',0,NULL,'2026-04-01 06:35:34'),(8,1,1,'🚨 IMPASSE — Contestação de Nota: Redes Digitais — Sistemas, Aplicação e Serviços','Foi detectado um IMPASSE na contestação da nota de Dingana Nimina Embana na disciplina Redes Digitais — Sistemas, Aplicação e Serviços (Turma: GHS-4T1).\n\nContestação do aluno: A minha nota nata da CE não é essa\nResposta do docente: Não houve a irregularidade na sua nota.\nContra-argumentação: Como assim?\n\nAcção necessária: Convoque as partes para mediação presencial.',1,'2026-04-01 07:03:59','2026-04-01 06:36:45'),(9,1,137,'Convocatória: Mediação de Nota — Redes Digitais — Sistemas, Aplicação e Serviços','📋 CONVOCATÓRIA — Contestação de Nota: Redes Digitais — Sistemas, Aplicação e Serviços\n\nFoi agendada uma reunião de mediação para resolver a sua contestação.\n\n📅 Data: 13/04/2026\n⏰ Hora: 13:00\n📍 Local: Gabinete de Apoio Psicopedagógico (GAP)\n📌 Motivo: Convocatória obrigatória para resolução de impasse em avaliação académica.\n\nA sua presença é obrigatória. Em caso de impossibilidade, contacte a administração.',0,NULL,'2026-04-01 06:38:43'),(10,1,186,'Convocatória: Mediação de Nota — Redes Digitais — Sistemas, Aplicação e Serviços','📋 CONVOCATÓRIA — Mediação de Nota: Redes Digitais — Sistemas, Aplicação e Serviços\n\nFoi agendada uma reunião de mediação referente à contestação do(a) aluno(a) Dingana Nimina Embana.\n\n📅 Data: 13/04/2026\n⏰ Hora: 13:00\n📍 Local: Gabinete de Apoio Psicopedagógico (GAP)\n📌 Motivo: Convocatória obrigatória para resolução de impasse em avaliação académica.\n\nA sua comparência é obrigatória.',0,NULL,'2026-04-01 06:38:43'),(11,177,1,'Notificação de Sistema','Pagamento validado pela Secretaria: Dingana Nimina Embana (ID: 1).',1,'2026-04-01 07:03:59','2026-04-01 06:51:41'),(12,1,137,'Contestação Encerrada: Redes Digitais — Sistemas, Aplicação e Serviços','✅ Processo de Contestação Encerrado\n\nDisciplina: Redes Digitais — Sistemas, Aplicação e Serviços\nDecisão da Administração:\n\nA administração decidiu que o aluno tem razão',0,NULL,'2026-04-01 07:02:42'),(13,1,186,'Contestação Encerrada: Redes Digitais — Sistemas, Aplicação e Serviços','✅ Processo de Contestação Encerrado\n\nDisciplina: Redes Digitais — Sistemas, Aplicação e Serviços\nDecisão da Administração:\n\nA administração decidiu que o aluno tem razão',0,NULL,'2026-04-01 07:02:42'),(14,1,177,'Notificação de Sistema','Matrícula validada pelo Admin: Darlene Mendes Nunes (darlenemendesnunes@hotmail.com).',1,'2026-04-01 07:18:39','2026-04-01 07:03:48'),(15,1,177,'Notificação de Sistema','Matrícula validada pelo Admin: Darlene Mendes Nunes (darlenemendesnunes@hotmail.com).',1,'2026-04-01 07:18:39','2026-04-01 07:03:50'),(16,1,177,'Notificação de Sistema','O Administrador validou o pagamento de Diosives Pedro Nunes Crobute (ID: 2).',1,'2026-04-01 17:31:50','2026-04-01 07:28:34'),(17,1,186,'Nova Contestação de Nota — Redes Digitais — Sistemas, Aplicação e Serviços','O(A) aluno(a) Amadú Julde Djaló contestou a sua avaliação em Redes Digitais — Sistemas, Aplicação e Serviços.\nPor favor, aceda ao seu portal para responder dentro do prazo.',0,NULL,'2026-04-01 12:45:44'),(18,1,136,'Actualização da Contestação — Redes Digitais — Sistemas, Aplicação e Serviços','O docente respondeu à sua contestação. Pode aceitar ou apresentar contra-argumentação.',0,NULL,'2026-04-01 12:47:38'),(19,1,1,'🚨 IMPASSE — Contestação de Nota: Redes Digitais — Sistemas, Aplicação e Serviços','Foi detectado um IMPASSE na contestação da nota de Amadú Julde Djaló na disciplina Redes Digitais — Sistemas, Aplicação e Serviços (Turma: GHS-4T1).\n\nContestação do aluno: Não concordo com a nota atribuida\nResposta do docente: Não há irregularidade na sua nota\nContra-argumentação: Eu discordo\n\nAcção necessária: Convoque as partes para mediação presencial.',1,'2026-04-01 15:51:49','2026-04-01 12:49:05'),(20,1,136,'CONVOCATÓRIA: Conflito de Nota','O Diretor convocou as partes para a resolução de conflito de nota na disciplina: Redes Digitais — Sistemas, Aplicação e Serviços.\n\nMotivo: Convocatória oficial para resolução de conflito de notas.\n\nPor favor, compareça à sala da Direção/Secretaria no próximo horário disponível.',0,NULL,'2026-04-01 12:49:38'),(21,1,186,'CONVOCATÓRIA: Conflito de Nota','O Diretor convocou as partes para a resolução de conflito de nota na disciplina: Redes Digitais — Sistemas, Aplicação e Serviços.\n\nMotivo: Convocatória oficial para resolução de conflito de notas.\n\nPor favor, compareça à sala da Direção/Secretaria no próximo horário disponível.',0,NULL,'2026-04-01 12:49:38'),(22,1,177,'Notificação de Sistema','O Administrador convocou as partes para a resolução de conflito de nota (Aluno ID: 135). Motivo: Convocatória oficial para resolução de conflito de notas.',1,'2026-04-01 17:31:50','2026-04-01 12:49:38'),(23,1,189,'Nova Contestação de Nota — Multimédia e Computação Gráfica','O(A) aluno(a) Amadú Julde Djaló contestou a sua avaliação em Multimédia e Computação Gráfica.\nPor favor, aceda ao seu portal para responder dentro do prazo.',0,NULL,'2026-04-02 01:24:49'),(24,1,1,'🚨 IMPASSE — Contestação de Nota: Multimédia e Computação Gráfica','Foi detectado um IMPASSE na contestação da nota de Amadú Julde Djaló na disciplina Multimédia e Computação Gráfica (Turma: GHS-4T1).\n\nContestação do aluno: Não vejo a media de exame do primeiro semestre.\nResposta do docente: \nContra-argumentação: Cade as nota do exame?\n\nAcção necessária: Convoque as partes para mediação presencial.',1,'2026-04-02 01:41:08','2026-04-02 01:31:04'),(25,1,136,'CONVOCATÓRIA: Conflito de Nota','O Diretor convocou as partes para a resolução de conflito de nota na disciplina: Multimédia e Computação Gráfica.\n\nMotivo: Convocatória oficial para resolução de conflito de notas.\n\nPor favor, compareça à sala da Direção/Secretaria no próximo horário disponível.',0,NULL,'2026-04-02 01:31:38'),(26,1,189,'CONVOCATÓRIA: Conflito de Nota','O Diretor convocou as partes para a resolução de conflito de nota na disciplina: Multimédia e Computação Gráfica.\n\nMotivo: Convocatória oficial para resolução de conflito de notas.\n\nPor favor, compareça à sala da Direção/Secretaria no próximo horário disponível.',0,NULL,'2026-04-02 01:31:38'),(27,1,177,'Notificação de Sistema','O Administrador convocou as partes para a resolução de conflito de nota (Aluno ID: 135). Motivo: Convocatória oficial para resolução de conflito de notas.',1,'2026-04-05 15:23:37','2026-04-02 01:31:38'),(28,1,177,'Notificação de Sistema','O Administrador validou o pagamento de Amadú Julde Djaló (ID: 4).',1,'2026-04-05 15:23:37','2026-04-02 01:47:50'),(29,1,186,'Nova Contestação de Nota — Redes Digitais — Sistemas, Aplicação e Serviços','O(A) aluno(a) Diosives Pedro Nunes Crobute contestou a sua avaliação em Redes Digitais — Sistemas, Aplicação e Serviços.\nPor favor, aceda ao seu portal para responder dentro do prazo.',0,NULL,'2026-04-03 09:49:21'),(30,1,138,'Actualização da Contestação — Redes Digitais — Sistemas, Aplicação e Serviços','O docente actualizou a sua nota. Por favor, verifique.',0,NULL,'2026-04-03 09:51:44'),(31,1,1,'🚨 IMPASSE — Contestação de Nota: Redes Digitais — Sistemas, Aplicação e Serviços','Foi detectado um IMPASSE na contestação da nota de Diosives Pedro Nunes Crobute na disciplina Redes Digitais — Sistemas, Aplicação e Serviços (Turma: GHS-4T1).\n\nContestação do aluno: Protesto essa nota.\nResposta do docente: Não há irregularidade\nContra-argumentação: Discordo\n\nAcção necessária: Convoque as partes para mediação presencial.',1,'2026-04-03 10:29:55','2026-04-03 09:52:38'),(32,1,138,'CONVOCATÓRIA: Conflito de Nota','O Diretor convocou as partes para a resolução de conflito de nota na disciplina: Redes Digitais — Sistemas, Aplicação e Serviços.\n\nMotivo: Convocatória oficial para resolução de conflito de notas.\n\nPor favor, compareça à sala da Direção/Secretaria no próximo horário disponível.',0,NULL,'2026-04-03 09:53:18'),(33,1,186,'CONVOCATÓRIA: Conflito de Nota','O Diretor convocou as partes para a resolução de conflito de nota na disciplina: Redes Digitais — Sistemas, Aplicação e Serviços.\n\nMotivo: Convocatória oficial para resolução de conflito de notas.\n\nPor favor, compareça à sala da Direção/Secretaria no próximo horário disponível.',0,NULL,'2026-04-03 09:53:18'),(34,1,177,'Notificação de Sistema','O Administrador convocou as partes para a resolução de conflito de nota (Aluno ID: 137). Motivo: Convocatória oficial para resolução de conflito de notas.',1,'2026-04-05 15:23:37','2026-04-03 09:53:18'),(35,1,177,'Notificação de Sistema','Matrícula validada pelo Admin: Edegar Nababo (edegar@gmail.com).',1,'2026-04-05 15:23:37','2026-04-03 10:29:38'),(36,1,177,'Notificação de Sistema','Matrícula validada pelo Admin: Edegar Nababo (edegar@gmail.com).',1,'2026-04-05 15:23:37','2026-04-03 10:29:40'),(37,177,1,'Notificação de Sistema','A Secretaria REJEITOU a matrícula de Juventino Gomes. Motivo: falta de documentos exigidos pela escola.',1,'2026-04-04 03:23:49','2026-04-04 03:07:31'),(38,177,1,'Notificação de Sistema','A Secretaria REJEITOU a matrícula de Juventino Gomes. Motivo: falta de documentos exigidos pela escola.',1,'2026-04-04 03:23:49','2026-04-04 03:07:33'),(39,177,1,'Notificação de Sistema','A Secretaria REJEITOU a matrícula de Juventino Gomes. Motivo: falta de documentos exigidos pela escola.',1,'2026-04-04 03:23:49','2026-04-04 03:07:35'),(40,177,1,'Notificação de Sistema','A Secretaria REJEITOU a matrícula de Juventino Gomes. Motivo: falta de documentos exigidos pela escola.',1,'2026-04-04 03:23:49','2026-04-04 03:07:38'),(41,177,1,'Notificação de Sistema','A Secretaria REJEITOU a matrícula de Juventino Gomes. Motivo: falta de documentos exigidos pela escola.',1,'2026-04-04 03:23:49','2026-04-04 03:07:40'),(42,177,1,'Notificação de Sistema','A Secretaria REJEITOU a matrícula de Juventino Gomes. Motivo: falta de documentos exigidos pela escola.',1,'2026-04-04 03:23:49','2026-04-04 03:07:42'),(43,177,1,'Notificação de Sistema','A Secretaria REJEITOU a matrícula de Juventino Gomes. Motivo: falta de documentos exigidos pela escola.',1,'2026-04-04 03:23:49','2026-04-04 03:07:44'),(44,177,1,'Notificação de Sistema','A Secretaria REJEITOU a matrícula de Juventino Gomes. Motivo: falta de documentos exigidos pela escola.',1,'2026-04-04 03:23:49','2026-04-04 03:07:46'),(45,177,1,'Notificação de Sistema','A Secretaria REJEITOU a matrícula de Juventino Gomes. Motivo: falta de documentos exigidos pela escola.',1,'2026-04-04 03:23:49','2026-04-04 03:07:48'),(46,177,1,'Notificação de Sistema','A Secretaria REJEITOU a matrícula de Juventino Gomes. Motivo: falta de documentos exigidos pela escola.',1,'2026-04-04 03:23:49','2026-04-04 03:07:50'),(47,177,1,'Notificação de Sistema','A Secretaria REJEITOU a matrícula de Juventino Gomes. Motivo: falta de documentos exigidos pela escola.',1,'2026-04-04 03:23:49','2026-04-04 03:07:52'),(48,177,1,'Notificação de Sistema','A Secretaria REJEITOU a matrícula de Juventino Gomes. Motivo: falta de documentos exigidos pela escola.',1,'2026-04-04 03:23:49','2026-04-04 03:07:54'),(49,177,1,'Notificação de Sistema','A Secretaria REJEITOU a matrícula de Juventino Gomes. Motivo: falta de documentos exigidos pela escola.',1,'2026-04-04 03:23:49','2026-04-04 03:07:56'),(50,177,1,'Notificação de Sistema','A Secretaria REJEITOU a matrícula de Juventino Gomes. Motivo: falta de documentos exigidos pela escola.',1,'2026-04-04 03:23:49','2026-04-04 03:07:58'),(51,177,1,'Notificação de Sistema','A Secretaria REJEITOU a matrícula de Juventino Gomes. Motivo: falta de documentos exigidos pela escola.',1,'2026-04-04 03:23:49','2026-04-04 03:08:01'),(52,177,1,'Notificação de Sistema','A Secretaria REJEITOU a matrícula de Juventino Gomes. Motivo: falta de documentos exigidos pela escola.',1,'2026-04-04 03:23:49','2026-04-04 03:08:03'),(53,177,1,'Notificação de Sistema','A Secretaria REJEITOU a matrícula de Juventino Gomes. Motivo: falta de documentos exigidos pela escola.',1,'2026-04-04 03:23:49','2026-04-04 03:08:05'),(54,177,1,'Notificação de Sistema','A Secretaria REJEITOU a matrícula de Juventino Gomes. Motivo: falta de documentos exigidos pela escola.',1,'2026-04-04 03:23:49','2026-04-04 03:08:07'),(55,177,1,'Notificação de Sistema','A Secretaria REJEITOU a matrícula de Juventino Gomes. Motivo: falta de documentos exigidos pela escola.',1,'2026-04-04 03:23:49','2026-04-04 03:08:09'),(56,177,1,'Notificação de Sistema','A Secretaria REJEITOU a matrícula de Juventino Gomes. Motivo: falta de documentos exigidos pela escola.',1,'2026-04-04 03:23:49','2026-04-04 03:08:11'),(57,177,1,'Notificação de Sistema','Pagamento validado pela Secretaria: Diosives Pedro Nunes Crobute (ID: 14).',1,'2026-04-08 00:34:28','2026-04-05 15:32:02'),(58,177,1,'Notificação de Sistema','Pagamento validado pela Secretaria: Diosives Pedro Nunes Crobute (ID: 13).',1,'2026-04-08 00:34:28','2026-04-05 15:32:10'),(59,177,1,'Notificação de Sistema','Pagamento validado pela Secretaria: Diosives Pedro Nunes Crobute (ID: 12).',1,'2026-04-08 00:34:28','2026-04-05 15:32:19'),(60,177,1,'Notificação de Sistema','Pagamento validado pela Secretaria: Diosives Pedro Nunes Crobute (ID: 11).',1,'2026-04-08 00:34:28','2026-04-05 15:32:26'),(61,177,1,'Notificação de Sistema','Pagamento validado pela Secretaria: Diosives Pedro Nunes Crobute (ID: 10).',1,'2026-04-08 00:34:28','2026-04-05 15:32:35'),(62,177,1,'Notificação de Sistema','Pagamento validado pela Secretaria: Diosives Pedro Nunes Crobute (ID: 9).',1,'2026-04-08 00:34:28','2026-04-05 15:32:43');
/*!40000 ALTER TABLE `mensagens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notas`
--

DROP TABLE IF EXISTS `notas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `estudante_id` int(11) NOT NULL,
  `avaliacao_id` int(11) NOT NULL,
  `nota` decimal(5,2) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `data_lancamento` timestamp NULL DEFAULT NULL,
  `lancado_por` int(11) DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `confirmado_admin` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_estudante_avaliacao` (`estudante_id`,`avaliacao_id`),
  KEY `lancado_por` (`lancado_por`),
  KEY `idx_estudante` (`estudante_id`),
  KEY `idx_avaliacao` (`avaliacao_id`),
  KEY `idx_notas_estudante` (`estudante_id`),
  KEY `idx_notas_avaliacao` (`avaliacao_id`),
  CONSTRAINT `notas_ibfk_1` FOREIGN KEY (`estudante_id`) REFERENCES `estudantes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `notas_ibfk_2` FOREIGN KEY (`avaliacao_id`) REFERENCES `avaliacoes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `notas_ibfk_3` FOREIGN KEY (`lancado_por`) REFERENCES `utilizadores` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notas`
--

LOCK TABLES `notas` WRITE;
/*!40000 ALTER TABLE `notas` DISABLE KEYS */;
INSERT INTO `notas` VALUES (1,135,1,1.00,NULL,NULL,186,'2026-04-01 05:16:47','2026-04-01 05:28:17',1),(2,135,2,2.00,NULL,NULL,186,'2026-04-01 05:16:47','2026-04-01 05:28:17',1),(3,135,3,3.00,NULL,NULL,186,'2026-04-01 05:16:47','2026-04-01 05:28:17',1),(4,135,4,3.00,NULL,NULL,186,'2026-04-01 05:16:47','2026-04-01 05:28:17',1),(5,135,5,14.00,NULL,NULL,186,'2026-04-01 05:16:47','2026-04-01 05:28:17',1),(6,136,1,2.00,NULL,NULL,186,'2026-04-01 05:29:56','2026-04-01 05:29:56',0),(7,136,2,1.10,NULL,NULL,186,'2026-04-01 05:29:56','2026-04-01 05:29:56',0),(8,136,3,2.90,NULL,NULL,186,'2026-04-01 05:29:56','2026-04-01 05:29:56',0),(9,136,4,6.00,NULL,NULL,186,'2026-04-01 05:29:56','2026-04-01 05:29:56',0),(10,136,5,11.90,NULL,NULL,186,'2026-04-01 05:29:56','2026-04-01 05:29:56',0),(11,137,1,2.00,NULL,NULL,186,'2026-04-01 07:24:46','2026-04-04 01:12:11',0),(12,137,2,2.00,NULL,NULL,186,'2026-04-01 07:24:46','2026-04-04 01:12:11',0),(13,137,3,3.90,NULL,NULL,186,'2026-04-01 07:24:46','2026-04-04 01:12:11',0),(14,137,4,8.00,NULL,NULL,186,'2026-04-01 07:24:46','2026-04-04 01:12:11',0),(15,137,5,16.00,NULL,NULL,186,'2026-04-01 07:24:46','2026-04-04 01:12:11',0),(16,135,6,2.00,NULL,NULL,189,'2026-04-02 01:23:36','2026-04-02 01:30:09',0),(17,135,7,3.00,NULL,NULL,189,'2026-04-02 01:23:36','2026-04-02 01:30:09',0),(18,135,8,2.00,NULL,NULL,189,'2026-04-02 01:23:36','2026-04-02 01:30:09',0),(19,135,9,10.00,NULL,NULL,189,'2026-04-02 01:23:36','2026-04-02 01:30:09',0),(20,138,5,15.00,NULL,NULL,186,'2026-04-08 02:47:32','2026-04-08 02:47:32',0);
/*!40000 ALTER TABLE `notas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pagamentos`
--

DROP TABLE IF EXISTS `pagamentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pagamentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `estudante_id` int(11) NOT NULL,
  `matricula_id` int(11) DEFAULT NULL,
  `tipo_pagamento_id` int(11) DEFAULT NULL,
  `ano_letivo` year(4) DEFAULT NULL,
  `mes_referencia` tinyint(4) DEFAULT NULL COMMENT '1-12 para mensalidades',
  `descricao` varchar(200) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `comprovativo_path` varchar(500) DEFAULT NULL,
  `data_vencimento` date NOT NULL,
  `data_pagamento` date DEFAULT NULL,
  `status` enum('Pendente','Pago','Vencido','Cancelado') DEFAULT 'Pendente',
  `forma_pagamento` enum('Dinheiro','Transferência','Mobile Money') DEFAULT NULL,
  `comprovativo_arquivo` varchar(255) DEFAULT NULL,
  `referencia_bancaria` varchar(100) DEFAULT NULL,
  `processado_por` int(11) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `matricula_id` (`matricula_id`),
  KEY `tipo_pagamento_id` (`tipo_pagamento_id`),
  KEY `processado_por` (`processado_por`),
  KEY `idx_estudante` (`estudante_id`),
  KEY `idx_status` (`status`),
  KEY `idx_data_vencimento` (`data_vencimento`),
  KEY `idx_pagamentos_estudante_status` (`estudante_id`,`status`),
  CONSTRAINT `pagamentos_ibfk_1` FOREIGN KEY (`estudante_id`) REFERENCES `estudantes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pagamentos_ibfk_2` FOREIGN KEY (`matricula_id`) REFERENCES `matriculas` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pagamentos_ibfk_3` FOREIGN KEY (`tipo_pagamento_id`) REFERENCES `tipos_pagamento` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pagamentos_ibfk_4` FOREIGN KEY (`processado_por`) REFERENCES `utilizadores` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pagamentos`
--

LOCK TABLES `pagamentos` WRITE;
/*!40000 ALTER TABLE `pagamentos` DISABLE KEYS */;
INSERT INTO `pagamentos` VALUES (1,136,NULL,NULL,NULL,NULL,'Abril',31500.00,NULL,'2026-05-01','2026-04-01','Pago',NULL,'public/uploads/comprovativos/PAG_136_94b52ef18bb4920c.pdf',NULL,177,'','2026-04-01 06:51:00','2026-04-01 06:51:41'),(4,135,NULL,NULL,NULL,NULL,'Março',31500.00,NULL,'2026-05-02','2026-04-02','Pago',NULL,'public/uploads/comprovativos/PAG_135_62b422538fd822f5.pdf',NULL,1,'','2026-04-02 01:43:36','2026-04-02 01:47:50'),(5,176,NULL,NULL,2026,7,'10º Mês (Julho) - Acto de Matrícula',31500.00,NULL,'2026-04-03','2026-04-03','Pago','',NULL,NULL,1,'Pagamento automático de taxas obrigatórias no acto da matrícula (Workflow GHS).','2026-04-03 10:29:36','2026-04-03 10:29:36'),(6,176,NULL,NULL,2026,0,'Taxa de Inscrição () - Acto de Matrícula',10000.00,NULL,'2026-04-03','2026-04-03','Pago','',NULL,NULL,1,'Pagamento automático de taxas obrigatórias no acto da matrícula (Workflow GHS).','2026-04-03 10:29:36','2026-04-03 10:29:36'),(7,176,NULL,NULL,2026,0,'Cartão de Estudante - Acto de Matrícula',2500.00,NULL,'2026-04-03','2026-04-03','Pago','',NULL,NULL,1,'Pagamento automático de taxas obrigatórias no acto da matrícula (Workflow GHS).','2026-04-03 10:29:36','2026-04-03 10:29:36'),(8,176,NULL,NULL,2026,0,'Caderneta de Notas - Acto de Matrícula',3000.00,NULL,'2026-04-03','2026-04-03','Pago','',NULL,NULL,1,'Pagamento automático de taxas obrigatórias no acto da matrícula (Workflow GHS).','2026-04-03 10:29:36','2026-04-03 10:29:36'),(9,137,NULL,NULL,NULL,NULL,'Outubro',31500.00,NULL,'2026-05-05','2026-04-05','Pago',NULL,'public/uploads/comprovativos/PAG_137_3ff9588507eb2a65.jpg',NULL,177,'','2026-04-05 15:28:18','2026-04-05 15:32:43'),(10,137,NULL,NULL,NULL,NULL,'Novembro',31500.00,NULL,'2026-05-05','2026-04-05','Pago',NULL,'public/uploads/comprovativos/PAG_137_92229bc006604dfe.png',NULL,177,'','2026-04-05 15:28:58','2026-04-05 15:32:35'),(11,137,NULL,NULL,NULL,NULL,'Dezembro',31500.00,NULL,'2026-05-05','2026-04-05','Pago',NULL,'public/uploads/comprovativos/PAG_137_5610a6c19deaf03c.jpg',NULL,177,'','2026-04-05 15:29:31','2026-04-05 15:32:26'),(12,137,NULL,NULL,NULL,NULL,'Janeiro',31500.00,NULL,'2026-05-05','2026-04-05','Pago',NULL,'public/uploads/comprovativos/PAG_137_4d162e36a5ed629a.png',NULL,177,'','2026-04-05 15:30:00','2026-04-05 15:32:19'),(13,137,NULL,NULL,NULL,NULL,'Fevereiro',31500.00,NULL,'2026-05-05','2026-04-05','Pago',NULL,'public/uploads/comprovativos/PAG_137_baa75e207137b04c.png',NULL,177,'','2026-04-05 15:30:50','2026-04-05 15:32:10'),(14,137,NULL,NULL,NULL,NULL,'Março',31500.00,NULL,'2026-05-05','2026-04-05','Pago',NULL,'public/uploads/comprovativos/PAG_137_7cc5726a99ad8ef9.jpg',NULL,177,'','2026-04-05 15:31:31','2026-04-05 15:32:02'),(15,176,NULL,NULL,2026,0,'Taxa de Associação de Estudante (TAE) - Acto de Matrícula',1000.00,NULL,'2026-04-08','2026-04-08','Pago','',NULL,NULL,1,'Regularização manual via recibo.','2026-04-08 02:14:05','2026-04-08 02:14:05'),(16,176,NULL,NULL,2026,0,'Selos de Estado (Legalização) - Acto de Matrícula',2000.00,NULL,'2026-04-08','2026-04-08','Pago','',NULL,NULL,1,'Regularização manual via recibo.','2026-04-08 02:14:05','2026-04-08 02:14:05');
/*!40000 ALTER TABLE `pagamentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `professor_disciplina`
--

DROP TABLE IF EXISTS `professor_disciplina`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `professor_disciplina` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `professor_id` int(11) NOT NULL,
  `disciplina_id` int(11) NOT NULL,
  `turma_id` int(11) DEFAULT NULL,
  `ano_letivo` year(4) NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_professor_disciplina_turma` (`professor_id`,`disciplina_id`,`turma_id`,`ano_letivo`),
  KEY `disciplina_id` (`disciplina_id`),
  KEY `turma_id` (`turma_id`),
  CONSTRAINT `professor_disciplina_ibfk_1` FOREIGN KEY (`professor_id`) REFERENCES `professores` (`id`) ON DELETE CASCADE,
  CONSTRAINT `professor_disciplina_ibfk_2` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `professor_disciplina_ibfk_3` FOREIGN KEY (`turma_id`) REFERENCES `turmas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `professor_disciplina`
--

LOCK TABLES `professor_disciplina` WRITE;
/*!40000 ALTER TABLE `professor_disciplina` DISABLE KEYS */;
INSERT INTO `professor_disciplina` VALUES (89,18,30,7,2026,'2026-04-02 01:17:44'),(90,18,25,16,2026,'2026-04-02 01:17:44'),(91,18,70,20,2026,'2026-04-02 01:17:44'),(92,19,29,7,2026,'2026-04-02 01:21:49');
/*!40000 ALTER TABLE `professor_disciplina` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `professores`
--

DROP TABLE IF EXISTS `professores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `professores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utilizador_id` int(11) NOT NULL,
  `bi` varchar(20) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `especialidade` varchar(100) DEFAULT NULL,
  `grau_academico` varchar(50) DEFAULT NULL,
  `data_contratacao` date DEFAULT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `bi` (`bi`),
  KEY `utilizador_id` (`utilizador_id`),
  KEY `idx_bi` (`bi`),
  CONSTRAINT `professores_ibfk_1` FOREIGN KEY (`utilizador_id`) REFERENCES `utilizadores` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `professores`
--

LOCK TABLES `professores` WRITE;
/*!40000 ALTER TABLE `professores` DISABLE KEYS */;
INSERT INTO `professores` VALUES (18,186,'BI00045','956678954','Redes de Computadores','Licenciado','2026-04-01',NULL,'2026-04-01 05:13:54','2026-04-01 05:13:54'),(19,189,'BI67081','95677777','Multimédia e Computação Gráfica','Licenciado','2026-04-02',NULL,'2026-04-02 01:21:49','2026-04-02 01:21:49');
/*!40000 ALTER TABLE `professores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sumarios`
--

DROP TABLE IF EXISTS `sumarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sumarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `professor_id` int(11) NOT NULL,
  `turma_id` int(11) NOT NULL,
  `disciplina_id` int(11) NOT NULL,
  `tempo` varchar(20) DEFAULT NULL,
  `data` date NOT NULL,
  `conteudo` text NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `confirmado_admin` tinyint(1) DEFAULT 0,
  `assinatura_digital` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sumarios`
--

LOCK TABLES `sumarios` WRITE;
/*!40000 ALTER TABLE `sumarios` DISABLE KEYS */;
/*!40000 ALTER TABLE `sumarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipos_avaliacao`
--

DROP TABLE IF EXISTS `tipos_avaliacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipos_avaliacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `pontuacao_maxima` decimal(5,2) NOT NULL,
  `peso_relativo` decimal(5,2) DEFAULT NULL,
  `ordem` tinyint(4) NOT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipos_avaliacao`
--

LOCK TABLES `tipos_avaliacao` WRITE;
/*!40000 ALTER TABLE `tipos_avaliacao` DISABLE KEYS */;
INSERT INTO `tipos_avaliacao` VALUES (1,'TPC','Trabalho para Casa',2.00,NULL,1,1),(2,'AP','Apresentação',3.00,NULL,2,1),(3,'TPI','Trabalho Prático Individual',5.00,NULL,3,1),(4,'CE','Chamada Escrita',10.00,NULL,4,1),(5,'EX','Exame Semestral',20.00,NULL,5,1);
/*!40000 ALTER TABLE `tipos_avaliacao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipos_pagamento`
--

DROP TABLE IF EXISTS `tipos_pagamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipos_pagamento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `recorrente` tinyint(1) DEFAULT 0,
  `obrigatorio` tinyint(1) DEFAULT 1,
  `descricao` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipos_pagamento`
--

LOCK TABLES `tipos_pagamento` WRITE;
/*!40000 ALTER TABLE `tipos_pagamento` DISABLE KEYS */;
INSERT INTO `tipos_pagamento` VALUES (1,'INSC_NOVO','Inscrição (Novo Ingresso)',15000.00,0,1,NULL),(2,'INSC_INTERNO','Inscrição (Estudante Interno)',10000.00,0,1,NULL),(3,'TAE','TAE (Taxa Académica de Estudante)',1000.00,1,1,NULL),(4,'FOLHA_PROVA','Folha de Prova',2000.00,1,1,NULL),(5,'CADERNETA','Caderneta de Notas',3000.00,1,1,NULL),(6,'AVAL_CONT','Avaliação Contínua',3500.00,1,1,NULL),(7,'CARTAO','Cartão de Estudante',2500.00,1,1,NULL),(8,'','Propina',0.00,0,1,NULL);
/*!40000 ALTER TABLE `tipos_pagamento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `turmas`
--

DROP TABLE IF EXISTS `turmas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `turmas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) NOT NULL,
  `ano_id` int(11) NOT NULL,
  `turno` enum('Manhã','Tarde','Noite') NOT NULL,
  `numero_turma` tinyint(4) NOT NULL,
  `sala_principal` varchar(20) DEFAULT NULL,
  `vagas` int(11) DEFAULT 30,
  `ativa` tinyint(1) DEFAULT 1,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `idx_codigo` (`codigo`),
  KEY `idx_ano_turno` (`ano_id`,`turno`),
  CONSTRAINT `turmas_ibfk_1` FOREIGN KEY (`ano_id`) REFERENCES `anos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `turmas`
--

LOCK TABLES `turmas` WRITE;
/*!40000 ALTER TABLE `turmas` DISABLE KEYS */;
INSERT INTO `turmas` VALUES (7,'GHS-4T1',4,'Tarde',2,'Laboratorio 2',15,1,'2026-03-22 20:36:25'),(9,'GHS-3T1',3,'Tarde',3,'',15,1,'2026-03-23 00:48:57'),(10,'GHS-1M1',1,'Manhã',1,'1',34,1,'2026-03-23 05:55:32'),(11,'GHS-1T1',1,'Tarde',1,'1',30,1,'2026-03-23 05:56:11'),(12,'GHS-1N1',1,'Noite',1,'1',30,1,'2026-03-23 05:56:39'),(13,'GHS-2M1',2,'Manhã',1,'2',20,1,'2026-03-23 05:57:15'),(14,'GHS-2T1',2,'Tarde',1,'Sala 02',16,1,'2026-03-23 05:58:46'),(15,'GHS-2N1',2,'Noite',1,'Sala 02',13,1,'2026-03-23 05:59:53'),(16,'GHS-3M1',3,'Manhã',3,'Sala 03',18,1,'2026-03-23 06:01:21'),(17,'GHS-3N1',3,'Noite',3,'Sala 03',10,1,'2026-03-23 06:02:03'),(18,'GHS-4N1',4,'Noite',4,'Sala 04',12,1,'2026-03-23 06:03:01'),(19,'GHS-5NBD1',5,'Noite',5,'Sala 05',11,1,'2026-03-23 06:05:00'),(20,'GHS-5TRD1',5,'Tarde',5,'Sala 05',13,1,'2026-03-23 06:06:28');
/*!40000 ALTER TABLE `turmas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utilizadores`
--

DROP TABLE IF EXISTS `utilizadores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `utilizadores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome_completo` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('admin','secretaria','professor','aluno') NOT NULL,
  `status` enum('ativo','inativo','pendente') DEFAULT 'pendente',
  `data_aprovacao` datetime DEFAULT NULL,
  `token_confirmacao` varchar(100) DEFAULT NULL,
  `token_recuperacao` varchar(100) DEFAULT NULL,
  `token_expira` datetime DEFAULT NULL,
  `ultimo_acesso` datetime DEFAULT NULL,
  `ip_registo` varchar(45) DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `tentativas_login` int(11) DEFAULT 0,
  `bloqueado_ate` datetime DEFAULT NULL,
  `codigo_2fa` varchar(10) DEFAULT NULL,
  `expiracao_2fa` datetime DEFAULT NULL,
  `requires_pw_change` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_email` (`email`),
  KEY `idx_tipo` (`tipo`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=192 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utilizadores`
--

LOCK TABLES `utilizadores` WRITE;
/*!40000 ALTER TABLE `utilizadores` DISABLE KEYS */;
INSERT INTO `utilizadores` VALUES (1,'Administrador GHS','diretor_ghs@gmail.com','$2y$10$G9sYCqjtrLTQQ9xG3ipdWe7e9CbCmYlpiFWG6a.oV87Xru1eYe7wS','admin','ativo','2026-04-01 03:28:34',NULL,NULL,NULL,'2026-04-08 00:54:01',NULL,'2026-04-01 03:28:34','2026-04-08 00:54:01',0,NULL,NULL,NULL,0),(2,'Adalgiza da Costa','adalgiza.costa@ghs.school','$2y$10$xtx56h9lebi3aQ07sPv9zemzxM5dwOAABkAHfsF0o36IT9XrI2U0K','aluno','ativo','2026-04-01 03:43:30',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30',0,NULL,NULL,NULL,0),(3,'Adul Carimo Baldé','adul.baldé@ghs.school','$2y$10$JFwFndYytpY6mPJIkvnusOnz.Ht9B3iqQABE4nALWLumiPAcqt/ne','aluno','ativo','2026-04-01 03:43:30',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30',0,NULL,NULL,NULL,0),(4,'Aminata Djamila Injai','aminata.injai@ghs.school','$2y$10$HsIHP56QbooVWHHZSLDpGuOUU.0493tbStUqqxE9hvKOp39PUCXde','aluno','ativo','2026-04-01 03:43:30',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30',0,NULL,NULL,NULL,0),(5,'António Alberto Lopes','antónio.lopes@ghs.school','$2y$10$m7uG9PXnKPJvv/MIcO24aeunvCH1dzxQCefsqwxrWj3HXIFX8TH6S','aluno','ativo','2026-04-01 03:43:30',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30',0,NULL,NULL,NULL,0),(6,'Artimiza Augusto Tcham','artimiza.tcham@ghs.school','$2y$10$QE8LiDojioxEZi8batMq6.4w.FEw0Ai05tYIqJOYmB8Ei8lVH73cC','aluno','ativo','2026-04-01 03:43:30',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30',0,NULL,NULL,NULL,0),(7,'Benoni Domingos Pereira','benoni.pereira@ghs.school','$2y$10$hOkedhFJXos0k2wHvXtP5uNa6tPyVwjP4.HjPlBqvpCZX783kBue6','aluno','ativo','2026-04-01 03:43:30',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30',0,NULL,NULL,NULL,0),(8,'Binto Camará','binto.camará@ghs.school','$2y$10$Xv1DfRlxzuMcQZ563hQgButptrES0Jbl4a5DYUcLLUyWNBHIAoTpu','aluno','ativo','2026-04-01 03:43:30',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30',0,NULL,NULL,NULL,0),(9,'Bissiqué Joaquim','bissiqué.joaquim@ghs.school','$2y$10$o6G3HrfZISQgEfxwOxW3b.jdXsv.vX93eiZSqCfiRywhWwLVZ6mJ.','aluno','ativo','2026-04-01 03:43:30',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30',0,NULL,NULL,NULL,0),(10,'Brolim António Damas','brolim.damas@ghs.school','$2y$10$1e7FtNwfNQw6vAoesSToduSpvjrnKly5bMmxL63RL5dgWJE9DleNO','aluno','ativo','2026-04-01 03:43:30',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30',0,NULL,NULL,NULL,0),(11,'Burama Gil Pombo','burama.pombo@ghs.school','$2y$10$mEyhrd4iSOvdCYOKv3zUzeL5UM7Z9B1VSieYXZqq2CSpkZoxpQ5GG','aluno','ativo','2026-04-01 03:43:30',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30',0,NULL,NULL,NULL,0),(12,'Carlita Mangar','carlita.mangar@ghs.school','$2y$10$WzAC2WKsToonEFtj61OWD.i50V8XGgiez0c1.ajJTFLQ./gwTi8rO','aluno','ativo','2026-04-01 03:43:30',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30',0,NULL,NULL,NULL,0),(13,'Davicson Lona Mbana','davicson.mbana@ghs.school','$2y$10$n3.GEU7uU.N19X6raqvmIO4n0YtkJ0Gh0KMplW3cwEoC507klZdji','aluno','ativo','2026-04-01 03:43:30',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:30','2026-04-01 03:43:30',0,NULL,NULL,NULL,0),(14,'Desejado Correia Forbs','desejado.forbs@ghs.school','$2y$10$RZ.i5UMFqcFGicrGM9Ja7ekoTZ0NfxY6mo78Ym2hgYxl60aVeXaCO','aluno','ativo','2026-04-01 03:43:31',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31',0,NULL,NULL,NULL,0),(15,'Eleutério Emilio Dias Monteiro','eleutério.monteiro@ghs.school','$2y$10$VmyGvjlZy9TfHlKF4Derb.GGph.lGzCRbst8SQllvcm/YTF9LkCE2','aluno','ativo','2026-04-01 03:43:31',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31',0,NULL,NULL,NULL,0),(16,'Elson Correia','elson.correia@ghs.school','$2y$10$W9iOdeDJxC.Wg6JHOsS3/uV6Up1pz15P/3/XGLGwVWpqpwTG6vUQq','aluno','ativo','2026-04-01 03:43:31',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31',0,NULL,NULL,NULL,0),(17,'Emanuel Duarte Djata','emanuel.djata@ghs.school','$2y$10$DQHmCqPrltz84S9voqEFDu4Khb9dZjFzckXn6Xkkcd7gEF4nlBXPS','aluno','ativo','2026-04-01 03:43:31',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31',0,NULL,NULL,NULL,0),(18,'Eugénio João Pereira','eugénio.pereira@ghs.school','$2y$10$4pd0JYNMVjqBgPuLj4d5k.B4WVbWt8ZRFw1X5aIN0r.eHMJaQqMi.','aluno','ativo','2026-04-01 03:43:31',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31',0,NULL,NULL,NULL,0),(19,'Eusébio Tcherno Mamudo Baldé','eusébio.baldé@ghs.school','$2y$10$2Bp5tVAI3nI/eKKRY0JTROf63uJOoKTQmRtcpQpnSREcj0ZUmpnJa','aluno','ativo','2026-04-01 03:43:31',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31',0,NULL,NULL,NULL,0),(20,'Fina Pereira','fina.pereira@ghs.school','$2y$10$g3LGM5HUahzk5CKrpWF6LeWFv6..qVyifMsvKmejvGZtBPKdeuMxm','aluno','ativo','2026-04-01 03:43:31',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31',0,NULL,NULL,NULL,0),(21,'Isaias Paulino Incundé','isaias.incundé@ghs.school','$2y$10$kY3E7AN0FUHa6krRqFNMg.ApvxMldxQsQsks1GO3vc8exTY5fuZPG','aluno','ativo','2026-04-01 03:43:31',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31',0,NULL,NULL,NULL,0),(22,'Jacinto Siverino Mancanha','jacinto.mancanha@ghs.school','$2y$10$RngKhlXvTLUU.VK/fq4vluClTwaeNnK6TESILs1LqHA4POlYW3wla','aluno','ativo','2026-04-01 03:43:31',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31',0,NULL,NULL,NULL,0),(23,'João Saliu Monteiro','joão.monteiro@ghs.school','$2y$10$1Pa6Lg7upwITewyx4cobg.8pJWqIxGsQoyTAHZr9zWZpRtnklgzRi','aluno','ativo','2026-04-01 03:43:31',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31',0,NULL,NULL,NULL,0),(24,'Lucas Paulo Ialá','lucas.ialá@ghs.school','$2y$10$Y/PRWAQeDBSrpe/nk3UZ5u3qGUf0GNvmmxjrPcS/DepA4Cn9wf8Wm','aluno','ativo','2026-04-01 03:43:31',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31',0,NULL,NULL,NULL,0),(25,'Luinela Edvises Papa Cá','luinela.cá@ghs.school','$2y$10$KrqqoBRoiV7Be8f4HXc3huw6/WqQoM2awoE3eY5gvX/OthEfb7LAW','aluno','ativo','2026-04-01 03:43:31',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31',0,NULL,NULL,NULL,0),(26,'Moises Sá','moises.sá@ghs.school','$2y$10$DJI6lodHrjV3lekgXqCJpO4Fr8EWNj1C2m7wtkXA7sm2TOmosnmui','aluno','ativo','2026-04-01 03:43:31',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31',0,NULL,NULL,NULL,0),(27,'Naziana Nisco de Carvalho','naziana.carvalho@ghs.school','$2y$10$CHee/eEDD4wZIYZLKw5Zp.bWUyZEbQI4AbE.bS0DUJJYx3Y1Midca','aluno','ativo','2026-04-01 03:43:31',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:31','2026-04-01 03:43:31',0,NULL,NULL,NULL,0),(28,'Nucia Tobana Vasna','nucia.vasna@ghs.school','$2y$10$cYH0hO91EdKEZBIVFgb0iu0EWpqlbJnV1sfaOMJM1F0Z6x24fqFqS','aluno','ativo','2026-04-01 03:43:32',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32',0,NULL,NULL,NULL,0),(29,'Raisa Ucalute Gomes','raisa.gomes@ghs.school','$2y$10$nmrvtIvb3rGIgXAba2hVKuVEto0yBDXazCxMf0KZKzx7L.Ioq1pKi','aluno','ativo','2026-04-01 03:43:32',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32',0,NULL,NULL,NULL,0),(30,'Rudilson Daivaneo Semedo Cá','rudilson.cá@ghs.school','$2y$10$11CeUEJGX5lqneu52QxJCON.LiNnPSXwSKW3lp7CQDGAC4gvS2gaG','aluno','ativo','2026-04-01 03:43:32',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32',0,NULL,NULL,NULL,0),(31,'Sabado Carlos Ntumbo','sabado.ntumbo@ghs.school','$2y$10$6fuhH/g3hCiRJZTsRqauBeMly1kMGnyEjgOljTrNmxdmj84CWv8tO','aluno','ativo','2026-04-01 03:43:32',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32',0,NULL,NULL,NULL,0),(32,'Saico Umaro Só','saico.só@ghs.school','$2y$10$U25MJXlz4CYqtOoJWJ6nu.ypxRpX4iv4tnVREs138ZqeCkGoaa2Cu','aluno','ativo','2026-04-01 03:43:32',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32',0,NULL,NULL,NULL,0),(33,'São João Fernando Carissali','são.carissali@ghs.school','$2y$10$oxqCHCb8RWukyEJgs1oPwurSbkA1NsGerFOhyMcTgW1ZrUJ6OCIVe','aluno','ativo','2026-04-01 03:43:32',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32',0,NULL,NULL,NULL,0),(34,'Silvano Augusto','silvano.augusto@ghs.school','$2y$10$CusGXtUjWNhx4jBivS98Au0UrBNeKmwp1SOOVTZizSk0o8BOKUv8e','aluno','ativo','2026-04-01 03:43:32',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32',0,NULL,NULL,NULL,0),(35,'Tcherno Mamadú Camará','tcherno.camará@ghs.school','$2y$10$qKgUmewwf9v.WYLz7aezYOScIT3ObSpq43OnNNc45mfrXOy3p5QE6','aluno','ativo','2026-04-01 03:43:32',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32',0,NULL,NULL,NULL,0),(36,'Timotio Sete','timotio.sete@ghs.school','$2y$10$EBwtW1FJhE2RqW.shCX22elxYdHXWFsdzxW.SVtcnddLJZniYB5QK','aluno','ativo','2026-04-01 03:43:32',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32',0,NULL,NULL,NULL,0),(37,'Vanilson Gomes da Costa','vanilson.costa@ghs.school','$2y$10$AJI6axVzoWwcQJcN3IN.8uK6hgalrmYvyIqCK3REfUPFYT8KGO3Ua','aluno','ativo','2026-04-01 03:43:32',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32',0,NULL,NULL,NULL,0),(38,'Adelio Cá','adelio.cá@ghs.school','$2y$10$B0XF/ReFcvBO7Cmq6OecXup.8xjREe9gMzMTf31i0PGGIu0KzaLjq','aluno','ativo','2026-04-01 03:43:32',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32',0,NULL,NULL,NULL,0),(39,'Aderito António Marcos','aderito.marcos@ghs.school','$2y$10$QJEIgzH/UWWqwU48nrTb7uVFi8myw6hcjmHblsUDFTzk4ujSPeIxq','aluno','ativo','2026-04-01 03:43:32',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32',0,NULL,NULL,NULL,0),(40,'Amade Embaló','amade.embaló@ghs.school','$2y$10$aMvf9zgWCj1I/qg52ygmve.tjGOk/Y3KenExMMs9rFoJHnab5T34G','aluno','ativo','2026-04-01 03:43:32',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32',0,NULL,NULL,NULL,0),(41,'Amadu Djulde Djaló','amadu.djaló@ghs.school','$2y$10$BY2j2XRRWd0K9bUeGRGhKuJ.OKYK6GNGhDjR1KJzjWcKyHkVBFWdC','aluno','ativo','2026-04-01 03:43:32',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32',0,NULL,NULL,NULL,0),(42,'Amadú E. Da Silva Nbotche','amadú.nbotche@ghs.school','$2y$10$15AkYmN2MPXznXAPSCkkf.69gCnApnDe7iYsFR1Lq9hagVPZx7HXS','aluno','ativo','2026-04-01 03:43:32',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:32','2026-04-01 03:43:32',0,NULL,NULL,NULL,0),(43,'Amonique Cá','amonique.cá@ghs.school','$2y$10$5LyavvidbGJ06gWAgcykdeAYNQBZ8P8p3RoezbdjjJ8KUdoeGU1F2','aluno','ativo','2026-04-01 03:43:33',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33',0,NULL,NULL,NULL,0),(44,'Bedamone Sandussa Nandiba','bedamone.nandiba@ghs.school','$2y$10$OvBN1KKY7.BfGyRwgoX3tOeCKCprVEKSrdCQ6fndG6ZywttiiVp9u','aluno','ativo','2026-04-01 03:43:33',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33',0,NULL,NULL,NULL,0),(45,'Benvinda Indiba','benvinda.indiba@ghs.school','$2y$10$/t1iBMx5c8CFpwCun7PKNe09/xmjff5ZR4WHl.4GFEdGeJb0IfHFW','aluno','ativo','2026-04-01 03:43:33',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33',0,NULL,NULL,NULL,0),(46,'Binta Cassamá','binta.cassamá@ghs.school','$2y$10$jAHv7z/XB//rAz3fldCz/.y0DiwSUkwH7qJZE3sMQp2PfQmDHDSKO','aluno','ativo','2026-04-01 03:43:33',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33',0,NULL,NULL,NULL,0),(47,'Dabana Lóa Na Tcharré','dabana.tcharré@ghs.school','$2y$10$D4Kg5lDdMdgf99XDGUIPSeFMdJsDNmIrsR7/IOfkKGgaK6mkmZ2py','aluno','ativo','2026-04-01 03:43:33',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33',0,NULL,NULL,NULL,0),(48,'Danilson Roel da Silva','danilson.silva@ghs.school','$2y$10$ZrPs54/OQlqjj/GFxHLeAO6Dq64qVU2kZ.noeuwUvw10F3cF4T2W2','aluno','ativo','2026-04-01 03:43:33',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33',0,NULL,NULL,NULL,0),(49,'Desejado Ilidio Dias','desejado.dias@ghs.school','$2y$10$E4S3Lu7X11U5Ccljfb16r.0mVZkNSmLv4fAwd5/LYAT.eD87CHHjO','aluno','ativo','2026-04-01 03:43:33',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33',0,NULL,NULL,NULL,0),(50,'Edimilson Augusto Malel','edimilson.malel@ghs.school','$2y$10$YlJ2PZtQsHlhoSNSCHvjqeu84BiJSGt.ZhPMlQ.KL7MSui5JBQoSy','aluno','ativo','2026-04-01 03:43:33',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33',0,NULL,NULL,NULL,0),(51,'Emanuela Nino Sambú','emanuela.sambú@ghs.school','$2y$10$1rfh6q3yxPmD8Nt32PMK5.IrjhtF/udpP610oARE3DrsvB.IZKzzO','aluno','ativo','2026-04-01 03:43:33',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33',0,NULL,NULL,NULL,0),(52,'Emerson Dias Baldé','emerson.baldé@ghs.school','$2y$10$.2qR4zu5.2Gf8c.EgRODduqecRNxyaUdSV2vE7H.4msCXlBVNp9We','aluno','ativo','2026-04-01 03:43:33',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33',0,NULL,NULL,NULL,0),(53,'Eugénio Marcelo Semedo','eugénio.semedo@ghs.school','$2y$10$Z716s9WiBW0fUoE/5rHU5uoF893cPkS4UfZVebaRCLMHG0aGsOxzS','aluno','ativo','2026-04-01 03:43:33',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33',0,NULL,NULL,NULL,0),(54,'Fodé Amara Sissé','fodé.sissé@ghs.school','$2y$10$rnDjMPpM2KH5rNvL8iuDFOKKSNcyZjrun1XT7nH8puUkwUh5T3MVW','aluno','ativo','2026-04-01 03:43:33',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33',0,NULL,NULL,NULL,0),(55,'Iasene Purna Ntchala','iasene.ntchala@ghs.school','$2y$10$TK0Fc.YTPlURuKZGJHUZf.K1OOCVwDpWxkxJ60NLu4zh9kd.2X00S','aluno','ativo','2026-04-01 03:43:33',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33',0,NULL,NULL,NULL,0),(56,'Ibraima Baldé','ibraima.baldé@ghs.school','$2y$10$kzZOgGMAsb.TwdInnA05auLD0kAShs2Y9iOjqFgof0TQccv2i/e9W','aluno','ativo','2026-04-01 03:43:33',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33',0,NULL,NULL,NULL,0),(57,'Jacira Correia','jacira.correia@ghs.school','$2y$10$K3.LMWFk8MjQ9bQxpOn4huEb6FjVLtpB75IT5Hcx0NIJa0DQDSQii','aluno','ativo','2026-04-01 03:43:33',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33',0,NULL,NULL,NULL,0),(58,'Juliana Gomes da Silva','juliana.silva@ghs.school','$2y$10$kNxSIsUWaEVONZ.acHvxPOQwmUjK1AUmhPmdsE.gnmgDUH0otSibW','aluno','ativo','2026-04-01 03:43:33',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:33','2026-04-01 03:43:33',0,NULL,NULL,NULL,0),(59,'Junior Augusto Landim','junior.landim@ghs.school','$2y$10$bfS151PwzqJgfdjaMYxWBu1JKYe2lVDrqWcRQyWqXwGKbwDO9kLQG','aluno','ativo','2026-04-01 03:43:34',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34',0,NULL,NULL,NULL,0),(60,'Leovanio Silvano Mendes','leovanio.mendes@ghs.school','$2y$10$EF0QwQQ/sWCWsvoIAmg9/udBtzXj4jHzuY.q8eU0ilfdGGKeDie6e','aluno','ativo','2026-04-01 03:43:34',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34',0,NULL,NULL,NULL,0),(61,'Mindo Aduquir Júnior da Silva','mindo.silva@ghs.school','$2y$10$pnTUjzg7IlRyQvmi3cesCee/kuOnul3U05G9yFuKVbt55Y1G3Oorm','aluno','ativo','2026-04-01 03:43:34',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34',0,NULL,NULL,NULL,0),(62,'Mohamadu Baldé','mohamadu.baldé@ghs.school','$2y$10$ZG1EB6sZJXegI5lm6FGZXu4Mk1YIU2ZWwLiwgFQtwmFhndLhLzaKK','aluno','ativo','2026-04-01 03:43:34',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34',0,NULL,NULL,NULL,0),(63,'Neia Ntchama','neia.ntchama@ghs.school','$2y$10$oqpGHeiQLMa1rab8PePE2umN/XqP3aem2bBh516dmy90T6H8l5ZUa','aluno','ativo','2026-04-01 03:43:34',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34',0,NULL,NULL,NULL,0),(64,'Nicaela Alfredo Correia','nicaela.correia@ghs.school','$2y$10$cKx9tleo8JkOz10K1ELKHuIUCk5F/7c7zQyo8tyIQ7mJE96TGmbZG','aluno','ativo','2026-04-01 03:43:34',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34',0,NULL,NULL,NULL,0),(65,'Pedro Seidi','pedro.seidi@ghs.school','$2y$10$7U5x84fU/KtgIDZK7i7EneJzvzQr4fc0yA5DrH/ZV0YaU8pf/Vbh6','aluno','ativo','2026-04-01 03:43:34',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34',0,NULL,NULL,NULL,0),(66,'Ronaldinho Edmilson Gomes Pereira','ronaldinho.pereira@ghs.school','$2y$10$MF4OBtt6SQC01in5zjwlfeWcz7LBxDNuqwfEQFGRKVm2ZgojInxQO','aluno','ativo','2026-04-01 03:43:34',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34',0,NULL,NULL,NULL,0),(67,'Salimatu Candé','salimatu.candé@ghs.school','$2y$10$0dgLgaCgIlje3o8JtLy88.op7fXH6h4BFibDo8efEjqN5nDY3Vudy','aluno','ativo','2026-04-01 03:43:34',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34',0,NULL,NULL,NULL,0),(68,'Silvio Luis Caetano','silvio.caetano@ghs.school','$2y$10$DuEKDsvpDqnH3Q2FOSPbmeo6k.E1.9wvu7zv1cRD/BzBeUUH57Ime','aluno','ativo','2026-04-01 03:43:34',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34',0,NULL,NULL,NULL,0),(69,'Ussumane Sané','ussumane.sané@ghs.school','$2y$10$hpQ/HnHGzZV1p2hvxMCCberNnCIDGl2uZpKl4OOvL9YLwRDLhRqUS','aluno','ativo','2026-04-01 03:43:34',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34',0,NULL,NULL,NULL,0),(70,'Valdimira Cabral Gomes','valdimira.gomes@ghs.school','$2y$10$Lth9ACAVmlK.jO0f/G2DD./DLqDuB46xte0U8x/MDmMbbhgVqaVtG','aluno','ativo','2026-04-01 03:43:34',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34',0,NULL,NULL,NULL,0),(71,'Vasco Alexandre Na Cul','vasco.cul@ghs.school','$2y$10$V7GvVb3BlmwXM9IwMX4kSOaebD6aCUb1KLyVzEpnPRdT0kxb/.O/e','aluno','ativo','2026-04-01 03:43:34',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34',0,NULL,NULL,NULL,0),(72,'Manjupe Lais da Costa','manjupe.costa@ghs.school','$2y$10$JeY7ODEu95FXk8Wai06YM.UKPUcKTcPcVZRONpogbLozfMy64m/YG','aluno','ativo','2026-04-01 03:43:34',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:34','2026-04-01 03:43:34',0,NULL,NULL,NULL,0),(73,'Alfredo Tchuda','alfredo.tchuda@ghs.school','$2y$10$aFelN2xo72WkEDS/b5nPb.6MS4h.uSqnlD4Nvg768rzsbgtdZSbsK','aluno','ativo','2026-04-01 03:43:35',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35',0,NULL,NULL,NULL,0),(74,'Arafam Cand','arafam.cand@ghs.school','$2y$10$BH9xrs1zsBg9lY2J6arXS.p./8HNEZ7SEbdL7dgrIg2UtycnsGhEO','aluno','ativo','2026-04-01 03:43:35',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35',0,NULL,NULL,NULL,0),(75,'Calido Djau','calido.djau@ghs.school','$2y$10$cgRwSjje9jRCpNucxQH3wORjTqt14W4hcHlhhOfTRJcrW9J1h77/G','aluno','ativo','2026-04-01 03:43:35',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35',0,NULL,NULL,NULL,0),(76,'Elizio João Pereira','elizio.pereira@ghs.school','$2y$10$08UyISoFp2TwRWI0M0EHbeNFt2CMuS3WOSxWlS8EqMWbIhvjj4Jga','aluno','ativo','2026-04-01 03:43:35',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35',0,NULL,NULL,NULL,0),(77,'Emanuel Biussum Iurna','emanuel.iurna@ghs.school','$2y$10$7WqH.kyD86jYFokTOekVkuHxy7hUPBw1Cq6TMSbzIHJYo.wzQcGv6','aluno','ativo','2026-04-01 03:43:35',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35',0,NULL,NULL,NULL,0),(78,'Sabino Carvalho','sabino.carvalho@ghs.school','$2y$10$0G.jrcQ37Q/f1n0ouWnMZO9RvRUsOVNk6Q9ZqoJDt50AnzWlb5CPW','aluno','ativo','2026-04-01 03:43:35',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35',0,NULL,NULL,NULL,0),(79,'Serginho Pedro Gomes Vilela','serginho.vilela@ghs.school','$2y$10$VzPXg9BYNAaKVxiAabQvperdIwtEy8xsLyPeKNOC3yc/rssF7L25y','aluno','ativo','2026-04-01 03:43:35',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35',0,NULL,NULL,NULL,0),(80,'Aliu Águas','aliu.Águas@ghs.school','$2y$10$G2EL/TuIv41gCSdUWJtdg.IFHlR8/ZxMG3NnFtMNG6I9TzZWvOh8e','aluno','ativo','2026-04-01 03:43:35',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35',0,NULL,NULL,NULL,0),(81,'Armando Ié','armando.ié@ghs.school','$2y$10$/ZYkc93G/Z4HD3uT3PFn2OZ/CR3feuB1jkklupEhP8akETpYPBbRa','aluno','ativo','2026-04-01 03:43:35',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35',0,NULL,NULL,NULL,0),(82,'Bidam-Mone Na Camine','bidam-mone.camine@ghs.school','$2y$10$obetb0iL5fX6Vu1CktYL8.zuhLLj4v0qgphur5p/toSXcN1T4LmFa','aluno','ativo','2026-04-01 03:43:35',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35',0,NULL,NULL,NULL,0),(83,'Celestina Gomes','celestina.gomes@ghs.school','$2y$10$D88hNNvLyf.EZn1uqw8Wh.ozeEPrrmb9si.ahptZzmRhJ/jAywXgi','aluno','ativo','2026-04-01 03:43:35',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35',0,NULL,NULL,NULL,0),(84,'Cesaltina Joaquim Bailambi','cesaltina.bailambi@ghs.school','$2y$10$yNTmiTtVH00pvqhmnSTM9.awFrbn0/RpH4sxgdfzd1Mgbodx1VvJe','aluno','ativo','2026-04-01 03:43:35',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35',0,NULL,NULL,NULL,0),(85,'Daimara Correia Mendes','daimara.mendes@ghs.school','$2y$10$Qr./fsBNvnFAJ.BBLgb0VOuMvme77lKF2vVWuZKCZV2.pyO3PQlOq','aluno','ativo','2026-04-01 03:43:35',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35',0,NULL,NULL,NULL,0),(86,'Endem Camará','endem.camará@ghs.school','$2y$10$7xXq15AXimpYk6bWoiDyROmOkEOwkMddTNM8FSDLTAS0Sbb9pvyAC','aluno','ativo','2026-04-01 03:43:35',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35',0,NULL,NULL,NULL,0),(87,'Fredinilton Pereira Bassali','fredinilton.bassali@ghs.school','$2y$10$7yEv5jraFk6rsHJpatik2uPXYUjt.3gNnHeZ0ftBTWnB6cYQ8RlNS','aluno','ativo','2026-04-01 03:43:35',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35',0,NULL,NULL,NULL,0),(88,'Isis Djibril Camará','isis.camará@ghs.school','$2y$10$o71YDbUoFAFdfsWnPC.NEOurLYpfbnpAAT6Atpx/WC7Ynba8gA5Zq','aluno','ativo','2026-04-01 03:43:35',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:35','2026-04-01 03:43:35',0,NULL,NULL,NULL,0),(89,'Jacir Abubacar Moreno Turé','jacir.turé@ghs.school','$2y$10$uqkeBKaLQ/F.lQ6G9tGtsOfcuU9Ilk0rb/hO62gR8CnkL9Vxala.K','aluno','ativo','2026-04-01 03:43:36',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36',0,NULL,NULL,NULL,0),(90,'Jovane Daniel Cutende','jovane.cutende@ghs.school','$2y$10$J9U9ePJjIEru9mg2DWjXue9ywDbXF1PHPzxIWx6fu7CRtEC1U9kwy','aluno','ativo','2026-04-01 03:43:36',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36',0,NULL,NULL,NULL,0),(91,'Juelson Mendes','juelson.mendes@ghs.school','$2y$10$dX0YTbu1kdPE7kF99b7oEeTZEmsaF83fm6NiyCc1.U/luzigthVO2','aluno','ativo','2026-04-01 03:43:36',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36',0,NULL,NULL,NULL,0),(92,'Juscelino Lopes','juscelino.lopes@ghs.school','$2y$10$WeW/CNwJGjcXUjcXQ4mSj.wGTXRwr31IOq1kicaNfTOc35pqHxA8.','aluno','ativo','2026-04-01 03:43:36',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36',0,NULL,NULL,NULL,0),(93,'Leorooney Mendes Sá Correia','leorooney.correia@ghs.school','$2y$10$7cMew4zoHa6tkbnkL1SmX.fDcwdbOKMzsS48g41w5ocg9E8.IH3Ne','aluno','ativo','2026-04-01 03:43:36',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36',0,NULL,NULL,NULL,0),(94,'Marcos Bissunha Ntchama','marcos.ntchama@ghs.school','$2y$10$iM8AjOhHYklGnmW10QV3v.PF8WiBFUjYsKM0aPsYxIYW7vTQNPOem','aluno','ativo','2026-04-01 03:43:36',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36',0,NULL,NULL,NULL,0),(95,'Marcos M. Nampunque','marcos.nampunque@ghs.school','$2y$10$C4Yn8xxLB10uGH9Wi22TdO.HuAlVYDg714ZvpgF5mWl.fBE3dCZaO','aluno','ativo','2026-04-01 03:43:36',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36',0,NULL,NULL,NULL,0),(96,'Mariama Tumane Quadé','mariama.quadé@ghs.school','$2y$10$CJ76jcQU0fZ1SJCsW/jukO1EjR2L6Plvx/Lx0UUtP8U8XhURbHBai','aluno','ativo','2026-04-01 03:43:36',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36',0,NULL,NULL,NULL,0),(97,'Ncaram Bunha Cumba','ncaram.cumba@ghs.school','$2y$10$7YHPLHu8Jq.XXFVXipL14.nkKPO5VnmbLSwv5vJv7SWQkCigAsV8S','aluno','ativo','2026-04-01 03:43:36',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36',0,NULL,NULL,NULL,0),(98,'Nelson Duarte da Silva','nelson.silva@ghs.school','$2y$10$Egj16XK4mJ48VfDb5K8reO.Fi5oEyMWUBtoMUhTTB6cNbqxwWBJu.','aluno','ativo','2026-04-01 03:43:36',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36',0,NULL,NULL,NULL,0),(99,'Nghale Wid Cumba','nghale.cumba@ghs.school','$2y$10$cuW/v6sGrSP86rPc7uSxeO2eOEpVwL4YnFlOcmeZU6B8b.p80UpNO','aluno','ativo','2026-04-01 03:43:36',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36',0,NULL,NULL,NULL,0),(100,'Tuncam Embaló','tuncam.embaló@ghs.school','$2y$10$ovhibTxBK1fBY7Dm7lV6BekaiW87qRm/5/N6eATI5B/SqoRGI/NHO','aluno','ativo','2026-04-01 03:43:36',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36',0,NULL,NULL,NULL,0),(101,'Zaira Alanam Nichudê','zaira.nichudê@ghs.school','$2y$10$Jvc6aWgtF9sSyaYlqCu7zuWd7cf20PC6XW5mbNXfcVzukK/Q5IlGK','aluno','ativo','2026-04-01 03:43:36',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36',0,NULL,NULL,NULL,0),(102,'Andre Djata','andre.djata@ghs.school','$2y$10$lqIYkhKjB.NyCFp1GnNd1uBupMTlL6loSCnRv2Y76VqZ0DPUxKNjS','aluno','ativo','2026-04-01 03:43:36',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36',0,NULL,NULL,NULL,0),(103,'António Tidjane Camará','antónio.camará@ghs.school','$2y$10$8JqHlO0ghxIgGENAfzMTnuIW9Uf17swzdcVuEOhEIg6Qv8G/US2rK','aluno','ativo','2026-04-01 03:43:36',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:36','2026-04-01 03:43:36',0,NULL,NULL,NULL,0),(104,'Carlos Alberto Correia','carlos.correia@ghs.school','$2y$10$vTJHD1P7pBLUvxbeujux7OE3X3jSd3/7ATnhyTZZeDBNIJFk9J8mS','aluno','ativo','2026-04-01 03:43:37',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37',0,NULL,NULL,NULL,0),(105,'Djabu Joãozinho da Costa','djabu.costa@ghs.school','$2y$10$toEJPn5Jv8QmAuhCq7C6Fe5B7LHlYM3ODnXpWMM1CPsvmJIrIgJJi','aluno','ativo','2026-04-01 03:43:37',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37',0,NULL,NULL,NULL,0),(106,'Eliana Correia Mendes','eliana.mendes@ghs.school','$2y$10$HDJymK1k/WBx2W5hKFAvCu1Y88k8eEFzTZajuC0yvlgJoH4Tt01hm','aluno','ativo','2026-04-01 03:43:37',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37',0,NULL,NULL,NULL,0),(107,'Iaia Seidi','iaia.seidi@ghs.school','$2y$10$hOVt7foisPUs3g.8hH4sZOgshdNrWGNlK5Y3XsW1qgDRENSJSHOHS','aluno','ativo','2026-04-01 03:43:37',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37',0,NULL,NULL,NULL,0),(108,'Ijaquiel Armando Sanca','ijaquiel.sanca@ghs.school','$2y$10$NicqNTio7oHIXh9IZAHwQ.t72jys4Atnaw/gy4nEFJK0qnucTS2MC','aluno','ativo','2026-04-01 03:43:37',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37',0,NULL,NULL,NULL,0),(109,'José Silva Nhaga','josé.nhaga@ghs.school','$2y$10$taZlyTyI7qnLG7ZbiWW3bu/48WJ1n42vBcIQoMmLjH8QvjphX1aqu','aluno','ativo','2026-04-01 03:43:37',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37',0,NULL,NULL,NULL,0),(110,'Luisella Mané Lopes dos Santos','luisella.santos@ghs.school','$2y$10$H.z4NFlw5cXqFA6C5PtJKOwmzEtBk5fca8kViZDCnz/UuKzjEJNFu','aluno','ativo','2026-04-01 03:43:37',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37',0,NULL,NULL,NULL,0),(111,'Mamadu Baldé','mamadu.baldé@ghs.school','$2y$10$oYJqA7UZIhJpfoxTubs5/.P/yxgwBnumDga3vdeV4nLysjzvjUwBi','aluno','ativo','2026-04-01 03:43:37',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37',0,NULL,NULL,NULL,0),(112,'Sat-na Faie Siga','sat-na.siga@ghs.school','$2y$10$wCh7xS9bXnNfTk5sQH/pGuLpB/BgyNOiWw3OqhW/WpMsiq0he.d3G','aluno','ativo','2026-04-01 03:43:37',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37',0,NULL,NULL,NULL,0),(113,'Sinaider F. da Silva','sinaider.silva@ghs.school','$2y$10$ucwRqcuH2QWlpl50VrKGROUet1SrrSA08WZaMKpJBAJTjKFnBukCu','aluno','ativo','2026-04-01 03:43:37',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37',0,NULL,NULL,NULL,0),(114,'Suaila Djata','suaila.djata@ghs.school','$2y$10$Bg2rzRizTIQAdBVSL/lGbO8/ISCMBZCWy7AthTDvznkgn8IcJMhF.','aluno','ativo','2026-04-01 03:43:37',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37',0,NULL,NULL,NULL,0),(115,'Zafenate Quintino Mondi','zafenate.mondi@ghs.school','$2y$10$LQgdiypYqG5GPPUtfNZruusId4bdeLck60/9hpwHEvDk4G/aKi6eS','aluno','ativo','2026-04-01 03:43:37',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37',0,NULL,NULL,NULL,0),(116,'Carlos Isnaba Bidonga','carlos.bidonga@ghs.school','$2y$10$Xmvoz/Tg0DVjavx82kS.YOQcdE6XGJX5lawoIzholYLhD4uecHnn6','aluno','ativo','2026-04-01 03:43:37',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37',0,NULL,NULL,NULL,0),(117,'Dias Domingos Ialá','dias.ialá@ghs.school','$2y$10$7REwTiFVdreh0zlrro.tE.3KUi3ii70s.XCBwdDkDkqv0zUKw2C8W','aluno','ativo','2026-04-01 03:43:37',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37',0,NULL,NULL,NULL,0),(118,'Dutim Rodrigues','dutim.rodrigues@ghs.school','$2y$10$m/zz51/Ws.nC1Yc7ZXDbCOAY2kgUn1ebIQNMk/VFULLqOdYMxbcsm','aluno','ativo','2026-04-01 03:43:37',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:37','2026-04-01 03:43:37',0,NULL,NULL,NULL,0),(119,'Elizabete Marena','elizabete.marena@ghs.school','$2y$10$2774BCpbzJKgchdR1M8ySOopalIbriAxPPw582gR/SEzRJtoRdQXa','aluno','ativo','2026-04-01 03:43:38',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38',0,NULL,NULL,NULL,0),(120,'Elizio da Silva','elizio.silva@ghs.school','$2y$10$Ukg4jSmf2trvdTbDhTLB4eMORWiBpegvzE8o6pv9Ms/gXQqHw0dL.','aluno','ativo','2026-04-01 03:43:38',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38',0,NULL,NULL,NULL,0),(121,'Hatissary Thayssa Sá Nogueira','hatissary.nogueira@ghs.school','$2y$10$b2H/DhmzL/8sp2fmc7unLeWUssVMtiwRUx0lSCGu/aX2GgnvUrHy.','aluno','ativo','2026-04-01 03:43:38',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38',0,NULL,NULL,NULL,0),(122,'Junaid Ibn Abulai Conté','junaid.conté@ghs.school','$2y$10$A/RJnV31M5Y4G6RFmD65juimEhiGLRHvQIrM8tfpIur0vD1EjjuNy','aluno','ativo','2026-04-01 03:43:38',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38',0,NULL,NULL,NULL,0),(123,'Massirem da Costa Djaló','massirem.djaló@ghs.school','$2y$10$Cb4WIK50Woh6F2u.sdnwleOq3bA5EztzJIj0f23WuLibMvWhT66s2','aluno','ativo','2026-04-01 03:43:38',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38',0,NULL,NULL,NULL,0),(124,'Samba Baldé','samba.baldé@ghs.school','$2y$10$HG2UHZGuwvBVmc2mEkFBNukH/fSqlOQGOBly7/srZpqdiqgo0M7Hy','aluno','ativo','2026-04-01 03:43:38',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38',0,NULL,NULL,NULL,0),(125,'Serifo Amadú Fadil Seidi','serifo.seidi@ghs.school','$2y$10$sqEx.I2DeyC1X4lnHxZGmOPtQWiUWSMM3xAWuoqwHCoQm0oGsyJ92','aluno','ativo','2026-04-01 03:43:38',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38',0,NULL,NULL,NULL,0),(126,'Tidjane Indjai','tidjane.indjai@ghs.school','$2y$10$rqrN786LqHAppHAhC65Iyu6kTFVV2sZGdpzI4b5bW69cEO6TQ6.Pe','aluno','ativo','2026-04-01 03:43:38',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38',0,NULL,NULL,NULL,0),(127,'Tcherno Camará','tcherno.camará89@ghs.school','$2y$10$8p89x8VdLCMH43fPv9vA2u5zgLD74cCJcVcLxpJZSEewiuIQwln4G','aluno','ativo','2026-04-01 03:43:38',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38',0,NULL,NULL,NULL,0),(128,'Buba Martinho Na Forna','buba.forna@ghs.school','$2y$10$ffd/H2pxeOtHqtAl4y0oDebEYAMO3/CMP5Abc8vO/euYkayVir/2.','aluno','ativo','2026-04-01 03:43:38',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38',0,NULL,NULL,NULL,0),(129,'Claus Roxin Jorge da Costa','claus.costa@ghs.school','$2y$10$fQO6CQV7HjJ3GhdCtCyuIOIr77URwhJRtvUVw6w.9.o8O8g8ESbqi','aluno','ativo','2026-04-01 03:43:38',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38',0,NULL,NULL,NULL,0),(130,'Fatumata Seidi','fatumata.seidi@ghs.school','$2y$10$yqqnIvDmZPWlHrmB/TdZHOacRCIfmmL1dTkQ4CbPwSqKN2rVKelq.','aluno','ativo','2026-04-01 03:43:38',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38',0,NULL,NULL,NULL,0),(131,'Gershon Quadé Ié','gershon.ié@ghs.school','$2y$10$YOZjAtuKpblJfE5yOBjiseW30doBccOcpdXsjsdHDSgniCVdZE7de','aluno','ativo','2026-04-01 03:43:38',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38',0,NULL,NULL,NULL,0),(132,'Jaqueline M. M. Lopes Nonaque','jaqueline.nonaque@ghs.school','$2y$10$FfkmXRila9E1ZhraAoo75u7p.S8PSkJBzC55MksSqILocsylCvjZq','aluno','ativo','2026-04-01 03:43:38',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38',0,NULL,NULL,NULL,0),(133,'Naida Na Biatchiba','naida.biatchiba@ghs.school','$2y$10$BrSUigto/M2p2ev50ddODu5gimXQjRTOLAnsVfBZI0D4eXIrOl1WC','aluno','ativo','2026-04-01 03:43:38',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:38','2026-04-01 03:43:38',0,NULL,NULL,NULL,0),(134,'Tussem Mendes','tussem.mendes@ghs.school','$2y$10$A01pPpP5lGJBfSvlgu1PKeDPCSwMQEIlrI4H.Ium0DcUOhSycaXVK','aluno','ativo','2026-04-01 03:43:39',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39',0,NULL,NULL,NULL,0),(135,'Ussumane Ponqué','ussumane.ponqué@ghs.school','$2y$10$/2n6I/gwW6u0.1TnCHwlN.780DyRsY2UdoxdQ37yqgu/p80zmANOW','aluno','ativo','2026-04-01 03:43:39',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39',0,NULL,NULL,NULL,0),(136,'Amadú Julde Djaló','amadudjalo4t1@ghs.com','$2y$10$nWR00nBvTRI5X4dCvs0M2uaJv29iHua.ar.d5ULuoL8BCrkkPVIEe','aluno','ativo','2026-04-01 03:43:39',NULL,NULL,NULL,'2026-04-02 01:12:43',NULL,'2026-04-01 03:43:39','2026-04-02 01:12:43',0,NULL,NULL,NULL,0),(137,'Dingana Nimina Embana','dinganaembana@ghs.com','$2y$10$2m1j.Tirh4vkX7rAnUUWGegcaa0NnM9a17P0vnilBBKUg4kEQMfAy','aluno','ativo','2026-04-01 03:43:39',NULL,NULL,NULL,'2026-04-01 07:22:04',NULL,'2026-04-01 03:43:39','2026-04-01 07:22:04',0,NULL,NULL,NULL,0),(138,'Diosives Pedro Nunes Crobute','diosives@gmail.com','$2y$10$P/hau8B9wtqEtrEwcRHWd.BWz8nzApLZ0D6mRCL/Fb71yZGjMlLIe','aluno','ativo','2026-04-01 03:43:39',NULL,NULL,NULL,'2026-04-08 00:55:45',NULL,'2026-04-01 03:43:39','2026-04-08 00:55:45',0,NULL,NULL,NULL,0),(139,'Djibril Tchamo','djibril.tchamo@ghs.school','$2y$10$Kszmx1LUfIm2VseiuGzpK.0QhCkFOsu8lvcYqwiMdquLHYbzrraha','aluno','ativo','2026-04-01 03:43:39',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39',0,NULL,NULL,NULL,0),(140,'Elizabete Vaz Moreno','elizabete.moreno@ghs.school','$2y$10$clvC6UmnCumYwzs4zjCdtehXMUbzueD5c.Dqel16/y6unWc2epLZC','aluno','ativo','2026-04-01 03:43:39',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39',0,NULL,NULL,NULL,0),(141,'Erikson Wogna Fanda','erikson.fanda@ghs.school','$2y$10$ientq5MSOMJrHmO4rw.fwOU4JeVMMSlyw72fRBFUUfSlkY8f0dsNa','aluno','ativo','2026-04-01 03:43:39',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39',0,NULL,NULL,NULL,0),(142,'Fernando Augusto Malú','fernando.malú@ghs.school','$2y$10$H92eWLVyRliRA4vB2fOV8.e8IscCjyj8km4f5qJEwIio53U/gotWC','aluno','ativo','2026-04-01 03:43:39',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39',0,NULL,NULL,NULL,0),(143,'Francisco N. Na Nhassé','francisco.nhassé@ghs.school','$2y$10$TzPqKPL3szcyOjEPMnSFv.UE0i6Ha6VgZ3UPUXQ/0L89MS/FvJb6W','aluno','ativo','2026-04-01 03:43:39',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39',0,NULL,NULL,NULL,0),(144,'Idjatu Dabó','idjatu.dabó@ghs.school','$2y$10$ZvwFKgfC7SNBBRNoONOzxe0W.AC8Tme9EPVFFMghbRCCS0bgkLhOO','aluno','ativo','2026-04-01 03:43:39',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39',0,NULL,NULL,NULL,0),(145,'Ivan Sajo Samananco','ivan.samananco@ghs.school','$2y$10$mAwOkKKUOHim3NsvbX/fPudAIx/Jth4rzA/V8aEXlayCnGumC0CRS','aluno','ativo','2026-04-01 03:43:39',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39',0,NULL,NULL,NULL,0),(146,'Luizela Sanhá Pereira Tecanhe','luizela.tecanhe@ghs.school','$2y$10$/aYQa2aMkfyiDtR1OsKJdeV6SEtglpQuvE1CtyD2O3pmXTXjM/uJy','aluno','ativo','2026-04-01 03:43:39',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39',0,NULL,NULL,NULL,0),(147,'Tiago Yalá','tiago.yalá@ghs.school','$2y$10$fMIjXtrKnrisAQ32nyDxI.mjMg0BAFK8M8EwRFDmPWhHd.dKHE3T.','aluno','ativo','2026-04-01 03:43:39',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39',0,NULL,NULL,NULL,0),(148,'Adulai Camará','adulai.camará@ghs.school','$2y$10$Dvx0iek.4eiLv9.GNv9VG.0rstc67dqYZfKpHv4S6AVwvJdGvl2/C','aluno','ativo','2026-04-01 03:43:39',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:39','2026-04-01 03:43:39',0,NULL,NULL,NULL,0),(149,'Aléssio José Rebelo Barbosa','aléssio.barbosa@ghs.school','$2y$10$SH/BXHB2ayRSCGfodtBXUuy85UzTKaxU9WeK0MKAeiff6LNQCzy06','aluno','ativo','2026-04-01 03:43:40',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40',0,NULL,NULL,NULL,0),(150,'Alqueia Nanque','alqueia.nanque@ghs.school','$2y$10$uk3VA2kjCkvWnpHixRwgjezRZ32vlHnW78H0JGOXlEtiXtF.uCjzq','aluno','ativo','2026-04-01 03:43:40',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40',0,NULL,NULL,NULL,0),(151,'Cabomarim Filipe Catame','cabomarim.catame@ghs.school','$2y$10$BTxefqNIkSgep38EoHDxsuZwGZYmkb6k378/pftCksiIbfVthWvrO','aluno','ativo','2026-04-01 03:43:40',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40',0,NULL,NULL,NULL,0),(152,'Domingos Fafé','domingos.fafé@ghs.school','$2y$10$xN5TArZXlijGm2cm408Z/uxMNX4qiFKEg.4xsjZeRHF8vADoKDSc.','aluno','ativo','2026-04-01 03:43:40',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40',0,NULL,NULL,NULL,0),(153,'Ela Candé','ela.candé@ghs.school','$2y$10$IeQGMUIMhF9W91tovm9Dh.98I.YeZz40pdApXEP3qVyyqJFIA1Yy6','aluno','ativo','2026-04-01 03:43:40',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40',0,NULL,NULL,NULL,0),(154,'Francisco Andre da Silva','francisco.silva@ghs.school','$2y$10$oGlWZ05PXYRp3shv.Tqp9uNK0gR4zs9tpUR1gUzzzm9N2bdaZPyRi','aluno','ativo','2026-04-01 03:43:40',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40',0,NULL,NULL,NULL,0),(155,'Isnaba Conha Inta-a','isnaba.inta-a@ghs.school','$2y$10$GrfKkh0bFiF4bq8P8Bb93.x174DiFF87pjlVUydolmBCz3qdA.N7i','aluno','ativo','2026-04-01 03:43:40',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40',0,NULL,NULL,NULL,0),(156,'Issa Djau','issa.djau@ghs.school','$2y$10$xxz1xIvOortWkYZ83tNS3uuTBMsaOpG8mX1DG4jm6n3XKE2TgrH1q','aluno','ativo','2026-04-01 03:43:40',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40',0,NULL,NULL,NULL,0),(157,'Madjer Moaquim Malam Sanhá Baió','madjer.baió@ghs.school','$2y$10$o/cHDid3McgsItQ7WysXxunhu.3JkZid5iv4gTqK58ODrKeojsX8a','aluno','ativo','2026-04-01 03:43:40',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40',0,NULL,NULL,NULL,0),(158,'Mafudje Bá Jau','mafudje.jau@ghs.school','$2y$10$nR9er73MJ/Saa8TU2Vr0N./Ab0of5Xrh7Q3k9acPszZBAk9HgvceS','aluno','ativo','2026-04-01 03:43:40',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40',0,NULL,NULL,NULL,0),(159,'Miriam Nhaga','miriam.nhaga@ghs.school','$2y$10$hm1TWECtjI1i9/6JTej1O.13QGFoKydMmx35l/vfuU6mrp2/683t6','aluno','ativo','2026-04-01 03:43:40',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40',0,NULL,NULL,NULL,0),(160,'Roberto Kabi Naguadé','roberto.naguadé@ghs.school','$2y$10$AzcsQpL7vbCGaFCYpCDz/ugeS6qcJ8CYHY9A5HxDx.MCduBWnHNPS','aluno','ativo','2026-04-01 03:43:40',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40',0,NULL,NULL,NULL,0),(161,'Uleimato Jaló','uleimato.jaló@ghs.school','$2y$10$Etx7SOjaWDuPtT.Fmgw4d.fjF/S2ZjijIvhkEJ.9UTbva5ONV5Zxm','aluno','ativo','2026-04-01 03:43:40',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40',0,NULL,NULL,NULL,0),(162,'Valeri Cardose','valeri.cardose@ghs.school','$2y$10$A18L/toh4C8q5EIzAf2PcOR3UnfamAykeeqqkQ3ZhiFb3Ktt4WMMa','aluno','ativo','2026-04-01 03:43:40',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40',0,NULL,NULL,NULL,0),(163,'Artimiza Iano Sá','artimiza.sá@ghs.school','$2y$10$WgDqTDI5bQhR/qINokRhfeEl7bG.YJ32NmX5RTk2tcF317O.ao9PG','aluno','ativo','2026-04-01 03:43:40',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:40','2026-04-01 03:43:40',0,NULL,NULL,NULL,0),(164,'Badora Agostinho Djata','badora.djata@ghs.school','$2y$10$w.U96YNkWuHIPcIkGDvVyeBuW2LuqHvPvYJN1xhIKGbgEWirqfGfq','aluno','ativo','2026-04-01 03:43:41',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:41','2026-04-01 03:43:41',0,NULL,NULL,NULL,0),(165,'Bubacar Baldé','bubacar.baldé@ghs.school','$2y$10$z3erkpvDObrl5VcUtOWvxO4Gc5sKsWvZ2xoe1HP.h400Wgivk7bMK','aluno','ativo','2026-04-01 03:43:41',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:41','2026-04-01 03:43:41',0,NULL,NULL,NULL,0),(166,'Felklin Pedro da Silva Júnior','felklin.júnior@ghs.school','$2y$10$a36PMcCvawbxAiC60vqhvuZxNJGgYobI0f5nnAMb0Yw1TsI5diilW','aluno','ativo','2026-04-01 03:43:41',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:41','2026-04-01 03:43:41',0,NULL,NULL,NULL,0),(167,'Ieró Baldé','ieró.baldé@ghs.school','$2y$10$6hfGh/xCr1tquXdTrI91D.fO/KGZCTBxU8CYB5FnxO5Ca6zEnpeAO','aluno','ativo','2026-04-01 03:43:41',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:41','2026-04-01 03:43:41',0,NULL,NULL,NULL,0),(168,'Ivana Ivanovica de Oliveira Quelute','ivana.quelute@ghs.school','$2y$10$4VOB6jiCnJJhOZ4eD6cEoe0r5lZGDx8RSwTZ0pbTM/vSCwpv5IXGC','aluno','ativo','2026-04-01 03:43:41',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:41','2026-04-01 03:43:41',0,NULL,NULL,NULL,0),(169,'Leonardo António Kassama','leonardo.kassama@ghs.school','$2y$10$KkuyTbTkFEDT2IrGXBk9W.2XyIETVgj14UxDp7kEJiO9HozpqINfW','aluno','ativo','2026-04-01 03:43:41',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:41','2026-04-01 03:43:41',0,NULL,NULL,NULL,0),(170,'Nadia Lopes Nank Ié','nadia.ié@ghs.school','$2y$10$0fu8.5hIp/f4fxJ4yB4TsuhMHG9uT3dYcyho4HIJIuAokvMsMGzX2','aluno','ativo','2026-04-01 03:43:41',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:41','2026-04-01 03:43:41',0,NULL,NULL,NULL,0),(171,'Pier Tanhá','pier.tanhá@ghs.school','$2y$10$H54vCRp9robGfuFFgPsDU.N2MJpIx9kSdlx5Y75ewiUopIZ3tYOsC','aluno','ativo','2026-04-01 03:43:41',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:41','2026-04-01 03:43:41',0,NULL,NULL,NULL,0),(172,'Quintino Nunes','quintino.nunes@ghs.school','$2y$10$FlphOFsySk2GenJmihF81O.jzjIlIrfTyOifxfEBtpbijILmTL0vi','aluno','ativo','2026-04-01 03:43:41',NULL,NULL,NULL,NULL,NULL,'2026-04-01 03:43:41','2026-04-01 03:43:41',0,NULL,NULL,NULL,0),(177,'Malam Djob','malamdjob@gmail.com','$2y$10$Yag4qrD/22l56R77Ih.0Z.D5Ru0z/4VyzPVE3XgtbGVxk.FxR..h.','secretaria','ativo',NULL,NULL,NULL,NULL,'2026-04-05 15:23:18',NULL,'2026-04-01 04:16:22','2026-04-05 15:23:18',0,NULL,NULL,NULL,0),(180,'Lurdes Pereira Tecanhe','lurdes@gmail.com','$2y$10$9ojVMILZRhry8ro1Fqv9Z.2NURM/r1.fiVcJBzICGWZV/nb3BrKM2','aluno','ativo','2026-04-01 04:40:06',NULL,NULL,NULL,NULL,NULL,'2026-04-01 04:39:17','2026-04-01 04:40:06',0,NULL,NULL,NULL,0),(186,'Domingos Correia','domingosredes@gmail.com','$2y$10$IXZnEQSpDMHgPAQuMj2Agux3JbaCLDBM1fFhJ9RSb1.G.NKWfSOBG','professor','ativo',NULL,NULL,NULL,NULL,'2026-04-08 02:46:28',NULL,'2026-04-01 05:13:54','2026-04-08 02:46:28',0,NULL,NULL,NULL,0),(187,'Darlene Mendes Nunes','darlenemendesnunes@hotmail.com','$2y$10$AHoLbp/38y74RPSDY5Z.hudOrHxxGowlAnvi7238J.zGDRuOSmg5.','aluno','ativo','2026-04-01 07:03:48',NULL,NULL,NULL,NULL,NULL,'2026-04-01 06:48:03','2026-04-01 07:03:48',0,NULL,NULL,NULL,0),(188,'Diogo Jorge Gomes','diogo@gmail.com','$2y$10$IBOkBtt.QaRbPkZz3TvTMOnJWqGwkEdUXioWWVQHH7JfUn4JUAws2','aluno','ativo',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 16:51:10','2026-04-01 16:51:10',0,NULL,NULL,NULL,0),(189,'Samba Djob','sambadjob@gmail.com','$2y$10$qJPSKgQLxOOLd3i1X4mtXeaFGP1vSCucnnx.pvRkgDyG5iN/o/pKa','professor','ativo',NULL,NULL,NULL,NULL,'2026-04-02 01:22:22',NULL,'2026-04-02 01:21:49','2026-04-02 01:22:22',0,NULL,NULL,NULL,0),(190,'Edegar Nababo','edegar@gmail.com','$2y$10$3XGJCDRreVRdt4Vzl3idLOeRPoj89gnmYCPTCF0xdN6xQC.jPnkMW','aluno','ativo','2026-04-03 10:29:38',NULL,NULL,NULL,NULL,NULL,'2026-04-03 09:24:45','2026-04-03 10:29:38',0,NULL,NULL,NULL,0),(191,'Juventino Gomes','diolindo819@gmail.com','$2y$10$JYjcmpydxAariNejdJC97O/GgZY01kS78SsKlho249WtOtXVhkgpi','aluno','ativo',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-03 10:17:43','2026-04-03 10:17:43',0,NULL,NULL,NULL,0);
/*!40000 ALTER TABLE `utilizadores` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-08  2:47:33
