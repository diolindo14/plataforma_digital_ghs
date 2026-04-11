<?php
// README T├®cnico GHS v1.0
?>
<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <meta charset="UTF-8">
    <title>GHS ÔÇö README T├®cnico v1.0</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;600&display=swap"
        rel="stylesheet">
        <style>
        :root { --primary: #111827; --accent: #2563eb; --accent-green: #059669; --light: #f9fafb; --border: #e2e8f0; --text: #374151; --muted: #6b7280; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; color: var(--text); background: #fff; padding: 40px 60px; line-height: 1.6; font-size: 13px; }
        /* CAPA */
        .cover { display: flex; align-items: flex-start; justify-content: space-between; border-bottom: 2px solid var(--border); padding-bottom: 25px; margin-bottom: 35px; }
        .cover-left .logo { font-size: 16px; font-weight: 700; color: var(--primary); letter-spacing: 0.5px; }
        .cover-left .logo span { color: var(--accent); }
        .cover-left h1 { font-size: 26px; font-weight: 700; color: var(--primary); margin: 6px 0; }
        .cover-left p { color: var(--muted); font-size: 13px; }
        .cover-right { text-align: right; font-size: 12px; color: var(--muted); }
        .cover-right .version { display: inline-block; background: var(--light); border: 1px solid var(--border); color: var(--primary); padding: 3px 10px; border-radius: 4px; font-size: 11px; font-weight: 600; margin-bottom: 6px; }
        h2 { font-size: 15px; font-weight: 700; color: var(--primary); margin: 30px 0 12px; padding-bottom: 6px; border-bottom: 1px solid var(--border); }
        h3 { font-size: 14px; font-weight: 600; color: var(--primary); margin: 18px 0 8px; }
        h4 { font-size: 13px; font-weight: 600; color: var(--primary); margin: 12px 0 6px; }
        p { margin-bottom: 10px; text-align: justify; }
        ul, ol { padding-left: 20px; margin-bottom: 12px; }
        li { margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0 25px; font-size: 12px; border: 1px solid var(--border); }
        thead th { background: var(--light); color: var(--primary); padding: 10px 12px; text-align: left; font-weight: 600; border-bottom: 1px solid var(--border); }
        tbody td { border-bottom: 1px solid var(--border); padding: 8px 12px; vertical-align: top; }
        tbody tr:nth-child(even) td { background: #fafbfc; }
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 500; border: 1px solid transparent; }
        .badge-green { background: #f0fdf4; color: #166534; border-color: #bbf7d0; }
        .badge-blue { background: #eff6ff; color: #1e40af; border-color: #bfdbfe; }
        .badge-yellow { background: #fefce8; color: #854d0e; border-color: #fef08a; }
        .badge-red { background: #fef2f2; color: #991b1b; border-color: #fecaca; }
        .badge-orange { background: #fff7ed; color: #c2410c; border-color: #ffedd5; }
        /* CONTAINERS */
        .info-box { background: #eff6ff; border: 1px solid #bfdbfe; border-left: 3px solid var(--accent); border-radius: 4px; padding: 12px 16px; margin: 12px 0; }
        .warning-box { background: #fefce8; border: 1px solid #fef08a; border-left: 3px solid #eab308; border-radius: 4px; padding: 12px 16px; margin: 12px 0; }
        .success-box { background: #f0fdf4; border: 1px solid #bbf7d0; border-left: 3px solid var(--accent-green); border-radius: 4px; padding: 12px 16px; margin: 12px 0; }
        .danger-box { background: #fef2f2; border: 1px solid #fecaca; border-left: 3px solid #ef4444; border-radius: 4px; padding: 12px 16px; margin: 12px 0; }
        .info-box strong, .warning-box strong, .success-box strong, .danger-box strong { display: block; margin-bottom: 4px; color: var(--primary); }
        .steps { counter-reset: step; list-style: none; padding: 0; }
        .steps li { counter-increment: step; display: flex; gap: 12px; align-items: flex-start; margin-bottom: 12px; }
        .steps li::before { content: counter(step); display: flex; align-items: center; justify-content: center; width: 22px; height: 22px; min-width: 22px; background: var(--light); color: var(--primary); border: 1px solid var(--border); border-radius: 50%; font-weight: 600; font-size: 11px; margin-top: 2px; }
        .steps li strong { color: var(--primary); display: block; }
        code { font-family: 'JetBrains Mono', monospace; background: var(--light); padding: 2px 4px; border-radius: 3px; font-size: 11px; color: #b91c1c; border: 1px solid var(--border); }
        pre { font-family: 'JetBrains Mono', monospace; background: var(--light); color: var(--primary); padding: 12px; border-radius: 4px; font-size: 11px; line-height: 1.4; margin: 12px 0; overflow-x: auto; border: 1px solid var(--border); }
        pre .comment { color: var(--muted); }
        pre .key { color: var(--accent); }
        pre .val { color: var(--accent-green); }
        .metrics { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin: 15px 0 25px; }
        .metric-card { border: 1px solid var(--border); border-radius: 6px; padding: 16px; text-align: center; background: var(--light); }
        .metric-card .number { font-size: 24px; font-weight: 700; color: var(--primary); }
        .metric-card .label { font-size: 11px; color: var(--muted); margin-top: 4px; }
        .faq-item { border: 1px solid var(--border); border-radius: 4px; margin-bottom: 8px; overflow: hidden; }
        .faq-q { background: var(--light); padding: 10px 14px; font-weight: 600; color: var(--primary); font-size: 12px; }
        .faq-a { padding: 10px 14px; font-size: 12px; border-top: 1px solid var(--border); }
        .footer { margin-top: 40px; padding-top: 15px; font-size: 11px; color: var(--muted); display: flex; justify-content: space-between; border-top: 1px solid var(--border); }
        @media print {
            @page {
                margin: 0.5cm;
            }

            body { padding: 30px 40px; }
            .cover-bar { display: none; }
            .cover { border-bottom: 2px solid #ccc; padding-bottom: 15px; margin-bottom: 20px; }
            table, pre, .info-box, .warning-box, .success-box, .metric-card { box-shadow: none; border: 1px solid #ccc; }
        }
    </style>
</head>

<body>



    <div class="cover">
        <div class="cover-left">
            <img src="../img/logo.jpg" alt="GREEN HARD &amp; SOFTH" style="height: 60px; border-radius: 6px; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <h1>README T├®cnico da Plataforma</h1>
            <p>Arquitetura, Instala├º├úo, Seguran├ºa e Motores de L├│gica de Neg├│cio</p>
        </div>
        <div class="cover-right">
            <div class="version">v1.3</div><br>
            <strong>Data:</strong> Abril 2026<br>
            <strong>P├║blico-Alvo:</strong> Desenvolvedores / DevOps<br>
            <strong>Autor:</strong> Diosives Crobute
        </div>
    </div>

    
    

        <h2>1. Stack Tecnol├│gica</h2>
        <table>
            <thead>
                <tr>
                    <th>Componente</th>
                    <th>Tecnologia</th>
                    <th>Vers├úo M├¡nima</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Servidor Web</strong></td>
                    <td>Apache com <code>mod_rewrite</code></td>
                    <td>2.4+</td>
                </tr>
                <tr>
                    <td><strong>Linguagem Backend</strong></td>
                    <td>PHP Nativo ÔÇö Padr├úo MVC</td>
                    <td>8.2+</td>
                </tr>
                <tr>
                    <td><strong>Base de Dados</strong></td>
                    <td>MariaDB / MySQL via PDO</td>
                    <td>MariaDB 10.4+ / MySQL 8.0</td>
                </tr>
                <tr>
                    <td><strong>Extens├Áes PHP</strong></td>
                    <td><code>PDO</code>, <code>GD</code>, <code>finfo</code>, <code>session</code>,
                        <code>fileinfo</code>
                    </td>
                    <td>ÔÇö</td>
                </tr>
                <tr>
                    <td><strong>Gr├íficos</strong></td>
                    <td>Apache ECharts (CDN)</td>
                    <td>5.x</td>
                </tr>
                <tr>
                    <td><strong>Visualizador PDF</strong></td>
                    <td>PDF.js (Mozilla, CDN)</td>
                    <td>3.x</td>
                </tr>
                <tr>
                    <td><strong>UI Framework</strong></td>
                    <td>Bootstrap 5 + Ionicons + FontAwesome</td>
                    <td>5.3 / 7.x</td>
                </tr>
                <tr>
                    <td><strong>Sistema de Estilos</strong></td>
                    <td>CSS Nativo (Mobile-First) + Global Responsive CSS</td>
                    <td>v1.0 (Custom)</td>
                </tr>
            </tbody>
        </table>

        <h2>2. Arquitetura MVC e Estrutura de Pastas</h2>
        <p>O projeto segue o padr├úo <strong>Model-View-Controller</strong> puro, sem depend├¬ncia de frameworks externos.
            O
            <code>index.php</code> serve como Front Controller ├║nico, recebendo todas as requisi├º├Áes via
            <code>.htaccess</code>.
        </p>

        <pre>
<span class="comment"># Estrutura de pastas do projeto GHS v1.0</span>
green/
Ôö£ÔöÇÔöÇ <span class="key">index.php</span>           <span class="comment"># Front Controller ÔÇö ponto de entrada ├║nico</span>
Ôö£ÔöÇÔöÇ <span class="key">.htaccess</span>           <span class="comment"># URL Rewriting: /controlador/acao ÔåÆ index.php</span>
Ôö£ÔöÇÔöÇ core/
Ôöé   Ôö£ÔöÇÔöÇ <span class="key">Database.php</span>    <span class="comment"># Singleton PDO ÔÇö conex├úo ├á base de dados</span>
Ôöé   Ôö£ÔöÇÔöÇ <span class="key">Router.php</span>      <span class="comment"># Roteamento de URLs para Controllers</span>
Ôöé   ÔööÔöÇÔöÇ <span class="key">Security.php</span>   <span class="comment"># CSRF, XSS, finfo, Input Sanitization</span>
Ôö£ÔöÇÔöÇ app/
Ôöé   Ôö£ÔöÇÔöÇ models/         <span class="comment"># L├│gica de neg├│cio e queries SQL</span>
Ôöé   Ôöé   Ôö£ÔöÇÔöÇ <span class="key">Academico.php</span>   <span class="comment"># Ranking, Hist├│rico, Certificados</span>
Ôöé   Ôöé   Ôö£ÔöÇÔöÇ <span class="key">Estudante.php</span>   <span class="comment"># Perfil, fotos, dados do aluno</span>
Ôöé   Ôöé   Ôö£ÔöÇÔöÇ <span class="key">Matricula.php</span>   <span class="comment"># Motor de Progress├úo Acad├®mica</span>
Ôöé   Ôöé   Ôö£ÔöÇÔöÇ <span class="key">Pagamento.php</span>   <span class="comment"># Tesouraria e recibos digitais</span>
Ôöé   Ôöé   ÔööÔöÇÔöÇ <span class="key">Utilizador.php</span>  <span class="comment"># Autentica├º├úo e gest├úo de sess├úo</span>
Ôöé   Ôö£ÔöÇÔöÇ controllers/    <span class="comment"># Orquestra├º├úo de fluxo e valida├º├Áes</span>
Ôöé   ÔööÔöÇÔöÇ views/          <span class="comment"># Templates HTML/PHP por portal</span>
Ôöé       Ôö£ÔöÇÔöÇ admin/
Ôöé       Ôö£ÔöÇÔöÇ estudante/
Ôöé       Ôö£ÔöÇÔöÇ professor/
Ôöé       ÔööÔöÇÔöÇ secretaria/
Ôö£ÔöÇÔöÇ public/
Ôöé   Ôö£ÔöÇÔöÇ uploads/        <span class="comment"># Documentos enviados pelos alunos</span>
Ôöé   Ôö£ÔöÇÔöÇ css/            <span class="comment"># Estilos globais e responsivos</span>
Ôöé   Ôöé   ÔööÔöÇÔöÇ <span class="key">responsive_global.css</span> <span class="comment"># N├║cleo da Responsividade Mobile-First</span>
Ôöé   ÔööÔöÇÔöÇ assets/         <span class="comment"># Imagens est├íticas e bibliotecas</span>
ÔööÔöÇÔöÇ docs/               <span class="comment"># Documenta├º├úo e manuais export├íveis</span>
</pre>

        <h2>3. Instala├º├úo Local (XAMPP)</h2>
        <ol>
            <li>Clone ou copie o projeto para <code>C:\xampp\htdocs\green\</code>.</li>
            <li>Importe o ficheiro <code>docs/backups/database.sql</code> no phpMyAdmin.</li>
            <li>Verifique o ficheiro <code>core/Database.php</code> e ajuste as credenciais da base de dados:</li>
        </ol>
        <pre>
<span class="comment">// core/Database.php ÔÇö Configura├º├úo da Conex├úo</span>
<span class="key">private</span> $host   = <span class="val">'localhost'</span>;
<span class="key">private</span> $dbname = <span class="val">'ghsespf_db'</span>;
<span class="key">private</span> $user   = <span class="val">'root'</span>;
<span class="key">private</span> $pass   = <span class="val">''</span>;
</pre>
        <ol start="4">
            <li>Certifique-se que o <code>mod_rewrite</code> est├í ativo no Apache e que o <code>.htaccess</code> est├í a
                ser lido (<code>AllowOverride All</code>).</li>
            <li>Aceda no browser (Teste Local): <code>http://localhost/green/auth</code></li>
        </ol>

        <h3>3.1 Adapta├º├úo para Produ├º├úo (Cloud / InfinityFree)</h3>
        <p>Acesso Global ├á Plataforma: <strong><a href="https://escola-ghs.wuaze.com" target="_blank" style="color:var(--accent); text-decoration:none;">https://escola-ghs.wuaze.com</a></strong></p>
        <p>Para hospedar a plataforma num servidor em produ├º├úo raiz ou cPanel alojamento Web compartilhado:</p>
        <ol>
            <li>No ficheiro <code>core/config.php</code>, altere <code>define('URL_ROOT', '/green');</code> para <code>define('URL_ROOT', '');</code> para garantir que as folhas de estilos e AJAX requests funcionam na raiz do dom├¡nio.</li>
            <li>No ficheiro <code>.htaccess</code> raiz, adicione <code>RewriteBase /</code> imediatamente abaixo de <code>RewriteEngine On</code> para evitar erros de loop HTTP 500 do Apache.</li>
        </ol>

        <h2>4. Roteamento e Front Controller</h2>
        <p>O ficheiro <code>.htaccess</code> redireciona todas as requisi├º├Áes para o <code>index.php</code>, que
            instancia o
            <code>Router.php</code> para mapear o URL ao controller e a├º├úo correspondentes.
        </p>
        <pre>
<span class="comment"># .htaccess ÔÇö Regras de Reescrita</span>
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
</pre>
        <p>Exemplo de mapeamento de URL:</p>
        <table>
            <thead>
                <tr>
                    <th>URL Amig├ível</th>
                    <th>Controller</th>
                    <th>A├º├úo</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>/auth</code></td>
                    <td><code>AuthController</code></td>
                    <td><code>index()</code></td>
                </tr>
                <tr>
                    <td><code>/estudante/notas</code></td>
                    <td><code>EstudanteController</code></td>
                    <td><code>notas()</code></td>
                </tr>
                <tr>
                    <td><code>/admin/dashboard</code></td>
                    <td><code>AdminController</code></td>
                    <td><code>dashboard()</code></td>
                </tr>
                <tr>
                    <td><code>/secretaria/matriculas</code></td>
                    <td><code>SecretariaController</code></td>
                    <td><code>matriculas()</code></td>
                </tr>
            </tbody>
        </table>

        <h2>5. Base de Dados ÔÇö Principais Tabelas</h2>
        <table>
            <thead>
                <tr>
                    <th>Tabela</th>
                    <th>Finalidade</th>
                    <th>Campos Chave</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>utilizadores</code></td>
                    <td>Autentica├º├úo e perfis de acesso</td>
                    <td>id, email, password_hash, role, status</td>
                </tr>
                <tr>
                    <td><code>estudantes</code></td>
                    <td>Dados acad├®micos do aluno</td>
                    <td>id, utilizador_id, foto_perfil, numero_aluno</td>
                </tr>
                <tr>
                    <td><code>matriculas</code></td>
                    <td>Ciclo de vida da matr├¡cula</td>
                    <td>id, estudante_id, status, turma_id, ano_letivo, turno</td>
                </tr>
                <tr>
                    <td><code>notas</code></td>
                    <td>Avalia├º├Áes por tipo e disciplina</td>
                    <td>id, estudante_id, avaliacao_id, nota, confirmado_admin</td>
                </tr>
                <tr>
                    <td><code>avaliacoes</code></td>
                    <td>Estrutura dos momentos de avalia├º├úo</td>
                    <td>id, disciplina_id, tipo_avaliacao_id, turma_id, semestre</td>
                </tr>
                <tr>
                    <td><code>pagamentos</code></td>
                    <td>Registo financeiro e comprovativos</td>
                    <td>id, estudante_id, valor, status, data_validacao</td>
                </tr>
                <tr>
                    <td><code>horarios</code></td>
                    <td>Grade hor├íria por turma e disciplina</td>
                    <td>id, turma_id, disciplina_id, dia_semana, hora_inicio</td>
                </tr>
                <tr>
                    <td><code>comunicados</code></td>
                    <td>Avisos institucionais com expira├º├úo</td>
                    <td>id, titulo, corpo, data_expiracao, publicado_por</td>
                </tr>
                <tr>
                    <td><code>certificados_merito</code></td>
                    <td>Certificados de ranking acad├®mico</td>
                    <td>id, estudante_id, semestre, posicao, media, status</td>
                </tr>
                <tr>
                    <td><code>logs_auditoria</code></td>
                    <td>Trilha de auditoria de a├º├Áes cr├¡ticas</td>
                    <td>id, utilizador_id, acao, ip, created_at</td>
                </tr>
                <tr>
                    <td><code>concordancia_notas</code></td>
                    <td>Reclama├º├Áes e fluxo de valida├º├úo ativa de notas</td>
                    <td>id, estudante_id, disciplina_id, status (Pendente, Respondido, Concordado, Resolvido), comentario</td>
                </tr>
                <tr>
                    <td><code>recibos_pos</code></td>
                    <td>Metadados de conformidade para recibos t├®rmicos</td>
                    <td>id, pagamento_id, qr_code_hash, data_impressao</td>
                </tr>
            </tbody>
        </table>

        <h2>6. Seguran├ºa ÔÇö Camadas de Prote├º├úo (Hardening)</h2>

        <h3>6.1 Prote├º├úo CSRF (Cross-Site Request Forgery)</h3>
        <p>Todos os formul├írios e chamadas AJAX que alteram dados implementam tokens CSRF obrigat├│rios. O token ├® gerado
            por
            sess├úo usando <code>bin2hex(random_bytes(32))</code> e validado antes de qualquer processamento.</p>
        <pre>
<span class="comment">// Gera├º├úo do token (em cada formul├írio)</span>
<span class="key">$_SESSION</span>[<span class="val">'csrf_token'</span>] = bin2hex(random_bytes(<span class="val">32</span>));

<span class="comment">// Valida├º├úo no controller (antes de qualquer POST)</span>
<span class="key">if</span> ($_POST[<span class="val">'csrf_token'</span>] !== $_SESSION[<span class="val">'csrf_token'</span>]) {
    http_response_code(<span class="val">403</span>);
    <span class="key">die</span>(<span class="val">'Token CSRF inv├ílido.'</span>);
}
</pre>

        <h3>6.2 Mitiga├º├úo XSS (Cross-Site Scripting)</h3>
        <p>Todos os dados din├ómicos exibidos nas views s├úo sanitizados com <code>htmlspecialchars()</code>. Inputs de
            utilizador s├úo tratados antes de serem armazenados ou utilizados em queries.</p>

        <h3>6.3 Prote├º├úo IDOR (Insecure Direct Object Reference)</h3>
        <p>Em endpoints sens├¡veis (ex: download de recibos, visualiza├º├úo de documentos), o sistema verifica
            explicitamente
            se o recurso solicitado pertence ao utilizador autenticado. Nunca se confia apenas no ID na URL.</p>
        <pre>
<span class="comment">// Exemplo: prote├º├úo IDOR no download de recibo</span>
<span class="key">$pagamento</span> = $this->pagamentoModel->getById($_GET[<span class="val">'id'</span>]);
<span class="key">if</span> ($pagamento[<span class="val">'estudante_id'</span>] !== $_SESSION[<span class="val">'estudante_id'</span>]) {
    http_response_code(<span class="val">403</span>);
    <span class="key">die</span>(<span class="val">'Acesso negado.'</span>);
}
</pre>

        <h3>6.4 SQL Injection ÔÇö PDO Prepared Statements</h3>
        <p>100% das consultas ├á base de dados utilizam <strong>PDO Prepared Statements</strong> com par├ómetros
            vinculados.
            Nenhuma concatena├º├úo direta de vari├íveis em strings SQL ├® permitida.</p>
        <pre>
<span class="comment">// Exemplo de query segura em Matricula.php</span>
<span class="key">$stmt</span> = $this->db->prepare(
    <span class="val">"SELECT * FROM notas WHERE estudante_id = :eid AND confirmado_admin = 1"</span>
);
<span class="key">$stmt</span>->execute([<span class="val">':eid'</span> => $estudante_id]);
</pre>

        <h3>6.5 Valida├º├úo de Uploads ÔÇö Magic Numbers (finfo)</h3>
        <p>O sistema verifica o tipo real dos ficheiros enviados usando a extens├úo <code>finfo</code>, que l├¬ os bytes
            de
            assinatura do in├¡cio do ficheiro (<em>Magic Numbers</em>) em vez de confiar na extens├úo fornecida pelo
            utilizador. Apenas PDF (<code>application/pdf</code>) e imagens JPEG/PNG s├úo aceites.</p>
        <div class="danger-box">
            <strong>­ƒö┤ Risco Mitigado:</strong> Sem esta verifica├º├úo, um atacante poderia renomear um ficheiro PHP
            malicioso
            para <code>documento.pdf</code> e executar c├│digo no servidor ap├│s o upload.
        </div>

        <h2>7. Motores de L├│gica de Neg├│cio</h2>

        <h3>7.1 Motor de Progress├úo Acad├®mica (Matricula.php)</h3>
        <p>O m├®todo <code>getDetailedAcademicStatus($estudante_id)</code> implementa o algoritmo central de determina├º├úo
            do
            estatuto acad├®mico de cada aluno:</p>
        <pre>
<span class="comment">// Regras de Progress├úo ÔÇö Acad├®mico (Calculado em Nota.php)</span>
<span class="key">foreach</span> ($grades <span class="key">as</span> $g) {
    <span class="key">if</span>     ($media >= <span class="val">12</span>) $status = <span class="val">'Aprovado'</span>; <span class="comment">// Aprovado Direto</span>
    <span class="key">elseif</span> ($media >= <span class="val">8</span>)  $status = <span class="val">'Recurso'</span>;  <span class="comment">// Eleg├¡vel para Recurso</span>
    <span class="key">else</span>               $status = <span class="val">'Reprovado'</span>; <span class="comment">// Reprovado (< 8)</span>
}

<span class="comment">// Barreira de Admiss├úo em Acad├®mico.php</span>
<span class="key">if</span> ($total_ac < <span class="val">8</span>) {
    <span class="key">return</span> [<span class="val">'pode_fazer_exame'</span> => <span class="key">false</span>, <span class="val">'situacao'</span> => <span class="val">'Reprovado'</span>];
}
</pre>
        <h3>7.2 M├íquina de Estados: Confirma├º├úo de Notas (Contestacao.php)</h3>
        <p>Implementa um ciclo de vida rigoroso para a valida├º├úo de avalia├º├Áes, permitindo que o aluno aceite ou conteste resultados:</p>
        <table>
            <tr><th>Estado</th><th>A├º├úo do Aluno</th><th>Pr├│ximo Estado</th></tr>
            <tr><td><b>Inexistente</b></td><td>Confirmar Nota</td><td><code>Concordado</code> (Encerrado)</td></tr>
            <tr><td><b>Inexistente</b></td><td>Contestar</td><td><code>Pendente</code> (Aguarda Prof)</td></tr>
            <tr><td><b>Pendente</b></td><td>Professor Responde</td><td><code>Respondido</code></td></tr>
            <tr><td><b>Respondido</b></td><td>Aceitar</td><td><code>Resolvido</code> (Encerrado)</td></tr>
            <tr><td><b>Respondido</b></td><td>Escalar (Discordar)</td><td><code>Impasse</code> (Aguarda Admin)</td></tr>
            <tr><td><b>Impasse</b></td><td>Admin Convoca</td><td><code>Aguardando_Comparecimento</code> (Alertas Ativos)</td></tr>
        </table>

        <h3>7.3 Sistema de Convocat├│rias de Alta Prioridade (v1.3)</h3>
        <p>Implementa├º├úo de um mecanismo de inje├º├úo de alertas no topo do viewport (Sticky Header) para ambos os portais (Aluno e Professor). Quando o Admin define uma data de reuni├úo, o sistema detecta o estado <code>Aguardando_Comparecimento</code> e for├ºa a exibi├º├úo dos detalhes da reuni├úo em gradiente de perigo (Danger Gradient), garantindo que nenhuma convocat├│ria passe despercebida.</p>

        <h3>7.2 Motor de Ranking e M├®rito (Academico.php)</h3>
        <p>Os m├®todos <code>getRankingByNivel()</code> e <code>getRankingEscola()</code> calculam dinamicamente as
            m├®dias
            gerias de todos os alunos com exame lan├ºado e exp├Áem os resultados para o dashboard e para a emiss├úo de
            certificados de m├®rito. A f├│rmula base ├®:</p>
        <pre>
<span class="comment">-- F├│rmula SQL do C├ílculo de M├®dia por Disciplina</span>
(AC1 + AC2 + AC3 + AC4 + Exame_Final) / 2 AS nota_disciplina

<span class="comment">-- M├®dia Geral do Aluno</span>
AVG(nota_disciplina) AS media_geral
</pre>

        <h3>7.3 Hist├│rico Global (Academico.php &gt; getGlobalHistory)</h3>
        <p>Consolida o registo vital├¡cio acad├®mico do aluno, agrupando todas as notas por Ano Letivo e Semestre. Cada
            disciplina ├® classificada como Aprovado, Reprovado ou Em Curso, servindo de base para emiss├úo de certid├Áes e
            an├ílise hist├│rica.</p>

        <h3>7.4 Motor de Inscri├º├úo Inteligente (MatriculaController.php)</h3>
        <p>O controlador de matr├¡cula p├║blica foi refatorado para distinguir automaticamente entre novos candidatos e estudantes internos j├í autenticados:</p>
        <pre>
<span class="comment">// 1. Identificar ou reutilizar utilizador existente</span>
<span class="key">if</span> (isset($_SESSION['user_id']) && $_POST['tipo_candidatura'] == <span class="val">'Estudante Interno'</span>) {
    $user_id = $_SESSION[<span class="val">'user_id'</span>]; <span class="comment">// Reutiliza conta</span>
    $is_new_user = <span class="key">false</span>;
} <span class="key">else</span> {
    $user_id = $userModel->insertUser(...); <span class="comment">// Novo</span>
    $is_new_user = <span class="key">true</span>;
}

<span class="comment">// 2. Criar ou atualizar perfil do estudante</span>
$existing = $estudanteModel->findByUserId($user_id);
<span class="key">if</span> ($existing) {
    $estudanteModel->updateEstudante($existing['id'], $profileData);
} <span class="key">else</span> {
    $estudante_id = $estudanteModel->createEstudante($profileData);
}
        </pre>
        <p>No frontend, a fun├º├úo <code>toggleInternalFields()</code> oculta/mostra elementos e remove/adiciona o atributo <code>required</code> conforme o tipo de candidato selecionado (ou detetado via sess├úo).</p>

        <p>Sistema de gera├º├úo de documentos em formato 80mm para impressoras POS t├®rmicas, agora unificado entre a Secretaria e Alunos, com as seguintes corre├º├Áes estabilizadas na v1.2:</p>
        <ul>
            <li><strong>Resolvido Array Error:</strong> O controlador foi atualizado para referenciar corretamente <code>getPagamentoById()</code> em vez de um m├®todo base indefinido, resolvendo os Null Pointers na gera├º├úo via Admin.</li>
            <li><strong>QR Code Encoder:</strong> Gera um hash contendo <code>ID_PAGAMENTO | VALOR | ID_ESTUDANTE</code> via API externa para valida├º├úo r├ípida por scanner.</li>
            <li><strong>Fallback Inteligente:</strong> Se o recibo espec├¡fico de matr├¡cula n├úo for encontrado pelo termo exato, o controlador agora busca o pagamento mais recente do mesmo ano letivo para garantir que o utilizador nunca receba um erro ou redirecionamento nulo.</li>
            <li><strong>Abertura em Nova Aba:</strong> Implementa├º├úo sistem├ítica de <code>target="_blank"</code> em todas as refer├¬ncias de recibos para facilitar a impress├úo sem perda de contexto da sess├úo.</li>
        </ul>

        <h2>9. UI Architecture ÔÇö Responsividade Global</h2>
        <h3>9.1 Estrat├®gia Mobile-First</h3>
        <p>A plataforma adota uma abordagem <strong>Mobile-First</strong> centralizada no ficheiro <code>public/css/responsive_global.css</code>. Esta arquitetura remove a necessidade de estilos inline ou ficheiros CSS duplicados por portal.</p>
        <table>
            <thead>
                <tr>
                    <th>Breakpoints</th>
                    <th>Design Goal</th>
                    <th>Container Max-Width</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>< 600px</td>
                    <td>Smartphones (Stacked columns)</td>
                    <td>100%</td>
                </tr>
                <tr>
                    <td>600px - 1024px</td>
                    <td>Tablets (Compact grid)</td>
                    <td>95%</td>
                </tr>
                <tr>
                    <td>> 1024px</td>
                    <td>Desktop (Dashboard standard)</td>
                    <td>1400px (<code>.ghs-container</code>)</td>
                </tr>
            </tbody>
        </table>

        <h3>9.2 O Contentor <code>.ghs-container</code></h3>
        <p>Para evitar distor├º├úo visual em ecr├ús UltraWide (2K/4K), o conte├║do principal ├® envolvido na classe <code>.ghs-container</code>, que limita a largura m├íxima a 1400px e centraliza o dashboard, mantendo a densidade de informa├º├úo ideal para profissionais.</p>

        <h2>8. Sistema de Auditoria</h2>
        <p>Todas as a├º├Áes cr├¡ticas do sistema s├úo registadas na tabela <code>logs_auditoria</code> com os seguintes
            campos:
        </p>
        <table>
            <thead>
                <tr>
                    <th>Campo</th>
                    <th>Tipo</th>
                    <th>Descri├º├úo</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>utilizador_id</code></td>
                    <td>INT</td>
                    <td>ID do utilizador que executou a a├º├úo</td>
                </tr>
                <tr>
                    <td><code>acao</code></td>
                    <td>VARCHAR</td>
                    <td>Descri├º├úo detalhada da opera├º├úo (ex: "Aprovar Matr├¡cula #42")</td>
                </tr>
                <tr>
                    <td><code>ip</code></td>
                    <td>VARCHAR</td>
                    <td>Endere├ºo IP do cliente no momento da a├º├úo</td>
                </tr>
                <tr>
                    <td><code>created_at</code></td>
                    <td>DATETIME</td>
                    <td>Timestamp exato da opera├º├úo</td>
                </tr>
            </tbody>
        </table>
        <p>As a├º├Áes auditadas incluem: aprova├º├úo/rejei├º├úo de matr├¡culas, valida├º├úo de pagamentos, altera├º├úo de
            passwords,
            cria├º├úo/elimina├º├úo de disciplinas e turmas, e emiss├úo de certificados de m├®rito.</p>

        <div class="success-box">
            <strong>Ô£à Princ├¡pio de Seguran├ºa:</strong> Os logs de auditoria s├úo de escrita ├║nica ÔÇö nenhum utilizador,
            incluindo o Administrador, pode editar ou eliminar entradas existentes. Isto garante a integridade do
            registo
            legal.
        </div>

    </div>
    <div class="footer">
        <span>&copy; 2026 Green Hard &amp; Softh ÔÇö Seguran├ºa de N├¡vel Profissional. <strong>By Diosives
                Crobute</strong></span>
        <span>README T├®cnico v1.1</span>
    </div>

</body>

</html>
