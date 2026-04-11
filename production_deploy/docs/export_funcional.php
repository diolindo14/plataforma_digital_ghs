<?php // Manual do Utilizador GHS v2.0 ?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>GHS — Manual do Utilizador v2.0</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root{--primary:#111827;--accent:#2563eb;--accent-green:#059669;--light:#f9fafb;--border:#e2e8f0;--text:#374151;--muted:#6b7280}
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Inter',sans-serif;color:var(--text);background:#fff;padding:40px 60px;line-height:1.6;font-size:13px}
        .cover{display:flex;align-items:flex-start;justify-content:space-between;border-bottom:2px solid var(--border);padding-bottom:25px;margin-bottom:35px}
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
        .steps{counter-reset:step;list-style:none;padding:0}
        .steps li{counter-increment:step;display:flex;gap:12px;align-items:flex-start;margin-bottom:12px}
        .steps li::before{content:counter(step);display:flex;align-items:center;justify-content:center;width:22px;height:22px;min-width:22px;background:var(--light);color:var(--primary);border:1px solid var(--border);border-radius:50%;font-weight:600;font-size:11px;margin-top:2px}
        .steps li strong{color:var(--primary);display:block}
        code{font-family:monospace;background:var(--light);padding:2px 4px;border-radius:3px;font-size:11px;color:#b91c1c;border:1px solid var(--border)}
        .footer{margin-top:40px;padding-top:15px;font-size:11px;color:var(--muted);display:flex;justify-content:space-between;border-top:1px solid var(--border)}
        @media print{@page{margin:.5cm}body{padding:30px 40px}table,.info-box,.warning-box,.success-box,.alert-box{box-shadow:none;border:1px solid #ccc}}
    </style>
</head>
<body>

<div class="cover">
    <div class="cover-left">
        <img src="../img/logo.jpg" alt="GHS" style="height:60px;border-radius:6px;margin-bottom:20px;box-shadow:0 4px 6px rgba(0,0,0,.1)">
        <h1>Manual do Utilizador</h1>
        <p>Guia completo de utilização por perfil — Aluno, Professor e Administração</p>
    </div>
    <div class="cover-right">
        <div class="version">v2.0</div><br>
        <strong>Data:</strong> Abril 2026<br>
        <strong>Classificação:</strong> Uso Interno<br>
        <strong>Autor:</strong> Diosives Crobute
    </div>
</div>

<h2>1. Acesso ao Sistema</h2>
<h3>1.1 Como fazer Login</h3>
<ol class="steps">
    <li><strong>Aceda ao endereço do site GHS</strong> no seu browser.</li>
    <li><strong>Introduza o email institucional</strong> e a sua password.</li>
    <li><strong>Clique em "Entrar"</strong> para aceder ao seu portal.</li>
</ol>

<div style="text-align:center; margin: 20px 0;">
    <img src="../img/login_screen.png" alt="Ecrã de Login" style="max-width:80%; border:1px solid var(--border); border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.1)">
    <p style="font-size:11px; color:var(--muted); margin-top:8px;">Imagem 1: Interface de Acesso Unificado GHS</p>
</div>

<h3>1.2 Esqueceu a password?</h3>
<ol class="steps">
    <li>Clique em <strong>"Esqueceu a password?"</strong> no ecrã de login.</li>
    <li>Introduza o seu email institucional.</li>
    <li>Verifique a caixa de correio e siga as instruções recebidas.</li>
</ol>

<h3>1.3 Primeiro acesso — Aluno recém aprovado</h3>
<p>Após a conta ser aprovada pelo administrador:</p>
<ul>
    <li>Receberá um email de confirmação.</li>
    <li>Faça login com as suas credenciais.</li>
    <li><strong>Atenção:</strong> Tem <strong>48 horas</strong> para completar a matrícula — caso contrário a conta é eliminada automaticamente conforme a política institucional.</li>
</ul>
<div class="warning-box"><strong>⚠ Prazo de Matrícula:</strong> Após a aprovação da conta, o aluno tem exactamente 48 horas para submeter a matrícula. Este prazo é aplicado automaticamente pelo sistema.</div>

<h3>1.4 Cadastro e Inscrição Online (Candidatos)</h3>
<p>Se ainda não é aluno da GHS, deve realizar o seu cadastro inicial através da <strong>Inscrição Online</strong>:</p>
<ol class="steps">
    <li>No ecrã inicial, clique em <strong>"Fazer Matrícula"</strong>.</li>
    <li>Preencha os <strong>Dados Pessoais</strong> (Nome, BI, Email, Telefone).</li>
    <li>Indique os <strong>Dados Académicos</strong> e o turno pretendido.</li>
    <li>Faça o <strong>Upload de Documentos</strong> (BI, Fotos, Certificado e Comprovativo).</li>
    <li>Leia e aceite os termos de compromisso e clique em <strong>"Finalizar"</strong>.</li>
</ol>
<div class="info-box"><strong>ℹ Nota Importante:</strong> Após a submissão, a administração irá validar os seus dados. Receberá um e-mail de confirmação ou rejeição em até 48h úteis.</div>

<h2>2. Portal do Aluno / Estudante</h2>

<h3>2.1 Dashboard Principal</h3>
<p>Ao entrar, verá imediatamente:</p>
<ul>
    <li><strong>Alertas activos</strong> no topo (convocatórias de mediação, avisos urgentes)</li>
    <li><strong>Resumo de desempenho:</strong> média geral, total de faltas, estado financeiro</li>
    <li><strong>Próximas aulas:</strong> horário do dia</li>
    <li><strong>Certificados de Mérito</strong> (se aplicável)</li>
</ul>

<div class="alert-box">
    <strong>📢 Alerta de Convocatória de Mediação:</strong> Se aparecer um bloco vermelho no topo do Dashboard intitulado "Convocatória de Mediação Presencial", a sua presença na reunião é OBRIGATÓRIA. Anote cuidadosamente a data, hora e local indicados.
</div>

<h3>2.2 Consulta de Notas</h3>
<ul>
    <li>Aceda ao separador <strong>Notas / Avaliação</strong></li>
    <li>Veja as notas por disciplina: TPC, Actividades Práticas (AP), TPI, Comportamento/Escrita (CE) e Exame</li>
    <li>Veja o <strong>Total AC</strong> (soma dos 4 tipos de avaliação contínua) e a <strong>Nota Final</strong></li>
    <li>Clique em <strong>"Confirmar"</strong> se concordar com o resultado</li>
    <li>Clique em <strong>"Contestar"</strong> para iniciar um processo formal de revisão</li>
</ul>

<h3>2.3 Processo de Contestação de Nota</h3>
<p>Se discordar de uma nota:</p>
<ol class="steps">
    <li><strong>Clique em "Contestar"</strong> na disciplina em causa.</li>
    <li><strong>Escreva a sua justificativa</strong> detalhada (obrigatória).</li>
    <li>O professor é <strong>notificado automaticamente</strong>.</li>
    <li>Aguarde a resposta do professor no portal.</li>
    <li>Pode <strong>aceitar a resposta</strong> (encerra o processo) ou apresentar uma <strong>contra-argumentação</strong> (apenas 1 vez permitida).</li>
    <li>Em caso de impasse, a <strong>Administração é notificada automaticamente</strong> e convocará uma reunião presencial.</li>
</ol>

<p><strong>Estados possíveis da sua contestação:</strong></p>
<table>
    <thead><tr><th>Estado</th><th>Significado</th><th>Próxima Acção</th></tr></thead>
    <tbody>
        <tr><td><span class="badge badge-blue">Pendente</span></td><td>A aguardar resposta do professor</td><td>Aguardar</td></tr>
        <tr><td><span class="badge badge-blue">Respondido</span></td><td>Professor respondeu</td><td>Aceitar ou Contra-argumentar</td></tr>
        <tr><td><span class="badge badge-green">Resolvido</span></td><td>Professor actualizou a nota</td><td>Confirmar ou Contestar de novo</td></tr>
        <tr><td><span class="badge badge-orange">Impasse</span></td><td>Contra-argumentação enviada</td><td>Aguardar convocatória da Admin</td></tr>
        <tr><td><span class="badge badge-red">Aguardando Comparecimento</span></td><td>Reunião agendada</td><td>Comparecer na data e hora indicadas</td></tr>
        <tr><td><span class="badge badge-green">Encerrado</span></td><td>Processo concluído</td><td>—</td></tr>
    </tbody>
</table>

<h3>2.4 Horário Escolar</h3>
<p>Consulte o horário semanal completo no separador <strong>Horário</strong>. Veja as disciplinas, salas, horários e professores de todas as aulas da sua turma.</p>

<h3>2.5 Materiais de Estudo</h3>
<p>Aceda ao separador <strong>Materiais</strong> para descarregar ficheiros partilhados pelos professores (PDF, Word, PPT, ZIP — máx. 20MB).</p>

<h3>2.6 Financeiro</h3>
<p>Consulte o estado das propinas, veja o histórico de pagamentos efectuados e verifique se existem mensalidades em atraso.</p>

<h3>2.7 Comunicados</h3>
<p>Veja os avisos institucionais no separador <strong>Comunicados</strong>. Mensagens não lidas aparecem com um indicador de notificação no menu lateral.</p>

<h3>2.8 Certificados de Mérito</h3>
<p>Se estiver no Top 2 da sua turma no semestre, o seu <strong>Certificado de Mérito</strong> aparecerá automaticamente no Dashboard com a posição alcançada, a média e o ano lectivo.</p>

<h2>3. Portal do Professor</h2>

<h3>3.1 Dashboard Docente</h3>
<p>Ao entrar, verá:</p>
<ul>
    <li>Alertas activos (convocatórias de mediação)</li>
    <li>Turmas e disciplinas atribuídas</li>
    <li>Horário de aulas do dia actual</li>
    <li>Avisos e comunicados pendentes</li>
</ul>

<div class="alert-box">
    <strong>📢 Alerta de Convocatória de Mediação:</strong> Se aparecer um bloco vermelho no Dashboard, existe uma reunião de mediação agendada. A comparência é OBRIGATÓRIA. Veja todos os detalhes no separador "Reclamações de Notas".
</div>

<h3>3.2 Lançamento de Notas</h3>
<ol class="steps">
    <li>Aceda ao separador <strong>Lançamento de Notas</strong>.</li>
    <li>Seleccione a <strong>Turma</strong> e a <strong>Disciplina</strong>.</li>
    <li>Preencha os slots de cada tipo de avaliação (até 3 slots por tipo de AC).</li>
    <li>Clique em <strong>"Guardar"</strong> para registar no sistema.</li>
</ol>
<div class="info-box"><strong>ℹ Notas Bloqueadas:</strong> Se o aluno já concordou com a nota ou o processo foi resolvido por mediação, os campos ficam bloqueados para proteger a integridade académica.</div>

<h3>3.3 Resposta a Contestações</h3>
<ol class="steps">
    <li>Aceda ao separador <strong>Reclamações de Notas</strong>.</li>
    <li>Veja a justificativa do aluno e a nota em causa.</li>
    <li>Escreva a sua resposta explicando o critério de avaliação.</li>
    <li>Indique se <strong>alterou</strong> ou <strong>não alterou</strong> a nota.</li>
</ol>

<h3>3.4 Frequência / Chamada</h3>
<ol class="steps">
    <li>Aceda ao separador <strong>Frequência / Chamada</strong>.</li>
    <li>Seleccione a turma e a data de aula.</li>
    <li>Marque <strong>P</strong> (Presente) ou <strong>F</strong> (Falta) para cada aluno.</li>
    <li>Guarde o registo de sumário da aula.</li>
</ol>

<h3>3.5 Upload de Materiais</h3>
<ol class="steps">
    <li>Separador <strong>Upload de Materiais</strong>.</li>
    <li>Seleccione turma e disciplina.</li>
    <li>Dê um título ao ficheiro e faça o upload.</li>
    <li>Formatos aceites: PDF, PPT, DOC, ZIP, Imagens (máx. 20MB).</li>
</ol>

<h3>3.6 Calendário Académico</h3>
<p>Visualize o calendário de eventos e adicione actividades para a sua turma: Exames, Entregas de Trabalho, Aulas Extra ou outros eventos.</p>

<h2>4. Painel Administrativo</h2>

<h3>4.1 Gestão de Utilizadores</h3>
<ul>
    <li><strong>Aprovar / rejeitar</strong> contas pendentes de alunos recém-registados</li>
    <li>Criar contas de professores e secretárias manualmente</li>
    <li>Suspender ou reactivar contas existentes</li>
</ul>

<h3>4.2 Gestão de Matrículas</h3>
<ul>
    <li>Aprovar matrículas submetidas pelos alunos (ativa automaticamente a conta e tenta alocação em turma).</li>
    <li><strong>Rejeitar Matrícula:</strong> Se houver erro nos dados ou documentos, clique em "Rejeitar".</li>
    <li>Introduza o motivo da rejeição (o aluno recebe um e-mail automático com esta explicação).</li>
    <li><strong>Nota:</strong> A conta do aluno permanece ativa para que este possa corrigir a submissão sem ter de se registar novamente.</li>
    <li>Processar renovações de ano lectivo.</li>
</ul>

<h3>4.3 Gestão de Turmas e Horários</h3>
<ul>
    <li>Criar e gerir turmas por nível e turno (Manhã, Tarde, Noite)</li>
    <li>Atribuir professores a disciplinas e turmas específicas</li>
    <li>Gerir grade horária completa</li>
</ul>

<h3>4.4 Mediação Académica — Workflow</h3>
<ol class="steps">
    <li>Aceda a <strong>Mediações Académicas</strong> no painel.</li>
    <li>Veja os processos em estado "Impasse" ou "Em Mediação".</li>
    <li>Clique em <strong>"Convocar Partes"</strong> para agendar a reunião.</li>
    <li>Preencha: <strong>data, hora, local e motivo</strong> da convocatória.</li>
    <li>Aluno e professor recebem <strong>notificação automática</strong> com todos os detalhes.</li>
    <li>Após a reunião, registe a <strong>Decisão Final</strong>: encerrar ou ordenar correcção de nota.</li>
</ol>

<h3>4.5 Certificados de Mérito</h3>
<ol class="steps">
    <li>Aceda a <strong>Méritos / Certificados</strong>.</li>
    <li>Seleccione o semestre e ano lectivo.</li>
    <li>Veja os top alunos elegíveis calculados automaticamente.</li>
    <li>Seleccione os alunos e emita os certificados oficiais.</li>
</ol>

<h3>4.6 Comunicados e Alertas</h3>
<ul>
    <li>Envie comunicados para turmas específicas, todos os professores ou toda a escola</li>
    <li>Configure prioridade: Normal, Alta ou Urgente</li>
    <li>O sistema regista quem leu cada comunicado (<em>Read Tracking</em>)</li>
</ul>

<h2>5. Dicas Importantes</h2>
<div class="success-box"><strong>✅ Boa Prática:</strong> Faça sempre logout ao terminar a sessão, especialmente em computadores partilhados.</div>
<div class="warning-box"><strong>⚠ Contestações:</strong> Cada disciplina suporta apenas 1 contra-argumentação por período. Use este recurso com responsabilidade e fundamento claro.</div>
<div class="info-box"><strong>ℹ Cache do Browser:</strong> Se o site não actualizar após uma mudança administrativa, faça Ctrl+F5 (actualização forçada do browser).</div>

<div class="footer">
    <span>&copy; 2026 Green Hard &amp; Soft — Escola Superior de Informática. Documento de Uso Interno.</span>
    <span>Manual do Utilizador v2.0</span>
</div>

</body>
</html>
