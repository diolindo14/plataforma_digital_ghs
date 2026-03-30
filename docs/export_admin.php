<?php
// Resumo Executivo GHS v1.0
?>
<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <meta charset="UTF-8">
    <title>GHS — Resumo Executivo v1.0</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #111827;
            --accent: #2563eb;
            --accent-green: #059669;
            --light: #f9fafb;
            --border: #e2e8f0;
            --text: #374151;
            --muted: #6b7280;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text);
            background: #fff;
            padding: 40px 60px;
            line-height: 1.6;
            font-size: 13px;
        }

        /* CAPA */
        .cover {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            border-bottom: 2px solid var(--border);
            padding-bottom: 25px;
            margin-bottom: 35px;
        }

        .cover-left .logo {
            font-size: 16px;
            font-weight: 700;
            color: var(--primary);
            letter-spacing: 0.5px;
        }

        .cover-left .logo span {
            color: var(--accent);
        }

        .cover-left h1 {
            font-size: 26px;
            font-weight: 700;
            color: var(--primary);
            margin: 6px 0;
        }

        .cover-left p {
            color: var(--muted);
            font-size: 13px;
        }

        .cover-right {
            text-align: right;
            font-size: 12px;
            color: var(--muted);
        }

        .cover-right .version {
            display: inline-block;
            background: var(--light);
            border: 1px solid var(--border);
            color: var(--primary);
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        h2 {
            font-size: 15px;
            font-weight: 700;
            color: var(--primary);
            margin: 30px 0 12px;
            padding-bottom: 6px;
            border-bottom: 1px solid var(--border);
        }

        h3 {
            font-size: 14px;
            font-weight: 600;
            color: var(--primary);
            margin: 18px 0 8px;
        }

        h4 {
            font-size: 13px;
            font-weight: 600;
            color: var(--primary);
            margin: 12px 0 6px;
        }

        p {
            margin-bottom: 10px;
            text-align: justify;
        }

        ul,
        ol {
            padding-left: 20px;
            margin-bottom: 12px;
        }

        li {
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0 25px;
            font-size: 12px;
            border: 1px solid var(--border);
        }

        thead th {
            background: var(--light);
            color: var(--primary);
            padding: 10px 12px;
            text-align: left;
            font-weight: 600;
            border-bottom: 1px solid var(--border);
        }

        tbody td {
            border-bottom: 1px solid var(--border);
            padding: 8px 12px;
            vertical-align: top;
        }

        tbody tr:nth-child(even) td {
            background: #fafbfc;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 500;
            border: 1px solid transparent;
        }

        .badge-green {
            background: #f0fdf4;
            color: #166534;
            border-color: #bbf7d0;
        }

        .badge-blue {
            background: #eff6ff;
            color: #1e40af;
            border-color: #bfdbfe;
        }

        .badge-yellow {
            background: #fefce8;
            color: #854d0e;
            border-color: #fef08a;
        }

        .badge-red {
            background: #fef2f2;
            color: #991b1b;
            border-color: #fecaca;
        }

        .badge-orange {
            background: #fff7ed;
            color: #c2410c;
            border-color: #ffedd5;
        }

        /* CONTAINERS */
        .info-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-left: 3px solid var(--accent);
            border-radius: 4px;
            padding: 12px 16px;
            margin: 12px 0;
        }

        .warning-box {
            background: #fefce8;
            border: 1px solid #fef08a;
            border-left: 3px solid #eab308;
            border-radius: 4px;
            padding: 12px 16px;
            margin: 12px 0;
        }

        .success-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-left: 3px solid var(--accent-green);
            border-radius: 4px;
            padding: 12px 16px;
            margin: 12px 0;
        }

        .danger-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-left: 3px solid #ef4444;
            border-radius: 4px;
            padding: 12px 16px;
            margin: 12px 0;
        }

        .info-box strong,
        .warning-box strong,
        .success-box strong,
        .danger-box strong {
            display: block;
            margin-bottom: 4px;
            color: var(--primary);
        }

        .steps {
            counter-reset: step;
            list-style: none;
            padding: 0;
        }

        .steps li {
            counter-increment: step;
            display: flex;
            gap: 12px;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .steps li::before {
            content: counter(step);
            display: flex;
            align-items: center;
            justify-content: center;
            width: 22px;
            height: 22px;
            min-width: 22px;
            background: var(--light);
            color: var(--primary);
            border: 1px solid var(--border);
            border-radius: 50%;
            font-weight: 600;
            font-size: 11px;
            margin-top: 2px;
        }

        .steps li strong {
            color: var(--primary);
            display: block;
        }

        code {
            font-family: 'JetBrains Mono', monospace;
            background: var(--light);
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 11px;
            color: #b91c1c;
            border: 1px solid var(--border);
        }

        pre {
            font-family: 'JetBrains Mono', monospace;
            background: var(--light);
            color: var(--primary);
            padding: 12px;
            border-radius: 4px;
            font-size: 11px;
            line-height: 1.4;
            margin: 12px 0;
            overflow-x: auto;
            border: 1px solid var(--border);
        }

        pre .comment {
            color: var(--muted);
        }

        pre .key {
            color: var(--accent);
        }

        pre .val {
            color: var(--accent-green);
        }

        .metrics {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin: 15px 0 25px;
        }

        .metric-card {
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 16px;
            text-align: center;
            background: var(--light);
        }

        .metric-card .number {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
        }

        .metric-card .label {
            font-size: 11px;
            color: var(--muted);
            margin-top: 4px;
        }

        .faq-item {
            border: 1px solid var(--border);
            border-radius: 4px;
            margin-bottom: 8px;
            overflow: hidden;
        }

        .faq-q {
            background: var(--light);
            padding: 10px 14px;
            font-weight: 600;
            color: var(--primary);
            font-size: 12px;
        }

        .faq-a {
            padding: 10px 14px;
            font-size: 12px;
            border-top: 1px solid var(--border);
        }

        .footer {
            margin-top: 40px;
            padding-top: 15px;
            font-size: 11px;
            color: var(--muted);
            display: flex;
            justify-content: space-between;
            border-top: 1px solid var(--border);
        }

        @media print {
            @page {
                margin: 0.5cm;
            }

            body {
                padding: 30px 40px;
            }

            .cover-bar {
                display: none;
            }

            .cover {
                border-bottom: 2px solid #ccc;
                padding-bottom: 15px;
                margin-bottom: 20px;
            }

            table,
            pre,
            .info-box,
            .warning-box,
            .success-box,
            .metric-card {
                box-shadow: none;
                border: 1px solid #ccc;
            }
        }
    </style>
</head>

<body>



    <div class="cover">
        <div class="cover-left">
            <img src="../img/logo.jpg" alt="GREEN HARD &amp; SOFTH"
                style="height: 60px; border-radius: 6px; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <h1>Resumo Executivo da Plataforma</h1>
            <p>Documento de visão estratégica para Direção e Gestão Institucional</p>
        </div>
        <div class="cover-right">
            <div class="version">v1.0</div><br>
            <strong>Data:</strong> Março 2026<br>
            <strong>Classificação:</strong> Uso Interno<br>
            <strong>Autor:</strong> Diosives Crobute
        </div>
    </div>




    <h2>1. Visão Geral do Projeto</h2>
    <p>O <strong>GHS (Green Hard &amp; Softh)</strong> é uma plataforma de gestão académica e financeira
        desenvolvida em
        PHP nativo, concebida para eliminar processos manuais e papéis nas escolas superiores de informática. O
        ecossistema serve quatro perfis de utilizadores com portais independentes, garante a rastreabilidade de
        todas as
        operações e implementa padrões de segurança de nível empresarial.</p>
    <p>Na versão 1.0, foram consolidados o motor de regras pedagógicas, o sistema de inteligência visual
        (Dashboards) e
        as camadas de proteção de dados, resultando num produto robusto e pronto para escala institucional.</p>

    <h2>2. Problema e Solução</h2>
    <table>
        <thead>
            <tr>
                <th width="42%">Problema Anterior</th>
                <th width="58%">Solução Implementada na Plataforma GHS</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Matrículas presenciais com perda de documentos</td>
                <td>Portal de candidatura 100% digital com upload de B.I., Certificados e Comprovativos, validados
                    via
                    <strong>Integrador Visual Documental (PDF.js)</strong>.
                </td>
            </tr>
            <tr>
                <td>Cálculo manual de médias e progressão de ano</td>
                <td><strong>Motor Académico Autónomo</strong>: determina automaticamente Aprovação (≥12), Recurso
                    (8-11)
                    ou Repetição de Ano (&lt;8 ou mais de 3 negativas).</td>
            </tr>
            <tr>
                <td>Horários estáticos distribuídos em papel</td>
                <td>Grade horária interativa e dinâmica por turma, visível no portal do aluno e do professor, com
                    dados
                    em tempo real.</td>
            </tr>
            <tr>
                <td>Pagamentos sem rastreabilidade ou auditoria</td>
                <td>Sistema de Tesouraria com validação de comprovativos e <strong>Registo Manual de
                        Pagamentos</strong>
                    presenciais.</td>
            </tr>
            <tr>
                <td>Falta de análise visual dos dados da escola</td>
                <td><strong>Dashboards Estatísticos</strong> com gráficos de crescimento de alunos por ano e
                    distribuição por turno (ECharts).</td>
            </tr>
            <tr>
                <td>Professores sem ferramentas pedagógicas digitais</td>
                <td>Portal docente com lançamento de notas, registo de sumários digitais, marcação de faltas e
                    resposta
                    a reclamações de alunos.</td>
            </tr>
            <tr>
                <td>Comunicação escolar descentralizada e ineficaz</td>
                <td>Sistema de Comunicados com <strong>Read Tracking</strong> (registo de leitura por utilizador) e
                    expiração automática de avisos.</td>
            </tr>
        </tbody>
    </table>

    <h2>3. Portais e Utilizadores</h2>
    <table>
        <thead>
            <tr>
                <th>Portal</th>
                <th>Utilizador</th>
                <th>Principais Responsabilidades</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>⚙️ Administração</strong></td>
                <td>Diretor / Gestor</td>
                <td>Configuração global do sistema, auditoria de logs, gestão de utilizadores, análise de dashboards
                    estatísticos.</td>
            </tr>
            <tr>
                <td><strong>🏢 Secretaria/Tesouraria</strong></td>
                <td>Administrativos</td>
                <td>Validação de matrículas e documentos, aprovação/rejeição de pagamentos, emissão de recibos
                    digitais,
                    gestão de comunicados.</td>
            </tr>
            <tr>
                <td><strong>👨‍🏫 Professor</strong></td>
                <td>Docentes</td>
                <td>Lançamento de pautas e notas, registo de sumários e faltas, resposta a reclamações de alunos,
                    consulta de horários.</td>
            </tr>
            <tr>
                <td><strong>🎓 Estudante</strong></td>
                <td>Alunos</td>
                <td>Submissão de matrícula, consulta de notas, horários e histórico global, pagamento de propinas,
                    leitura de comunicados.</td>
            </tr>
        </tbody>
    </table>

    <h2>4. Novas Funcionalidades da Versão 1.0</h2>

    <h3>4.1 Dashboards de Inteligência Operacional</h3>
    <p>O painel Administrativo foi equipado com visualizações gráficas em tempo real utilizando a biblioteca
        <strong>ECharts</strong>. As métricas disponíveis incluem:
    </p>
    <ul>
        <li><strong>Densidade Estudantil por Ano Letivo</strong>: evolução do número de matriculados ao longo dos
            anos.
        </li>
        <li><strong>Distribuição por Turno</strong>: análise da ocupação de salas e docentes por turno (Manhã,
            Tarde,
            Noite).</li>
        <li><strong>Alerta de Propinas</strong>: monitorização em tempo real de propinas em atraso.</li>
    </ul>

    <h3>4.2 Motor de Progressão Académica Automática</h3>
    <p>Implementado no modelo <strong>Matricula.php</strong> e validado pelo motor <strong>Academico.php</strong>, o
        sistema aplica as seguintes regras pedagógicas sem intervenção manual:</p>
    <table>
        <thead>
            <tr>
                <th>Cenário</th>
                <th>Condição</th>
                <th>Resultado Automático</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Aprovação por Trânsito</td>
                <td>Média final ≥ 12 em todas as disciplinas</td>
                <td><span class="badge badge-green">Aprovado ✓</span></td>
            </tr>
            <tr>
                <td>Acesso a Exame de Recurso</td>
                <td>Média entre 8 e 11 (máx. 3 disciplinas)</td>
                <td><span class="badge badge-yellow">Recurso ⚠</span></td>
            </tr>
            <tr>
                <td>Repetição de Ano</td>
                <td>Nota &lt; 8 em qualquer disciplina OU mais de 3 negativas acumuladas</td>
                <td><span class="badge badge-red">Reprovado ✗</span></td>
            </tr>
        </tbody>
    </table>

    <h3>4.3 Gestão de Tesouraria e Pagamento Manual</h3>
    <p>Além da submissão e validação digital de comprovativos, a secretaria pode agora registar pagamentos em
        pessoa:
        depósitos bancários diretos são lançados manualmente pelo administrativo, activando imediatamente o status
        académico do aluno e gerando um recibo digital com número de série único.</p>

    <h3>4.4 Histórico Académico Global</h3>
    <p>Cada aluno tem acesso ao seu <strong>Histórico Global</strong>, um registo imutável e vitalício de todas as
        disciplinas concluídas, com médias, semestres e anos letivos. Este registo serve como base para a emissão de
        certidões e é gerido pelo modelo <strong>Academico.php > getGlobalHistory()</strong>.</p>

    <h3>4.5 Sistema de Mérito Académico</h3>
    <p>A plataforma emite automaticamente <strong>Certificados de Mérito</strong> para os melhores alunos por
        semestre e
        por nível. Os rankings são calculados pela média aritmética de todas as disciplinas com exame lançado, e os
        certificados ficam visíveis no portal do aluno.</p>

    <h3>4.6 Fluxo de Inscrição Simplificado para Estudantes Internos</h3>
    <p>O portal público de candidatura foi atualizado com lógica inteligente que distingue automaticamente entre
        <strong>novos candidatos</strong> e <strong>estudantes internos</strong> (alunos já registados na plataforma).
        Quando um aluno interno acede ao formulário de candidatura, o sistema:</p>
    <ul>
        <li><strong>Oculta campos redundantes</strong>: Escola de Proveniência, Ano de Conclusão, Média Final, Motivação e Certificado de Habilitações são automaticamente escondidos, pois estes dados já existem no sistema.</li>
        <li><strong>Pré-preenche os dados pessoais</strong>: Nome, B.I., Email, Telefone, Morada e dados do encarregado são preenchidos automaticamente a partir do perfil existente.</li>
        <li><strong>Reutiliza a conta existente</strong>: O backend identifica o utilizador já autenticado e associa a nova candidatura à conta existente, sem criar duplicados nem gerar novas credenciais.</li>
        <li><strong>Página de confirmação adaptada</strong>: Após a submissão, a página de sucesso não exibe credenciais (que o aluno já possui), apresentando apenas a confirmação da submissão e os próximos passos.</li>
    </ul>
    <div class="success-box">
        <strong>✅ Benefício Institucional:</strong> Este fluxo reduz o tempo de inscrição para estudantes em renovação de ano ou inscrição num novo curso, eliminando burocracia repetitiva e o risco de dados duplicados ou inconsistentes na base de dados.
    </div>


    <p>A plataforma implementa proteção multicamada, garantindo conformidade com as melhores práticas internacionais
        de
        segurança de dados:</p>
    <ul>
        <li><strong>CSRF</strong>: Token criptográfico único por sessão em todos os formulários e chamadas AJAX.
        </li>
        <li><strong>XSS</strong>: Sanitização sistemática de todos os inputs e outputs dinâmicos.</li>
        <li><strong>SQLi</strong>: PDO Prepared Statements em 100% das consultas à base de dados.</li>
        <li><strong>IDOR</strong>: Verificação de propriedade antes de servir qualquer ficheiro ou URL sensível.
        </li>
        <li><strong>Auditoria</strong>: Todas as ações críticas são registadas com ID do utilizador, IP e timestamp.
        </li>
    </ul>

    <div class="alert-box">
        <strong>🔒 Nota de Segurança Institucional:</strong> As credenciais de Administrador não devem ser
        partilhadas.
        Todas as ações efetuadas sob a conta administrativa ficam registadas num log inviolável, servindo como prova
        legal em caso de auditoria.
    </div>

    <h2>6. Ficha Técnica</h2>
    <table>
        <tbody>
            <tr>
                <td width="30%"><strong>Plataforma</strong></td>
                <td>PHP 8.2 Nativo — Padrão MVC sem frameworks</td>
            </tr>
            <tr>
                <td><strong>Servidor</strong></td>
                <td>Apache 2.4+ com mod_rewrite (XAMPP compatível)</td>
            </tr>
            <tr>
                <td><strong>Base de Dados</strong></td>
                <td>MariaDB 10.4+ / MySQL 8.0 via PDO</td>
            </tr>
            <tr>
                <td><strong>Interface</strong></td>
                <td>Bootstrap 5, ECharts, PDF.js, FontAwesome</td>
            </tr>
            <tr>
                <td><strong>Segurança</strong></td>
                <td>CSRF Tokens, XSS Sanitization, IDOR Guards, finfo Upload Validation</td>
            </tr>
            <tr>
                <td><strong>Desenvolvedor</strong></td>
                <td>Diosives Crobute / Waro Campotcho</td>
            </tr>
            <tr>
                <td><strong>Versão Atual</strong></td>
                <td>1.0 — Março 2026</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <span>&copy; 2026 Green Hard &amp; Softh — Escola Superior de Informática. Documento de Uso Interno.</span>
        <span>Resumo Executivo v1.0</span>
    </div>

</body>

</html>