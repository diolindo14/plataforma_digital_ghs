<?php
// Template de Exportação Profissional GHS
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Documentação Funcional - GHS Educational Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #0f172a; --accent: #3b82f6; }
        body { font-family: 'Outfit', sans-serif; line-height: 1.6; color: #334155; margin: 0; padding: 40px; }
        .header { border-bottom: 4px solid var(--primary); padding-bottom: 20px; margin-bottom: 40px; }
        .logo { font-weight: 700; font-size: 24px; color: var(--primary); }
        h1 { font-size: 32px; color: var(--primary); margin-top: 0; }
        h2 { font-size: 20px; color: var(--accent); border-left: 4px solid var(--accent); padding-left: 15px; margin-top: 30px; }
        h3 { font-size: 18px; color: #1e293b; margin-top: 25px; }
        p, li { font-size: 15px; text-align: justify; }
        .footer { margin-top: 50px; font-size: 12px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px; }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="background:#fef9c3; padding:10px; border:1px solid #fde047; border-radius:8px; margin-bottom:20px; text-align:center;">
        Pressione <b>Ctrl + P</b> e selecione "Guardar como PDF" para gerar o documento oficial.
    </div>

    <div class="header">
        <div class="logo">GREEN HARD & SOFTH</div>
        <h1>Especificação Funcional v4.0</h1>
        <p>Plataforma Integrada de Gestão de Académica</p>
    </div>

    <h2>1. Arquitetura Funcional</h2>
    <p>O ecossistema GHS foi concebido para automatizar a jornada do estudante e a operacionalização administrativa de instituições de ensino superior e técnico.</p>

    <h3>1.1 Portais Dedicados</h3>
    <ul>
        <li><strong>Portal Administrativo</strong>: Gestão estratégica e auditoria de sistemas.</li>
        <li><strong>Portal da Secretaria/Tesouraria</strong>: Validação documental e conformidade financeira.</li>
        <li><strong>Portal do Professor</strong>: Gestão de salas de aula, sumários e avaliações.</li>
        <li><strong>Portal do Estudante</strong>: Auto-serviço académico e financeiro.</li>
    </ul>

    <h2>2. Processos Críticos</h2>
    
    <h3>2.1 Matrícula e Renovação</h3>
    <p>O processo é 100% digital. O candidato faz o upload dos documentos (B.I., Certificados, Comprovativos). A secretaria visualiza estes ficheiros via "Integrador Visual Documental" e aprova ou rejeita com justificação.</p>

    <h3>2.2 Fluxo Financeiro (XOF)</h3>
    <p>O sistema gere propinas e emolumentos. Após o depósito bancário, o aluno submete o comprovativo. A tesouraria valida o pagamento, alterando o status da dívida e ativando o acesso académico do aluno.</p>

    <h3>2.3 Gestão Académica Dinâmica</h3>
    <p>As faltas e notas são lançadas pelos docentes. O sistema aplica regras complexas: aprovado por trânsito automático, repetição de ano (3 negativas ou insucesso em disciplinas chave) e acesso a exames de recurso.</p>

    <h2>3. Relatórios e Exportação</h2>
    <p>Todos os dados críticos são exportáveis em formato CSV para integração com Excel ou outros softwares de contabilidade, garantindo que a escola nunca perca a posse da sua informação bruta.</p>

    <div class="footer">
        &copy; 2026 Green Hard & Softh - Escola Superior de Informática. Todos os direitos reservados. <strong> By Diosives Crobute</strong>
    </div>
</body>
</html>
