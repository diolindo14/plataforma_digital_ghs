<?php
// README Técnico GHS v1.0
?>
<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <meta charset="UTF-8">
    <title>GHS — README Técnico v1.0</title>
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
            <h1>README Técnico da Plataforma</h1>
            <p>Arquitetura, Instalação, Segurança e Motores de Lógica de Negócio</p>
        </div>
        <div class="cover-right">
            <div class="version">v1.0</div><br>
            <strong>Data:</strong> Março 2026<br>
            <strong>Público-Alvo:</strong> Desenvolvedores / DevOps<br>
            <strong>Autor:</strong> Diosives Crobute
        </div>
    </div>

    
    

        <h2>1. Stack Tecnológica</h2>
        <table>
            <thead>
                <tr>
                    <th>Componente</th>
                    <th>Tecnologia</th>
                    <th>Versão Mínima</th>
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
                    <td>PHP Nativo — Padrão MVC</td>
                    <td>8.2+</td>
                </tr>
                <tr>
                    <td><strong>Base de Dados</strong></td>
                    <td>MariaDB / MySQL via PDO</td>
                    <td>MariaDB 10.4+ / MySQL 8.0</td>
                </tr>
                <tr>
                    <td><strong>Extensões PHP</strong></td>
                    <td><code>PDO</code>, <code>GD</code>, <code>finfo</code>, <code>session</code>,
                        <code>fileinfo</code>
                    </td>
                    <td>—</td>
                </tr>
                <tr>
                    <td><strong>Gráficos</strong></td>
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
                    <td>Bootstrap 5 + FontAwesome 6</td>
                    <td>5.x</td>
                </tr>
            </tbody>
        </table>

        <h2>2. Arquitetura MVC e Estrutura de Pastas</h2>
        <p>O projeto segue o padrão <strong>Model-View-Controller</strong> puro, sem dependência de frameworks externos.
            O
            <code>index.php</code> serve como Front Controller único, recebendo todas as requisições via
            <code>.htaccess</code>.
        </p>

        <pre>
<span class="comment"># Estrutura de pastas do projeto GHS v1.0</span>
green/
├── <span class="key">index.php</span>           <span class="comment"># Front Controller — ponto de entrada único</span>
├── <span class="key">.htaccess</span>           <span class="comment"># URL Rewriting: /controlador/acao → index.php</span>
├── core/
│   ├── <span class="key">Database.php</span>    <span class="comment"># Singleton PDO — conexão à base de dados</span>
│   ├── <span class="key">Router.php</span>      <span class="comment"># Roteamento de URLs para Controllers</span>
│   └── <span class="key">Security.php</span>   <span class="comment"># CSRF, XSS, finfo, Input Sanitization</span>
├── app/
│   ├── models/         <span class="comment"># Lógica de negócio e queries SQL</span>
│   │   ├── <span class="key">Academico.php</span>   <span class="comment"># Ranking, Histórico, Certificados</span>
│   │   ├── <span class="key">Estudante.php</span>   <span class="comment"># Perfil, fotos, dados do aluno</span>
│   │   ├── <span class="key">Matricula.php</span>   <span class="comment"># Motor de Progressão Académica</span>
│   │   ├── <span class="key">Pagamento.php</span>   <span class="comment"># Tesouraria e recibos digitais</span>
│   │   └── <span class="key">Utilizador.php</span>  <span class="comment"># Autenticação e gestão de sessão</span>
│   ├── controllers/    <span class="comment"># Orquestração de fluxo e validações</span>
│   └── views/          <span class="comment"># Templates HTML/PHP por portal</span>
│       ├── admin/
│       ├── estudante/
│       ├── professor/
│       └── secretaria/
├── public/
│   ├── uploads/        <span class="comment"># Documentos enviados pelos alunos</span>
│   └── assets/         <span class="comment"># CSS, JS, imagens estáticas</span>
└── docs/               <span class="comment"># Documentação e manuais exportáveis</span>
</pre>

        <h2>3. Instalação Local (XAMPP)</h2>
        <ol>
            <li>Clone ou copie o projeto para <code>C:\xampp\htdocs\green\</code>.</li>
            <li>Importe o ficheiro <code>docs/backups/database.sql</code> no phpMyAdmin.</li>
            <li>Verifique o ficheiro <code>core/Database.php</code> e ajuste as credenciais da base de dados:</li>
        </ol>
        <pre>
