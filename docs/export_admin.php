<?php
// Template de Exportação Profissional GHS - Manual do Administrador
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Manual do Administrador - GHS</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #0f172a; --accent: #10b981; }
        body { font-family: 'Outfit', sans-serif; line-height: 1.6; color: #334155; margin: 0; padding: 40px; }
        .header { border-bottom: 4px solid var(--primary); padding-bottom: 20px; margin-bottom: 40px; }
        .logo { font-weight: 700; font-size: 24px; color: var(--primary); }
        h1 { font-size: 32px; color: var(--primary); margin-top: 0; }
        h2 { font-size: 20px; color: var(--accent); border-left: 4px solid var(--accent); padding-left: 15px; margin-top: 30px; }
        h3 { font-size: 18px; color: #1e293b; margin-top: 25px; }
        p, li { font-size: 15px; text-align: justify; }
        .alert { background: #f0fdf4; border: 1px solid #bbf7d0; padding: 15px; border-radius: 8px; margin-top: 20px; }
        .alert strong { color: #166534; }
        .footer { margin-top: 50px; font-size: 12px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px; }
        @media print { body { padding: 0; } .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="background:#fef9c3; padding:10px; border:1px solid #fde047; border-radius:8px; margin-bottom:20px; text-align:center;">
        Pressione <b>Ctrl + P</b> e selecione "Guardar como PDF" para gerar o manual oficial.
    </div>

    <div class="header">
        <div class="logo">GREEN HARD & SOFTH</div>
        <h1>Manual do Administrador v4.0</h1>
        <p>Guia Operacional do Portal de Gestão Académica</p>
    </div>

    <h2>1. Primeiro Acesso e Dashboard</h2>
    <p>O painel de controlo (Dashboard) oferece uma visão panorâmica e em tempo real da saúde da instituição. Nele, encontrará alertas sobre propinas pendentes, matrículas não validadas e sumários em atraso.</p>

    <h2>2. Gestão de Matrículas (Processo Crítico)</h2>
    <p>As matrículas submetidas online ficam no estado "Pendente". Para aprovar:</p>
    <ol>
        <li>Navegue até o separador "Matrículas".</li>
        <li>Clique no botão com ícone de "olho" ao lado do aluno.</li>
        <li>O <strong>Integrador Visual Documental</strong> abrir-se-á. Utilize os botões (B.I., Certificado) para analisar os documentos em formato digital de alta resolução (PDF.js).</li>
        <li>Estando conforme, clique em "Aprovar Matrícula". O sistema irá ativar a conta do aluno e alocá-lo à turma compatível automaticamente.</li>
    </ol>

    <h2>3. Arquitetura Académica</h2>
    
    <h3>3.1 Criação de Turmas e Disciplinas</h3>
    <p>Antes do início do semestre, é crucial garantir que toda a grelha exista no sistema. Pode criar novas disciplinas e associá-las aos respetivos Anos Curriculares no menu correspondente.</p>

    <h3>3.2 Gestão de Professores</h3>
    <p>Ao recrutar um docente, cadastre-o na plataforma informando o B.I., formação e contactos. No mesmo modal de cadastro, utilize as "Atribuições" para associar o professor a múltiplas Turmas e Disciplinas (ex: Matemática Aplicada para Automação_T1).</p>

    <h2>4. Calendário Escolar e Comunicados</h2>
    <p>Mantenha a comunidade informada:</p>
    <ul>
        <li><strong>Comunicados</strong>: Envie alertas urgentes. Estes irão expirar automaticamente do portal do aluno após 7 dias para manter a interface limpa.</li>
        <li><strong>Calendário Global</strong>: Registe feriados, épocas de exame ou dias letivos extras. Estes eventos ficarão visíveis nos portais de Professores e Alunos de forma dinâmica.</li>
    </ul>

    <div class="alert">
        <strong>Nota de Segurança:</strong> Não partilhe as suas credenciais de Administrador. Todas as ações efetuadas sob a sua conta, incluindo aprovação de matrículas e gestão de pagamentos manuais, ficam registadas num log inviolável.
    </div>

    <div class="footer">
        &copy; 2026 Green Hard & Softh. Manual Operacional Privado.
    </div>
</body>
</html>
