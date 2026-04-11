<?php // Resumo Executivo GHS v2.0 ?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>GHS — Resumo Executivo v2.0</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary:#111827;--accent:#2563eb;--accent-green:#059669;--light:#f9fafb;--border:#e2e8f0;--text:#374151;--muted:#6b7280; }
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Inter',sans-serif;color:var(--text);background:#fff;padding:40px 60px;line-height:1.6;font-size:13px}
        .cover{display:flex;align-items:flex-start;justify-content:space-between;border-bottom:2px solid var(--border);padding-bottom:25px;margin-bottom:35px}
        .cover-left .logo{font-size:16px;font-weight:700;color:var(--primary);letter-spacing:.5px}
        .cover-left .logo span{color:var(--accent)}
        .cover-left h1{font-size:26px;font-weight:700;color:var(--primary);margin:6px 0}
        .cover-left p{color:var(--muted);font-size:13px}
        .cover-right{text-align:right;font-size:12px;color:var(--muted)}
        .cover-right .version{display:inline-block;background:var(--light);border:1px solid var(--border);color:var(--primary);padding:3px 10px;border-radius:4px;font-size:11px;font-weight:600;margin-bottom:6px}
        h2{font-size:15px;font-weight:700;color:var(--primary);margin:30px 0 12px;padding-bottom:6px;border-bottom:1px solid var(--border)}
        h3{font-size:14px;font-weight:600;color:var(--primary);margin:18px 0 8px}
        h4{font-size:13px;font-weight:600;color:var(--primary);margin:12px 0 6px}
        p{margin-bottom:10px;text-align:justify}
        ul,ol{padding-left:20px;margin-bottom:12px}
        li{margin-bottom:5px}
        table{width:100%;border-collapse:collapse;margin:15px 0 25px;font-size:12px;border:1px solid var(--border)}
        thead th{background:var(--light);color:var(--primary);padding:10px 12px;text-align:left;font-weight:600;border-bottom:1px solid var(--border)}
        tbody td{border-bottom:1px solid var(--border);padding:8px 12px;vertical-align:top}
        tbody tr:nth-child(even) td{background:#fafbfc}
        .badge{display:inline-flex;align-items:center;gap:4px;padding:2px 6px;border-radius:4px;font-size:10px;font-weight:500;border:1px solid transparent}
        .badge-green{background:#f0fdf4;color:#166534;border-color:#bbf7d0}
        .badge-blue{background:#eff6ff;color:#1e40af;border-color:#bfdbfe}
        .badge-yellow{background:#fefce8;color:#854d0e;border-color:#fef08a}
        .badge-red{background:#fef2f2;color:#991b1b;border-color:#fecaca}
        .badge-orange{background:#fff7ed;color:#c2410c;border-color:#ffedd5}
        .info-box{background:#eff6ff;border:1px solid #bfdbfe;border-left:3px solid var(--accent);border-radius:4px;padding:12px 16px;margin:12px 0}
        .warning-box{background:#fefce8;border:1px solid #fef08a;border-left:3px solid #eab308;border-radius:4px;padding:12px 16px;margin:12px 0}
        .success-box{background:#f0fdf4;border:1px solid #bbf7d0;border-left:3px solid var(--accent-green);border-radius:4px;padding:12px 16px;margin:12px 0}
        .alert-box{background:#fef2f2;border:1px solid #fecaca;border-left:3px solid #ef4444;border-radius:4px;padding:12px 16px;margin:12px 0}
        .info-box strong,.warning-box strong,.success-box strong,.alert-box strong{display:block;margin-bottom:4px;color:var(--primary)}
        .metrics{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin:15px 0 25px}
        .metric-card{border:1px solid var(--border);border-radius:6px;padding:16px;text-align:center;background:var(--light)}
        .metric-card .number{font-size:24px;font-weight:700;color:var(--primary)}
        .metric-card .label{font-size:11px;color:var(--muted);margin-top:4px}
        .footer{margin-top:40px;padding-top:15px;font-size:11px;color:var(--muted);display:flex;justify-content:space-between;border-top:1px solid var(--border)}
        code{font-family:monospace;background:var(--light);padding:2px 4px;border-radius:3px;font-size:11px;color:#b91c1c;border:1px solid var(--border)}
        @media print{@page{margin:.5cm}body{padding:30px 40px}table,.info-box,.warning-box,.success-box,.metric-card{box-shadow:none;border:1px solid #ccc}}
    </style>
</head>
<body>

<div class="cover">
    <div class="cover-left">
        <img src="../img/logo.jpg" alt="GREEN HARD &amp; SOFT" style="height:60px;border-radius:6px;margin-bottom:20px;box-shadow:0 4px 6px rgba(0,0,0,.1)">
        <h1>Resumo Executivo da Plataforma</h1>
        <p>Documento de visão estratégica para Direção e Gestão Institucional</p>
    </div>
    <div class="cover-right">
        <div class="version">v2.0</div><br>
        <strong>Data:</strong> Abril 2026<br>
        <strong>Classificação:</strong> Uso Interno<br>
        <strong>Autor:</strong> Diosives Crobute
    </div>
</div>

<h2>1. Visão Geral do Projeto</h2>
<p>O <strong>GHS (Green Hard &amp; Soft)</strong> é uma plataforma integrada de gestão académica, financeira e pedagógica desenvolvida em PHP nativo (padrão MVC), concebida para eliminar processos manuais e papéis nas escolas superiores. O ecossistema serve quatro perfis de utilizadores com portais independentes, garante rastreabilidade total de todas as operações e implementa padrões de segurança de nível empresarial.</p>
<p>Na versão 2.0, consolidou-se o sistema formal de Mediação Académica com fluxo de 8 etapas, o motor de alertas de convocatória em tempo real para alunos e professores, e a arquitectura Mobile-First com responsividade universal para todos os portais.</p>

<h2>2. Problema e Solução</h2>
<table>
    <thead><tr><th width="42%">Problema Anterior</th><th width="58%">Solução Implementada na Plataforma GHS</th></tr></thead>
    <tbody>
        <tr><td>Matrículas presenciais com perda de documentos</td><td>Portal de candidatura 100% digital com upload de B.I., Certificados e Comprovativos, validados via <strong>Integrador Visual Documental (PDF.js)</strong>.</td></tr>
        <tr><td>Cálculo manual de médias e progressão de ano</td><td><strong>Motor Académico Autónomo</strong>: determina automaticamente Aprovação (≥12), Recurso (8–11) ou Reprovação (&lt;8).</td></tr>
        <tr><td>Disputas de notas sem processo formal</td><td><strong>Sistema de Mediação Académica</strong> com 8 estados rastreáveis e convocatórias obrigatórias com alertas automáticos.</td></tr>
        <tr><td>Pagamentos sem rastreabilidade ou auditoria</td><td>Sistema de Tesouraria com validação de comprovativos e <strong>Registo Manual de Pagamentos</strong> presenciais com recibo térmico.</td></tr>
        <tr><td>Professores sem ferramentas pedagógicas digitais</td><td>Portal docente com lançamento de notas por slots, registo de sumários digitais, marcação de faltas e resposta a contestações.</td></tr>
        <tr><td>Comunicação escolar descentralizada</td><td>Sistema de Comunicados com <strong>Read Tracking</strong> e notificações automáticas por estado do processo.</td></tr>
    </tbody>
</table>

<h2>3. Portais e Utilizadores</h2>
<table>
    <thead><tr><th>Portal</th><th>Utilizador</th><th>Principais Responsabilidades</th></tr></thead>
    <tbody>
        <tr><td><strong>🛡 Administração</strong></td><td>Diretor / Gestor</td><td>Configuração global, auditoria de logs, gestão de utilizadores, mediação académica, emissão de certificados de mérito.</td></tr>
        <tr><td><strong>📋 Secretaria/Tesouraria</strong></td><td>Administrativos</td><td>Validação de matrículas, aprovação/rejeição de pagamentos, emissão de recibos, gestão de comunicados.</td></tr>
        <tr><td><strong>👨‍🏫 Professor</strong></td><td>Docentes</td><td>Lançamento de notas, registo de sumários e faltas, resposta a contestações, alertas de mediação.</td></tr>
        <tr><td><strong>📚 Estudante</strong></td><td>Alunos</td><td>Consulta de notas, contestação formal de avaliações, horários, materiais, pagamentos, alertas de mediação.</td></tr>
    </tbody>
</table>

<h2>4. Novas Funcionalidades — Versão 2.0</h2>

<h3>4.1 Sistema de Mediação Académica (8 Etapas)</h3>
<p>Fluxo formal e auditável para resolução de disputas de avaliações entre alunos e professores:</p>
<table>
    <thead><tr><th>Etapa</th><th>Actor</th><th>Estado</th></tr></thead>
    <tbody>
        <tr><td>1. Abertura da contestação</td><td>Aluno</td><td><span class="badge badge-blue">Pendente</span></td></tr>
        <tr><td>2. Resposta do docente</td><td>Professor</td><td><span class="badge badge-blue">Respondido / Resolvido</span></td></tr>
        <tr><td>3. Reacção do aluno</td><td>Aluno</td><td><span class="badge badge-yellow">Aceitar ou Contra-argumentar</span></td></tr>
        <tr><td>4. Detecção de Impasse</td><td>Sistema automático</td><td><span class="badge badge-orange">Impasse</span></td></tr>
        <tr><td>5. Escalada para Admin</td><td>Sistema automático</td><td><span class="badge badge-orange">Em Mediação</span></td></tr>
        <tr><td>6. Convocatória formal</td><td>Administração</td><td><span class="badge badge-red">Aguardando Comparecimento</span></td></tr>
        <tr><td>7. Reunião presencial</td><td>Todas as partes</td><td><span class="badge badge-red">Aguardando Comparecimento</span></td></tr>
        <tr><td>8. Decisão final</td><td>Administração</td><td><span class="badge badge-green">Encerrado / Aguardando Correcção</span></td></tr>
    </tbody>
</table>

<h3>4.2 Alertas de Convocatória em Tempo Real</h3>
<p>Quando a Administração agenda uma reunião de mediação, tanto o professor como o aluno recebem um <strong>alerta visual vermelho</strong> no topo do seu portal com data, hora, local e motivo da convocatória. A presença é marcada como obrigatória e o alerta é gerado independentemente de o aluno ter ou não notas lançadas.</p>

<h3>4.3 Motor de Progressão Académica</h3>
<table>
    <thead><tr><th>Cenário</th><th>Condição</th><th>Resultado</th></tr></thead>
    <tbody>
        <tr><td>Aprovação Directa</td><td>Média Final ≥ 12 valores</td><td><span class="badge badge-green">Aprovado ✓</span></td></tr>
        <tr><td>Exame de Recurso</td><td>Média entre 8 e 11,9</td><td><span class="badge badge-yellow">Recurso ⚠</span></td></tr>
        <tr><td>Reprovação</td><td>Nota AC &lt; 8 ou Média Final &lt; 8</td><td><span class="badge badge-red">Reprovado ✗</span></td></tr>
    </tbody>
</table>

<h3>4.4 Sistema de Mérito Académico</h3>
<p>Rankings automáticos por nível e escola. A Administração emite Certificados de Mérito para o 1.º e 2.º lugar de cada semestre, visíveis no portal do aluno. Algoritmo: <code>Média = (AC1+AC2+AC3+AC4+Exame) / 2</code> por disciplina.</p>

<h3>4.5 Responsividade Mobile-First Universal</h3>
<p>Todos os portais (Admin, Professor, Aluno) são totalmente responsivos em smartphone, tablet e desktop de alta resolução, implementados através de <code>responsive_global.css</code> com arquitectura Mobile-First.</p>

<h3>4.6 Segurança de Nível Empresarial</h3>
<ul>
    <li><strong>CSRF:</strong> Token único por sessão em todos os formulários e chamadas AJAX</li>
    <li><strong>XSS:</strong> Sanitização sistemática de todos os inputs/outputs dinâmicos</li>
    <li><strong>SQLi:</strong> PDO Prepared Statements em 100% das consultas</li>
    <li><strong>IDOR:</strong> Verificação de propriedade antes de servir qualquer recurso sensível</li>
    <li><strong>Auditoria:</strong> Log inviolável de todas as acções críticas com utilizador, IP e timestamp</li>
</ul>

<div class="alert-box">
    <strong>🔒 Nota de Segurança Institucional:</strong> As credenciais de Administrador não devem ser partilhadas. Todas as acções efectuadas sob a conta administrativa ficam registadas num log inviolável, servindo como prova legal em caso de auditoria.
</div>

<h2>5. Ficha Técnica</h2>
<table>
    <tbody>
        <tr><td width="30%"><strong>Plataforma</strong></td><td>PHP 8.2 Nativo — Padrão MVC sem frameworks</td></tr>
        <tr><td><strong>Domínio de Produção</strong></td><td><a href="https://escola-ghs.wuaze.com" style="color:var(--accent);text-decoration:none">https://escola-ghs.wuaze.com</a></td></tr>
        <tr><td><strong>Servidor</strong></td><td>Apache 2.4+ com mod_rewrite (InfinityFree / XAMPP local)</td></tr>
        <tr><td><strong>Base de Dados</strong></td><td>MariaDB 10.4+ / MySQL 8.0 via PDO</td></tr>
        <tr><td><strong>Interface</strong></td><td>Bootstrap 5, IonIcons, DataTables, FullCalendar</td></tr>
        <tr><td><strong>Segurança</strong></td><td>CSRF Tokens, XSS Sanitization, IDOR Guards, finfo Upload Validation</td></tr>
        <tr><td><strong>Desenvolvedor</strong></td><td>Diosives Crobute / Waro Campotcho</td></tr>
        <tr><td><strong>Versão Actual</strong></td><td>2.0 — Abril 2026</td></tr>
    </tbody>
</table>

<div class="footer">
    <span>&copy; 2026 Green Hard &amp; Soft — Escola Superior de Informática. Documento de Uso Interno.</span>
    <span>Resumo Executivo v2.0</span>
</div>

</body>
</html>