<span class="comment">// core/Database.php — Configuração da Conexão</span>
<span class="key">private</span> $host   = <span class="val">'localhost'</span>;
<span class="key">private</span> $dbname = <span class="val">'ghsespf_db'</span>;
<span class="key">private</span> $user   = <span class="val">'root'</span>;
<span class="key">private</span> $pass   = <span class="val">''</span>;
</pre>
        <ol start="4">
            <li>Certifique-se que o <code>mod_rewrite</code> está ativo no Apache e que o <code>.htaccess</code> está a
                ser
                lido (<code>AllowOverride All</code>).</li>
            <li>Aceda no browser: <code>http://localhost/green/auth</code></li>
        </ol>

        <h2>4. Roteamento e Front Controller</h2>
        <p>O ficheiro <code>.htaccess</code> redireciona todas as requisições para o <code>index.php</code>, que
            instancia o
            <code>Router.php</code> para mapear o URL ao controller e ação correspondentes.
        </p>
        <pre>
<span class="comment"># .htaccess — Regras de Reescrita</span>
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
</pre>
        <p>Exemplo de mapeamento de URL:</p>
        <table>
            <thead>
                <tr>
                    <th>URL Amigável</th>
                    <th>Controller</th>
                    <th>Ação</th>
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

        <h2>5. Base de Dados — Principais Tabelas</h2>
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
                    <td>Autenticação e perfis de acesso</td>
                    <td>id, email, password_hash, role, status</td>
                </tr>
                <tr>
                    <td><code>estudantes</code></td>
                    <td>Dados académicos do aluno</td>
                    <td>id, utilizador_id, foto_perfil, numero_aluno</td>
                </tr>
                <tr>
                    <td><code>matriculas</code></td>
                    <td>Ciclo de vida da matrícula</td>
                    <td>id, estudante_id, status, turma_id, ano_letivo, turno</td>
                </tr>
                <tr>
                    <td><code>notas</code></td>
                    <td>Avaliações por tipo e disciplina</td>
                    <td>id, estudante_id, avaliacao_id, nota, confirmado_admin</td>
                </tr>
                <tr>
                    <td><code>avaliacoes</code></td>
                    <td>Estrutura dos momentos de avaliação</td>
                    <td>id, disciplina_id, tipo_avaliacao_id, turma_id, semestre</td>
                </tr>
                <tr>
                    <td><code>pagamentos</code></td>
                    <td>Registo financeiro e comprovativos</td>
                    <td>id, estudante_id, valor, status, data_validacao</td>
                </tr>
                <tr>
                    <td><code>horarios</code></td>
                    <td>Grade horária por turma e disciplina</td>
                    <td>id, turma_id, disciplina_id, dia_semana, hora_inicio</td>
                </tr>
                <tr>
                    <td><code>comunicados</code></td>
                    <td>Avisos institucionais com expiração</td>
                    <td>id, titulo, corpo, data_expiracao, publicado_por</td>
                </tr>
                <tr>
                    <td><code>certificados_merito</code></td>
                    <td>Certificados de ranking académico</td>
                    <td>id, estudante_id, semestre, posicao, media, status</td>
                </tr>
                <tr>
                    <td><code>logs_auditoria</code></td>
                    <td>Trilha de auditoria de ações críticas</td>
                    <td>id, utilizador_id, acao, ip, created_at</td>
                </tr>
                <tr>
                    <td><code>concordancia_notas</code></td>
                    <td>Reclamações e respostas de notas</td>
                    <td>id, estudante_id, disciplina_id, status, comentario</td>
                </tr>
            </tbody>
        </table>

        <h2>6. Segurança — Camadas de Proteção (Hardening)</h2>

        <h3>6.1 Proteção CSRF (Cross-Site Request Forgery)</h3>
        <p>Todos os formulários e chamadas AJAX que alteram dados implementam tokens CSRF obrigatórios. O token é gerado
            por
            sessão usando <code>bin2hex(random_bytes(32))</code> e validado antes de qualquer processamento.</p>
        <pre>
