<?php // Manual Técnico GHS v2.0 ?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>GHS — Manual Técnico v2.0</title>
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
        .info-box{background:#eff6ff;border:1px solid #bfdbfe;border-left:3px solid var(--accent);border-radius:4px;padding:12px 16px;margin:12px 0}
        .warning-box{background:#fefce8;border:1px solid #fef08a;border-left:3px solid #eab308;border-radius:4px;padding:12px 16px;margin:12px 0}
        .success-box{background:#f0fdf4;border:1px solid #bbf7d0;border-left:3px solid var(--accent-green);border-radius:4px;padding:12px 16px;margin:12px 0}
        .alert-box{background:#fef2f2;border:1px solid #fecaca;border-left:3px solid #ef4444;border-radius:4px;padding:12px 16px;margin:12px 0}
        .info-box strong,.warning-box strong,.success-box strong,.alert-box strong{display:block;margin-bottom:4px;color:var(--primary)}
        code{font-family:'JetBrains Mono',monospace;background:var(--light);padding:2px 4px;border-radius:3px;font-size:11px;color:#b91c1c;border:1px solid var(--border)}
        pre{font-family:'JetBrains Mono',monospace;background:var(--light);color:var(--primary);padding:12px;border-radius:4px;font-size:11px;line-height:1.4;margin:12px 0;overflow-x:auto;border:1px solid var(--border)}
        pre .comment{color:var(--muted)}
        pre .key{color:var(--accent)}
        pre .val{color:var(--accent-green)}
        .footer{margin-top:40px;padding-top:15px;font-size:11px;color:var(--muted);display:flex;justify-content:space-between;border-top:1px solid var(--border)}
        @media print{@page{margin:.5cm}body{padding:30px 40px}table,pre,.info-box,.warning-box,.success-box,.alert-box{box-shadow:none;border:1px solid #ccc}}
    </style>
</head>
<body>

<div class="cover">
    <div class="cover-left">
        <img src="../img/logo.jpg" alt="GHS" style="height:60px;border-radius:6px;margin-bottom:20px;box-shadow:0 4px 6px rgba(0,0,0,.1)">
        <h1>Manual Técnico — Developer Guide</h1>
        <p>Documentação de arquitectura, modelos e segurança para equipas de desenvolvimento</p>
    </div>
    <div class="cover-right">
        <div class="version">v2.0</div><br>
        <strong>Data:</strong> Abril 2026<br>
        <strong>Classificação:</strong> Uso Interno (Dev)<br>
        <strong>Autor:</strong> Diosives Crobute
    </div>
</div>

<h2>1. Stack Tecnológico</h2>
<table>
    <tbody>
        <tr><td width="28%"><strong>Backend</strong></td><td>PHP 8.2 Nativo — Padrão MVC sem frameworks</td></tr>
        <tr><td><strong>Base de Dados</strong></td><td>MariaDB 10.4+ / MySQL 8.0 via PDO com Prepared Statements</td></tr>
        <tr><td><strong>Frontend</strong></td><td>Bootstrap 5.3, IonIcons 7, DataTables, FullCalendar 6</td></tr>
        <tr><td><strong>Servidor</strong></td><td>Apache 2.4+ com <code>mod_rewrite</code> (InfinityFree Cloud / XAMPP local)</td></tr>
        <tr><td><strong>Deploy Produção</strong></td><td><a href="https://escola-ghs.wuaze.com" style="color:var(--accent);text-decoration:none">https://escola-ghs.wuaze.com</a></td></tr>
    </tbody>
</table>

<h2>2. Estrutura de Pastas</h2>
<pre>
<span class="comment"># Raiz do projecto: /green/</span>
app/
  controllers/     <span class="comment"># AdminController, EstudanteController, ProfessorController, AuthController</span>
  models/          <span class="comment"># Academico, Contestacao, Matricula, Nota, Financeiro, Comunicado…</span>
  views/
    admin/         <span class="comment"># dashboard.php</span>
    estudante/     <span class="comment"># dashboard.php</span>
    professor/     <span class="comment"># dashboard.php</span>
    auth/          <span class="comment"># login.php, register.php</span>
  logs/
    error.log      <span class="comment"># Log centralizado de erros críticos</span>
core/
  config.php       <span class="comment"># URL_ROOT, DB_HOST, DB_NAME, DB_USER, DB_PASS</span>
  Database.php     <span class="comment"># Singleton PDO</span>
  App.php          <span class="comment"># Router / dispatcher</span>
  Controller.php   <span class="comment"># Base Controller (model(), view(), redirect())</span>
  Security.php     <span class="comment"># CSRF, XSS, sanitize</span>
docs/
  export_admin.php      <span class="comment"># Resumo Executivo (este ficheiro)</span>
  export_funcional.php  <span class="comment"># Manual do Utilizador</span>
  export_tecnica.php    <span class="comment"># Manual Técnico</span>
  backups/              <span class="comment"># Dumps SQL</span>
database/
  backups/         <span class="comment"># Backups automáticos em pontos críticos</span>
public/
  css/, js/, img/
production_deploy/ <span class="comment"># Cópia sincronizada para upload FTP em produção</span>
</pre>

<h2>3. Arquitectura MVC</h2>
<h3>3.1 Routing</h3>
<p>O <code>App.php</code> lê a URL e instancia o controlador correcto. Exemplo:</p>
<pre>
<span class="comment"># URL: /green/estudante/dashboard</span>
<span class="key">$controller</span> = <span class="val">'EstudanteController'</span>;
<span class="key">$method</span>     = <span class="val">'dashboard'</span>;
</pre>

<h3>3.2 Controladores Principais</h3>
<table>
    <thead><tr><th>Controlador</th><th>Responsabilidade</th></tr></thead>
    <tbody>
        <tr><td><code>AuthController</code></td><td>Login, logout, registo, recuperação de password, 2FA</td></tr>
        <tr><td><code>AdminController</code></td><td>Gestão de utilizadores, matrículas, turmas, mediação, certificados</td></tr>
        <tr><td><code>EstudanteController</code></td><td>Dashboard do aluno, notas, contestação, materiais, horários, convocatórias</td></tr>
        <tr><td><code>ProfessorController</code></td><td>Dashboard do docente, lançamento de notas, frequência, materiais, contestações</td></tr>
    </tbody>
</table>

<h2>4. Modelos Críticos</h2>

<h3>4.1 Contestacao.php — Máquina de Estados</h3>
<p>Implementa o fluxo formal de 8 etapas de resolução de disputas de avaliação com estados rastreáveis e transições validadas:</p>
<table>
    <thead><tr><th>Estado</th><th>Actor</th><th>Método</th></tr></thead>
    <tbody>
        <tr><td><span class="badge badge-blue">Pendente</span></td><td>Aluno</td><td><code>abrir()</code></td></tr>
        <tr><td><span class="badge badge-blue">Respondido / Resolvido</span></td><td>Professor</td><td><code>responderDocente()</code></td></tr>
        <tr><td><span class="badge badge-yellow">Impasse</span></td><td>Sistema</td><td><code>reagirAluno()</code></td></tr>
        <tr><td><span class="badge badge-yellow">Em_Mediacao</span></td><td>Sistema automático</td><td><code>_escalarParaAdmin()</code></td></tr>
        <tr><td><span class="badge badge-red">Aguardando_Comparecimento</span></td><td>Admin</td><td><code>convocarPartes()</code></td></tr>
        <tr><td><span class="badge badge-green">Encerrado / Aguardando_Correcao</span></td><td>Admin</td><td><code>registrarDecisao()</code></td></tr>
    </tbody>
</table>

<div class="info-box"><strong>Regra de Negócio:</strong> Apenas 1 contra-argumentação por aluno por processo é permitida. Após o estado "Impasse", o processo só pode ser alterado pela Administração.</div>

<h3>4.2 Academico.php — Motor de Notas</h3>
<p>Responsável pelo cálculo de médias, histórico global e rankings. Método principal: <code>getGradesByStudent($estudante_id)</code>. Fórmula: <code>Nota Final = (AC_total + Exame) / 2</code>.</p>

<h3>4.3 Matricula.php — Progressão Académica</h3>
<p>Avalia automaticamente aprovação, recurso e reprovação com base nas notas finais de todas as disciplinas. Aplica a "Regra das 3 Negativas" para determinar repetição de ano.</p>

<h3>4.4 Comunicado.php — Sistema de Notificações</h3>
<p>Todas as notificações automáticas (contestação, convocatória, aprovação de matrícula) são inseridas na tabela <code>comunicados</code> com destinatário específico. O sistema verifica na carga do dashboard e injeta alertas visuais.</p>

<h2>5. Tabelas da Base de Dados</h2>
<table>
    <thead><tr><th>Tabela</th><th>Descrição</th><th>Campos-Chave</th></tr></thead>
    <tbody>
        <tr><td><code>utilizadores</code></td><td>Todos os utilizadores do sistema</td><td>id, email, senha, tipo, status</td></tr>
        <tr><td><code>estudantes</code></td><td>Perfil estendido do aluno</td><td>id, utilizador_id, bi, telefone</td></tr>
        <tr><td><code>professores</code></td><td>Perfil estendido do professor</td><td>id, utilizador_id</td></tr>
        <tr><td><code>turmas</code></td><td>Turmas activas</td><td>id, codigo, turno, ano_id</td></tr>
        <tr><td><code>matriculas</code></td><td>Matrículas dos alunos</td><td>id, estudante_id, turma_id, status</td></tr>
        <tr><td><code>notas</code></td><td>Notas lançadas por tipo de avaliação</td><td>id, estudante_id, disciplina_id, tipo_id, nota</td></tr>
        <tr><td><code>concordancia_notas</code></td><td>Contestações e mediações</td><td>id, estudante_id, turma_id, disciplina_id, status, data_reuniao, hora_reuniao, local_reuniao, motivo_convocacao</td></tr>
        <tr><td><code>comunicados</code></td><td>Notificações e comunicados</td><td>id, titulo, mensagem, destinatario_id, tipo, prioridade</td></tr>
        <tr><td><code>frequencias</code></td><td>Presenças e faltas</td><td>id, estudante_id, turma_id, data, status</td></tr>
        <tr><td><code>horarios</code></td><td>Grade horária por turma</td><td>id, turma_id, disciplina_id, dia_semana, hora_inicio, hora_fim</td></tr>
    </tbody>
</table>

<h2>6. Segurança Implementada</h2>

<h3>6.1 CSRF Protection</h3>
<pre>
<span class="comment"># Token gerado por sessão (Security.php)</span>
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

<span class="comment"># Validação em todos os POSTs</span>
if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die('CSRF token inválido.');
}
</pre>

<h3>6.2 XSS — Output Sanitization</h3>
<pre>
<span class="comment"># Todas as saídas dinâmicas usam:</span>
echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
<span class="comment"># Ou na view com shorthand:</span>
&lt;?= htmlspecialchars($n['nome']) ?&gt;
</pre>

<h3>6.3 SQL Injection — PDO Prepared Statements</h3>
<pre>
<span class="comment"># 100% das queries usam bind parameters</span>
$stmt = $this->db->prepare("SELECT * FROM utilizadores WHERE email = :email");
$stmt->execute([':email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
</pre>

<h3>6.4 Upload Security</h3>
<ul>
    <li>Validação dupla: extensão + MIME real via <code>finfo_file()</code></li>
    <li>Limite de tamanho: 20MB máximo</li>
    <li>Ficheiros renomeados com hash para evitar execução remota</li>
    <li>Pasta de upload fora da raiz pública quando possível</li>
</ul>

<h3>6.5 Session Security</h3>
<pre>
<span class="comment"># Configuração em config.php</span>
session_set_cookie_params(['httponly' => true, 'samesite' => 'Strict']);
session_start();
<span class="comment"># Regeneração do ID após login</span>
session_regenerate_id(true);
</pre>

<h2>7. Deploy e Sincronização para Produção</h2>
<h3>7.1 Configuração do Ambiente</h3>
<p>Edite <code>core/config.php</code> com as credenciais do servidor de produção antes do deploy:</p>
<pre>
<span class="key">define</span>(<span class="val">'URL_ROOT'</span>, <span class="val">'https://escola-ghs.wuaze.com/green'</span>);
<span class="key">define</span>(<span class="val">'DB_HOST'</span>,  <span class="val">'sql.infinityfree.com'</span>);
<span class="key">define</span>(<span class="val">'DB_NAME'</span>,  <span class="val">'epiz_xxxxxxx_ghsespf'</span>);
<span class="key">define</span>(<span class="val">'DB_USER'</span>,  <span class="val">'epiz_xxxxxxx'</span>);
<span class="key">define</span>(<span class="val">'DB_PASS'</span>,  <span class="val">'*******'</span>);
</pre>

<h3>7.2 Processo de Deploy</h3>
<ol>
    <li>Copiar todos os ficheiros para <code>production_deploy/</code></li>
    <li>Fazer upload via FTP (FileZilla) para o servidor InfinityFree</li>
    <li>Importar o dump SQL via phpMyAdmin do cPanel</li>
    <li>Verificar permissões: <code>uploads/</code> deve ser gravável (<code>chmod 755</code>)</li>
    <li>Testar login e funcionalidades críticas em produção</li>
</ol>

<div class="warning-box"><strong>⚠ Antes do Deploy:</strong> Verifique se <code>core/config.php</code> está com as credenciais correctas de produção. Nunca faça commit das credenciais reais para repositórios públicos.</div>

<h2>8. Alertas de Convocatória — Implementação Técnica</h2>
<p>O sistema de alertas de mediação funciona da seguinte forma:</p>
<ol>
    <li>Admin chama <code>Contestacao::convocarPartes()</code> que actualiza o status para <code>Aguardando_Comparecimento</code> e preenche <code>data_reuniao</code>, <code>hora_reuniao</code>, <code>local_reuniao</code>, <code>motivo_convocacao</code> na tabela <code>concordancia_notas</code>.</li>
    <li><code>EstudanteController::index()</code> executa uma query directa: <code>SELECT ... WHERE status = 'Aguardando_Comparecimento' AND estudante_id = :eid</code> e injeta em <code>$data['convocatorias']</code>.</li>
    <li><code>ProfessorController::index()</code> usa <code>Contestacao::getPendentesDocente()</code> que inclui <code>Aguardando_Comparecimento</code> no filtro, injectando em <code>$data['contestacoes_pendentes']</code>.</li>
    <li>Ambos os dashboards renderizam o bloco <code>.alert.alert-danger</code> condicionalmente no topo do pane-home.</li>
</ol>

<h2>9. Logs e Monitorização</h2>
<p>Todos os erros críticos são registados em <code>app/logs/error.log</code> no formato:</p>
<pre>
[<span class="key">2026-04-11 04:30:09</span>] CRITICAL ERROR: <span class="val">mensagem_do_erro</span> em <span class="comment">ficheiro.php</span> na linha <span class="key">443</span>
</pre>
<p>Para monitorizar em tempo real, use o cPanel → File Manager → error.log, ou aceda ao log de erros do Apache no XAMPP.</p>

<div class="alert-box">
    <strong>🔒 Segurança do Log:</strong> O ficheiro <code>app/logs/error.log</code> está protegido por <code>.htaccess</code> e não é acessível publicamente. Nunca exponha logs em ambientes de produção.
</div>

<h2>10. Manutenção e Backups</h2>
<ul>
    <li>Backups automáticos em pontos críticos guardados em <code>database/backups/</code></li>
    <li>Para dump manual: <code>mysqldump -u root ghsespf_db > backup_$(date +%Y%m%d).sql</code></li>
    <li>Em produção, usar a ferramenta de Export do phpMyAdmin no cPanel</li>
</ul>

<div class="footer">
    <span>&copy; 2026 Green Hard &amp; Soft — Escola Superior de Informática. Documento de Uso Interno (Dev).</span>
    <span>Manual Técnico v2.0</span>
</div>

</body>
</html>
