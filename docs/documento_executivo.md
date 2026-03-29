# Resumo Executivo: Green Hard & Softh (GHS)

## 1. Visão Geral do Sistema
O sistema acadêmico Green Hard & Softh (GHS) foi desenvolvido para digitalizar e otimizar totalmente os processos pedagógicos, financeiros e de comunicação da instituição. Trata-se de uma plataforma unificada que suporta a jornada do estudante desde a matrícula à atribuição de notas finais, alinhando a escola às melhores práticas de gestão digital.

## 2. Pilares Operacionais
- **Gestão Pedagógica Dinâmica**: Implementação de um fluxo integral desde a alocação de turmas, disciplinas até ao lançamento de notas e validação da assiduidade através de sumários eletrónicos semanais, garantindo controlo rigoroso sobre a lecionação.
- **Portais Específicos para Perfil**: Painéis de utilizador estritamente desenhados para a experiência-alvo (Administração, Professores, Estudantes, Secretaria e Tesouraria).
- **Métricas e Dashboards Interativos**: Integração de gráficos estatísticos do número de alunos inscritos, percentagens de presenças, métricas de distribuição por classe/turno, e quadros de Honra (Mérito Académico).

## 3. Segurança e Auditoria Institucional
- **Proteção Completa (CSRF e XSS)**: A arquitetura está reforçada contra as vulnerabilidades vitais na web, encriptando palavras-passe, prevenindo ataques maliciosos via submissões injetadas e blindando as descargas diretas de anexos e identificadores de URL (IDOR mitigado).
- **Rastreabilidade**: Mantém-se de forma nativa um registo audito de todas as atividades sensíveis executadas pelas diretorias, desde cancelamentos financeiros até limpezas no calendário ou alocação de bolsas.

## 4. Retorno Sobre o Investimento (ROI) Tecnológico
Ao convergir o processamento administrativo (faturação local/bancária manual, pautas e certificados PDF nativos, horários PDF nativos) para um sistema com modelo base de dados relacional sólido (MySQL) e linguagem nativa otimizada (PHP MVC s/ excesso de Frameworks pesadas):
- Custos indiretos em consumíveis ou redundância caem mais de 80%.
- A agilidade no lançamento de comunicados para as turmas via painel administrativo torna a adoção instantânea.
- Calendários letivos globais agora embutem a sincronização de eventos com alcance modular (Ex: "Apenas Professores" ou "Global").

## 5. Escalabilidade
O projeto está preparado para acolher a expansão via integração com API externas (M-Pesa / Integrações de pagamentos diretos) nas fases seguintes. O desenho arquitetural Model-View-Controller permite que equipas terceiras evoluam módulos pontuais (como RH ou Gestão de Frotas Escolares) sem afetar o Core existente.