<span class="comment">// Geração do token (em cada formulário)</span>
<span class="key">$_SESSION</span>[<span class="val">'csrf_token'</span>] = bin2hex(random_bytes(<span class="val">32</span>));

<span class="comment">// Validação no controller (antes de qualquer POST)</span>
<span class="key">if</span> ($_POST[<span class="val">'csrf_token'</span>] !== $_SESSION[<span class="val">'csrf_token'</span>]) {
    http_response_code(<span class="val">403</span>);
    <span class="key">die</span>(<span class="val">'Token CSRF inválido.'</span>);
}
</pre>

        <h3>6.2 Mitigação XSS (Cross-Site Scripting)</h3>
        <p>Todos os dados dinâmicos exibidos nas views são sanitizados com <code>htmlspecialchars()</code>. Inputs de
            utilizador são tratados antes de serem armazenados ou utilizados em queries.</p>

        <h3>6.3 Proteção IDOR (Insecure Direct Object Reference)</h3>
        <p>Em endpoints sensíveis (ex: download de recibos, visualização de documentos), o sistema verifica
            explicitamente
            se o recurso solicitado pertence ao utilizador autenticado. Nunca se confia apenas no ID na URL.</p>
        <pre>
<span class="comment">// Exemplo: proteção IDOR no download de recibo</span>
<span class="key">$pagamento</span> = $this->pagamentoModel->getById($_GET[<span class="val">'id'</span>]);
<span class="key">if</span> ($pagamento[<span class="val">'estudante_id'</span>] !== $_SESSION[<span class="val">'estudante_id'</span>]) {
    http_response_code(<span class="val">403</span>);
    <span class="key">die</span>(<span class="val">'Acesso negado.'</span>);
}
</pre>

        <h3>6.4 SQL Injection — PDO Prepared Statements</h3>
        <p>100% das consultas à base de dados utilizam <strong>PDO Prepared Statements</strong> com parâmetros
            vinculados.
            Nenhuma concatenação direta de variáveis em strings SQL é permitida.</p>
        <pre>
<span class="comment">// Exemplo de query segura em Matricula.php</span>
<span class="key">$stmt</span> = $this->db->prepare(
    <span class="val">"SELECT * FROM notas WHERE estudante_id = :eid AND confirmado_admin = 1"</span>
);
<span class="key">$stmt</span>->execute([<span class="val">':eid'</span> => $estudante_id]);
</pre>

        <h3>6.5 Validação de Uploads — Magic Numbers (finfo)</h3>
        <p>O sistema verifica o tipo real dos ficheiros enviados usando a extensão <code>finfo</code>, que lê os bytes
            de
            assinatura do início do ficheiro (<em>Magic Numbers</em>) em vez de confiar na extensão fornecida pelo
            utilizador. Apenas PDF (<code>application/pdf</code>) e imagens JPEG/PNG são aceites.</p>
        <div class="danger-box">
            <strong>🔴 Risco Mitigado:</strong> Sem esta verificação, um atacante poderia renomear um ficheiro PHP
            malicioso
            para <code>documento.pdf</code> e executar código no servidor após o upload.
        </div>

        <h2>7. Motores de Lógica de Negócio</h2>

        <h3>7.1 Motor de Progressão Académica (Matricula.php)</h3>
        <p>O método <code>getDetailedAcademicStatus($estudante_id)</code> implementa o algoritmo central de determinação
            do
            estatuto académico de cada aluno:</p>
        <pre>
