<?php
// Template de Exportação Profissional GHS - Técnica e Segurança
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Documentação Técnica e Segurança - GHS</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #0f172a; --accent: #ef4444; }
        body { font-family: 'Outfit', sans-serif; line-height: 1.6; color: #334155; margin: 0; padding: 40px; }
        .header { border-bottom: 4px solid var(--primary); padding-bottom: 20px; margin-bottom: 40px; }
        .logo { font-weight: 700; font-size: 24px; color: var(--primary); }
        h1 { font-size: 32px; color: var(--primary); margin-top: 0; }
        h2 { font-size: 20px; color: var(--accent); border-left: 4px solid var(--accent); padding-left: 15px; margin-top: 30px; }
        h3 { font-size: 18px; color: #1e293b; margin-top: 25px; }
        code { background: #f1f5f9; padding: 2px 5px; border-radius: 4px; font-family: monospace; }
        .footer { margin-top: 50px; font-size: 12px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px; }
        @media print { body { padding: 0; } .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="background:#fef9c3; padding:10px; border:1px solid #fde047; border-radius:8px; margin-bottom:20px; text-align:center;">
        Pressione <b>Ctrl + P</b> e selecione "Guardar como PDF" para gerar o documento técnico oficial.
    </div>

    <div class="header">
        <div class="logo">GREEN HARD & SOFTH</div>
        <h1>Especificação Técnica e Segurança v4.0</h1>
        <p>Infraestrutura e Hardening da Plataforma</p>
    </div>

    <h2>1. Arquitetura de Software</h2>
    <p>O sistema segue o padrão <strong>MVC (Model-View-Controller)</strong> puro, garantindo separação total entre lógica de dados, controlo de fluxo e interface gráfica.</p>

    <h3>1.1 Requisitos de Sistema</h3>
    <ul>
        <li>Servidor Web: Apache 2.4+</li>
        <li>Runtime: PHP 8.2 (com extensões PDO, GD, finfo, session)</li>
        <li>Base de Dados: MariaDB 10.4+ / MySQL 8.0</li>
    </ul>

    <h2>2. Camadas de Segurança (Security Hardening)</h2>
    
    <h3>2.1 Mitigação de Vulnerabilidades</h3>
    <ul>
        <li><strong>Cross-Site Request Forgery (CSRF)</strong>: Implementação de tokens criptográficos obrigatórios em todos os POSTs.</li>
        <li><strong>SQL Injection</strong>: Abstração completa de queries via PDO Prepared Statements em todos os Modelos (e.g., <code>Matricula.php</code>, <code>Estudante.php</code>).</li>
        <li><strong>XSS (Cross-Site Scripting)</strong>: Sanitização sistemática de outputs dinâmicos no portal administrativo e aluno.</li>
        <li><strong>IDOR (Insecure Direct Object Reference)</strong>: Verificação de propriedade (ownership) e roles em endpoints sensíveis como <code>downloadRecibo</code>.</li>
    </ul>

    <h3>2.2 Integridade Documental</h3>
    <p>O sistema processivo de uploads utiliza filtragem avançada por <strong>Magic Numbers (finfo)</strong> em vez de confiar na extensão enviada pelo utilizador, prevenindo a execução de ficheiros maliciosos disfarçados de imagens ou PDFs.</p>

    <h2>3. Auditoria e Logs</h2>
    <p>Todas as ações administrativas relevantes (Criar Disciplina, Aprovar Matrícula, Alterar Password) são registadas na base de dados com o ID do utilizador, endereço IP e descrição detalhada da operação.</p>

    <div class="footer">
        &copy; 2026 Green Hard & Softh - Escola Superior de Informática. Segurança de Nível Profissional. <strong> By Diosives Crobute</strong>
    </div>
</body>
</html>
