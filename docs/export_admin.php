<?php
// Resumo Executivo GHS v5.0
?>
<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <meta charset="UTF-8">
    <title>GHS — Resumo Executivo v5.0</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1e293b;
            --accent: #10b981;
            --accent-blue: #3b82f6;
            --light: #f8fafc;
            --border: #e2e8f0;
            --text: #334155;
            --muted: #64748b;
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
            padding: 50px 60px;
            line-height: 1.7;
            font-size: 14px;
        }

        /* Cabeçalho */
        .cover {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            border-bottom: 3px solid var(--accent);
            padding-bottom: 25px;
            margin-bottom: 35px;
        }

        .cover-left .logo {
            font-size: 22px;
            font-weight: 700;
            color: var(--primary);
            letter-spacing: -0.5px;
        }

        .cover-left .logo span {
            color: var(--accent);
        }

        .cover-left h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary);
            margin-top: 8px;
        }

        .cover-left p {
            color: var(--muted);
            font-size: 13px;
            margin-top: 4px;
        }

        .cover-right {
            text-align: right;
            font-size: 12px;
            color: var(--muted);
        }

        .cover-right .version {
            display: inline-block;
            background: var(--primary);
            color: #fff;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        /* Conteúdo */
        h2 {
            font-size: 16px;
            font-weight: 700;
            color: var(--primary);
            border-left: 4px solid var(--accent);
            padding-left: 12px;
            margin: 35px 0 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        h3 {
            font-size: 14px;
            font-weight: 600;
            color: var(--accent-blue);
            margin: 20px 0 8px;
        }

        p {
            margin-bottom: 10px;
            text-align: justify;
        }

        ul,
        ol {
            padding-left: 20px;
            margin-bottom: 10px;
        }

        li {
            margin-bottom: 5px;
        }

        /* Tabelas */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0 25px;
            font-size: 13px;
        }

        thead th {
            background: var(--primary);
            color: #fff;
            padding: 11px 14px;
            text-align: left;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        tbody td {
            border: 1px solid var(--border);
            padding: 10px 14px;
            vertical-align: top;
        }

        tbody tr:nth-child(even) td {
            background: var(--light);
        }

        tbody tr:hover td {
            background: #f1f5f9;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-green {
            background: #dcfce7;
            color: #166534;
        }

        .badge-blue {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-yellow {
            background: #fef9c3;
            color: #854d0e;
        }

        .badge-red {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Caixas de destaque */
        .info-box {
            background: var(--light);
            border: 1px solid var(--border);
            border-left: 4px solid var(--accent-blue);
            border-radius: 6px;
            padding: 16px 20px;
            margin: 15px 0 25px;
        }

        .info-box strong {
            color: var(--primary);
        }

        .alert-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-left: 4px solid var(--accent);
            border-radius: 6px;
            padding: 16px 20px;
            margin: 20px 0;
        }

        .alert-box strong {
            color: #166534;
        }

        /* Grid de métricas */
        .metrics {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin: 20px 0 30px;
        }

        .metric-card {
            background: var(--light);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 20px;
            text-align: center;
        }

        .metric-card .number {
            font-size: 32px;
            font-weight: 700;
            color: var(--accent);
        }

        .metric-card .label {
            font-size: 12px;
            color: var(--muted);
            margin-top: 4px;
        }

        /* Rodapé */
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
            font-size: 11px;
            color: var(--muted);
            display: flex;
            justify-content: space-between;
        }

        /* Impressão */
        .no-print {
            background: #fefce8;
            border: 1px solid #fde047;
            border-radius: 6px;
            padding: 10px 20px;
            margin-bottom: 30px;
            text-align: center;
            font-size: 13px;
        }

        @media print {
            body {
                padding: 30px 40px;
            }

            .no-print {
                display: none;
            }

            .metrics {
                break-inside: avoid;
            }

            h2 {
                break-before: auto;
            }
        }
    </style>
</head>

<body>


    <div class="cover">
        <div class="cover-left">
            <div class="logo">GREEN HARD &amp; <span>SOFTH</span></div>
            <h1>Resumo Executivo da Plataforma</h1>
            <p>Documento de visão estratégica para Direção e Gestão Institucional</p>
        </div>
        <div class="cover-right">
            <div class="version">v5.0</div><br>
            <strong>Data:</strong> Março 2026<br>
            <strong>Classificação:</strong> Uso Interno<br>
            <strong>Autor:</strong> Diosives Crobute
        </div>
    </div>

    <h2>1. Visão Geral do Projeto</h2>
    <p>O <strong>GHS (Green Hard &amp; Softh)</strong> é uma plataforma de gestão académica e financeira desenvolvida em
        PHP nativo, concebida para eliminar processos manuais e papéis nas escolas superiores de informática. O
        ecossistema serve quatro perfis de utilizadores com portais independentes, garante a rastreabilidade de todas as
        operações e implementa padrões de segurança de nível empresarial.</p>
    <p>Na versão 5.0, foram consolidados o motor de regras pedagógicas, o sistema de inteligência visual (Dashboards) e
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
                <td>Portal de candidatura 100% digital com upload de B.I., Certificados e Comprovativos, validados via
                    <strong>Integrador Visual Documental (PDF.js)</strong>.</td>
            </tr>
            <tr>
                <td>Cálculo manual de médias e progressão de ano</td>
                <td><strong>Motor Académico Autónomo</strong>: determina automaticamente Aprovação (≥12), Recurso (8-11)
                    ou Repetição de Ano (&lt;8 ou mais de 3 negativas).</td>
            </tr>
            <tr>
                <td>Horários estáticos distribuídos em papel</td>
                <td>Grade horária interativa e dinâmica por turma, visível no portal do aluno e do professor, com dados
                    em tempo real.</td>
            </tr>
            <tr>
                <td>Pagamentos sem rastreabilidade ou auditoria</td>
                <td>Sistema de Tesouraria com validação de comprovativos e <strong>Registo Manual de Pagamentos</strong>
                    presenciais.</td>
            </tr>
            <tr>
                <td>Falta de análise visual dos dados da escola</td>
                <td><strong>Dashboards Estatísticos</strong> com gráficos de crescimento de alunos por ano e
                    distribuição por turno (ECharts).</td>
            </tr>
            <tr>
                <td>Professores sem ferramentas pedagógicas digitais</td>
                <td>Portal docente com lançamento de notas, registo de sumários digitais, marcação de faltas e resposta
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
                <td>Validação de matrículas e documentos, aprovação/rejeição de pagamentos, emissão de recibos digitais,
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

    <h2>4. Novas Funcionalidades da Versão 5.0</h2>

    <h3>4.1 Dashboards de Inteligência Operacional</h3>
    <p>O painel Administrativo foi equipado com visualizações gráficas em tempo real utilizando a biblioteca
        <strong>ECharts</strong>. As métricas disponíveis incluem:</p>
    <ul>
        <li><strong>Densidade Estudantil por Ano Letivo</strong>: evolução do número de matriculados ao longo dos anos.
        </li>
        <li><strong>Distribuição por Turno</strong>: análise da ocupação de salas e docentes por turno (Manhã, Tarde,
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
    <p>Além da submissão e validação digital de comprovativos, a secretaria pode agora registar pagamentos en pessoa:
        depósitos bancários diretos são lançados manualmente pelo administrativo, activando imediatamente o status
        académico do aluno e gerando um recibo digital com número de série único.</p>

    <h3>4.4 Histórico Académico Global</h3>
    <p>Cada aluno tem acesso ao seu <strong>Histórico Global</strong>, um registo imutável e vitalício de todas as
        disciplinas concluídas, com médias, semestres e anos letivos. Este registo serve como base para a emissão de
        certidões e é gerido pelo modelo <strong>Academico.php > getGlobalHistory()</strong>.</p>

    <h3>4.5 Sistema de Mérito Académico</h3>
    <p>A plataforma emite automaticamente <strong>Certificados de Mérito</strong> para os melhores alunos por semestre e
        por nível. Os rankings são calculados pela média aritmética de todas as disciplinas com exame lançado, e os
        certificados ficam visíveis no portal do aluno.</p>

    <h2>5. Segurança e Conformidade</h2>
    <p>A plataforma implementa proteção multicamada, garantindo conformidade com as melhores práticas internacionais de
        segurança de dados:</p>
    <ul>
        <li><strong>CSRF</strong>: Token criptográfico único por sessão em todos os formulários e chamadas AJAX.</li>
        <li><strong>XSS</strong>: Sanitização sistemática de todos os inputs e outputs dinâmicos.</li>
        <li><strong>SQLi</strong>: PDO Prepared Statements em 100% das consultas à base de dados.</li>
        <li><strong>IDOR</strong>: Verificação de propriedade antes de servir qualquer ficheiro ou URL sensível.</li>
        <li><strong>Auditoria</strong>: Todas as ações críticas são registadas com ID do utilizador, IP e timestamp.
        </li>
    </ul>

    <div class="alert-box">
        <strong>🔒 Nota de Segurança Institucional:</strong> As credenciais de Administrador não devem ser partilhadas.
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
                <td>5.0 — Março 2026</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <span>&copy; 2026 Green Hard &amp; Softh — Escola Superior de Informática. Documento de Uso Interno.</span>
        <span>Resumo Executivo v5.0</span>
    </div>

</body>

</html>