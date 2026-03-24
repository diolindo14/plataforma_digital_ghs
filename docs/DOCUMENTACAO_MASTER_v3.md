# Documentação Completa: GHS Educational Platform v3.0

## 1. Visão Geral
A plataforma GHS é uma solução integrada de gestão académica e financeira, desenhada sob o padrão MVC em PHP nativo. Oferece portais dedicados para quatro perfis distintos, garantindo segurança, rastreabilidade e eficiência nos processos escolares.

---

## 2. Manuais de Utilizador

### 2.1 Portal do Administrador
O Administrador tem controlo total sobre a infraestrutura da escola.
- **Gestão de Alunos**: Ativação, desativação e reset de passwords.
- **Gestão de Equipa**: Cadastro e monitorização de Professores e Secretários.
- **Configuração Académica**: Definição de Anos Letivos, Turmas e Disciplinas.
- **Auditoria**: Visualização de logs em tempo real de todas as ações críticas do sistema.

### 2.2 Portal da Secretaria
Focado na operacionalização financeira e documental.
- **Validação de Matrículas**: Análise de documentos (B.I., Certificados) e aprovação/rejeição de pedidos.
- **Controlo de Pagamentos**: Validação de comprovativos de transferência e emissão de recibos digitais.
- **Comunicados**: Emissão de avisos para toda a comunidade escolar.

### 2.3 Portal do Professor
Interface pedagógica para gestão de sala de aula.
- **Sumários**: Lançamento de temas das aulas e registo de faltas.
- **Notas**: Lançamento e edição de avaliações por disciplina e turma.
- **Feedback**: Acompanhamento do desempenho dos alunos.

### 2.4 Portal do Estudante
Auto-serviço para alunos e encarregados.
- **Inscrição**: Processo de matrícula simplificado com upload de documentos.
- **Financeiro**: Consulta de propinas e submissão de comprovativos de pagamento.
- **Académico**: Consulta de notas, faltas e histórico escolar completo.

---

## 3. Especificações Técnicas e Segurança

### 3.1 Arquitetura MVC
- **Modelos (Models)**: Centralizam toda a lógica de negócio e consultas SQL.
- **Controladores (Controllers)**: Gerem as requisições e a lógica de fluxo.
- **Vistas (Views)**: Interfaces dinâmicas em PHP/HTML com estilização CSS moderna.

### 3.2 Camadas de Segurança (Hardening)
1. **Proteção CSRF**: Implementada em todos os formulários e chamadas AJAX.
2. **Logs de Auditoria**: Registo de IP, Utilizador e Ação em tempo real.
3. **Validação MIME**: Verificação real de ficheiros (anti-malware) em todos os uploads.
4. **CAPTCHA Nativo**: Prevenção de bots no registo de novos utilizadores.
5. **Passwords**: Política de alteração obrigatória no primeiro acesso.

---

## 4. Guia de Manutenção
- **Base de Dados**: MariaDB/MySQL. O ficheiro de dump seguro encontra-se em `/docs/backups/database.sql`.
- **Logs de Erro**: Localizados em `app/logs/error.log`.
- **Scripts de Desenvolvimento**: Localizados em `/docs/dev/`.

---

> [!TIP]
> **COMO EXPORTAR PARA PDF**:
> 1. Abra este ficheiro no **VS Code**.
> 2. Pressione `Ctrl+Shift+P` e procure por "Markdown: Export as PDF" (necessário extensão *Markdown PDF*).
> 3. Alternativamente, copie este conteúdo para o **Google Docs** ou **Word** e selecione "Guardar como PDF". Ceramica Ceramica Ceramica Ceramica Ceramica Ceramica Ceramica Ceramica Ceramica Ceramica