<span class="comment">// Regras de Progressão — Matricula.php</span>
<span class="key">foreach</span> ($grades <span class="key">as</span> $g) {
    <span class="key">if</span>     ($media >= <span class="val">12</span>) $passedCount++;    <span class="comment">// Aprovado Direto</span>
    <span class="key">elseif</span> ($media >= <span class="val">8</span>)  $recursoCount++;   <span class="comment">// Elegível para Recurso</span>
    <span class="key">else</span>               $reprovadoCount++; <span class="comment">// Reprovado (&lt; 8)</span>
}

<span class="comment">// Regra das 3 Negativas</span>
<span class="key">if</span> ($reprovadoCount > <span class="val">0</span> || ($recursoCount + $reprovadoCount) > <span class="val">3</span>) {
    <span class="key">return</span> [<span class="val">'status'</span> => <span class="val">'Reprovado'</span>, <span class="val">'can_transit'</span> => <span class="key">false</span>];
}
</pre>

        <h3>7.2 Motor de Ranking e Mérito (Academico.php)</h3>
        <p>Os métodos <code>getRankingByNivel()</code> e <code>getRankingEscola()</code> calculam dinamicamente as
            médias
            gerias de todos os alunos com exame lançado e expõem os resultados para o dashboard e para a emissão de
            certificados de mérito. A fórmula base é:</p>
        <pre>
<span class="comment">-- Fórmula SQL do Cálculo de Média por Disciplina</span>
(AC1 + AC2 + AC3 + AC4 + Exame_Final) / 2 AS nota_disciplina

<span class="comment">-- Média Geral do Aluno</span>
AVG(nota_disciplina) AS media_geral
</pre>

        <h3>7.3 Histórico Global (Academico.php &gt; getGlobalHistory)</h3>
        <p>Consolida o registo vitalício académico do aluno, agrupando todas as notas por Ano Letivo e Semestre. Cada
            disciplina é classificada como Aprovado, Reprovado ou Em Curso, servindo de base para emissão de certidões e
            análise histórica.</p>

        <h3>7.4 Motor de Inscrição Inteligente (MatriculaController.php)</h3>
        <p>O controlador de matrícula pública foi refatorado para distinguir automaticamente entre novos candidatos e estudantes internos já autenticados:</p>
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
        <p>No frontend, a função <code>toggleInternalFields()</code> oculta/mostra elementos e remove/adiciona o atributo <code>required</code> conforme o tipo de candidato selecionado (ou detetado via sessão).</p>

        <h2>8. Sistema de Auditoria</h2>
        <p>Todas as ações críticas do sistema são registadas na tabela <code>logs_auditoria</code> com os seguintes
            campos:
        </p>
        <table>
            <thead>
                <tr>
                    <th>Campo</th>
                    <th>Tipo</th>
                    <th>Descrição</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>utilizador_id</code></td>
                    <td>INT</td>
                    <td>ID do utilizador que executou a ação</td>
                </tr>
                <tr>
                    <td><code>acao</code></td>
                    <td>VARCHAR</td>
                    <td>Descrição detalhada da operação (ex: "Aprovar Matrícula #42")</td>
                </tr>
                <tr>
                    <td><code>ip</code></td>
                    <td>VARCHAR</td>
                    <td>Endereço IP do cliente no momento da ação</td>
                </tr>
                <tr>
                    <td><code>created_at</code></td>
                    <td>DATETIME</td>
                    <td>Timestamp exato da operação</td>
                </tr>
            </tbody>
        </table>
        <p>As ações auditadas incluem: aprovação/rejeição de matrículas, validação de pagamentos, alteração de
            passwords,
            criação/eliminação de disciplinas e turmas, e emissão de certificados de mérito.</p>

        <div class="success-box">
            <strong>✅ Princípio de Segurança:</strong> Os logs de auditoria são de escrita única — nenhum utilizador,
            incluindo o Administrador, pode editar ou eliminar entradas existentes. Isto garante a integridade do
            registo
            legal.
        </div>

    </div>
    <div class="footer">
        <span>&copy; 2026 Green Hard &amp; Softh — Segurança de Nível Profissional. <strong>By Diosives
                Crobute</strong></span>
        <span>README Técnico v1.0</span>
    </div>

</body>

</html>
