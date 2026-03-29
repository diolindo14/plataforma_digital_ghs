# Documentação Completa: GHS Educational Platform v5.0

## 1. Visão Geral
A plataforma GHS é uma solução robusta de gestão académica e financeira. Na versão 5.0, consolidamos o motor de regras pedagógicas e reforçamos as camadas de proteção de dados, oferecendo uma experiência fluida para administradores, professores e alunos.

---

## 2. Componentes Estratégicos

### 2.1 Resumo Executivo (Gestão)
Focado no controle institucional e saúde financeira.
- **Dashboards Visual**: Gráficos de densidade estudantil e distribuição por turnos.
- **Gestão de Tesouraria**: Conciliação de pagamentos manuais e automáticos.
- **Audit Logs**: Rastreabilidade total de ações administrativas.

### 2.2 Manual do Utilizador (Funcional)
Experiência de auto-serviço e pedagogia digital.
- **Horários e Calendário**: Visualização dinâmica e exportação.
- **Histórico Global**: Registro vitalício de desempenho académico.
- **Integrador PDF.js**: Visualização segura de documentos sensíveis.

### 2.3 README Técnico (Desenvolvedor)
Infraestrutura e segurança de nível bancário.
- **Security Hardening**: Proteção ativa contra CSRF, XSS, SQLi e IDOR.
- **Motor Académico**: Algoritmos complexos de progressão (Regra das 3 negativas).
- **Validação de Registro**: CAPTCHA nativo e verificação de integridade documental (finfo).

---

## 3. Manutenção e Suporte
- **Base de Dados**: MariaDB/MySQL (Dump em `/docs/backups/`).
- **Logs**: Centralizados em `app/logs/error.log`.
- **Exportação**: Utilize as ferramentas em `/docs/export_*.php` para gerar manuais em PDF.

---

> [!TIP]
> **COMO GERAR OS MANUAIS EM PDF**:
> 1. Aceda via browser aos ficheiros `export_admin.php`, `export_funcional.php` ou `export_tecnica.php`.
> 2. Pressione `Ctrl + P`.
> 3. Selecione "Guardar como PDF" para obter os documentos oficiais com a formatação profissional.
