<?php
// Resumo Executivo GHS v1.0
?>
<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <meta charset="UTF-8">
    <title>GHS ÔÇö Resumo Executivo v1.3</title>
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
            <p>Documento de vis├úo estrat├®gica para Dire├º├úo e Gest├úo Institucional</p>
        </div>
        <div class="cover-right">
            <div class="version">v1.3</div><br>
            <strong>Data:</strong> Abril 2026<br>
            <strong>Classifica├º├úo:</strong> Uso Interno<br>
            <strong>Autor:</strong> Diosives Crobute
        </div>
    </div>




    <h2>1. Vis├úo Geral do Projeto</h2>
    <p>O <strong>GHS (Green Hard &amp; Softh)</strong> ├® uma plataforma de gest├úo acad├®mica e financeira
        desenvolvida em
        PHP nativo, concebida para eliminar processos manuais e pap├®is nas escolas superiores de inform├ítica. O
        ecossistema serve quatro perfis de utilizadores com portais independentes, garante a rastreabilidade de
        todas as
        opera├º├Áes e implementa padr├Áes de seguran├ºa de n├¡vel empresarial.</p>
    <p>Na vers├úo 1.0, foram consolidados o motor de regras pedag├│gicas, o sistema de intelig├¬ncia visual
        (Dashboards) e
        as camadas de prote├º├úo de dados, resultando num produto robusto e pronto para escala institucional.</p>

    <h2>2. Problema e Solu├º├úo</h2>
    <table>
        <thead>
            <tr>
                <th width="42%">Problema Anterior</th>
                <th width="58%">Solu├º├úo Implementada na Plataforma GHS</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Matr├¡culas presenciais com perda de documentos</td>
                <td>Portal de candidatura 100% digital com upload de B.I., Certificados e Comprovativos, validados
                    via
                    <strong>Integrador Visual Documental (PDF.js)</strong>.
                </td>
            </tr>
            <tr>
                <td>C├ílculo manual de m├®dias e progress├úo de ano</td>
                <td><strong>Motor Acad├®mico Aut├│nomo</strong>: determina automaticamente Aprova├º├úo (ÔëÑ12), Recurso
                    (8-11)
                    ou Repeti├º├úo de Ano (&lt;8 ou mais de 3 negativas).</td>
            </tr>
            <tr>
                <td>Hor├írios est├íticos distribu├¡dos em papel</td>
                <td>Grade hor├íria interativa e din├ómica por turma, vis├¡vel no portal do aluno e do professor, com
                    dados
                    em tempo real.</td>
            </tr>
            <tr>
                <td>Pagamentos sem rastreabilidade ou auditoria</td>
                <td>Sistema de Tesouraria com valida├º├úo de comprovativos e <strong>Registo Manual de
                        Pagamentos</strong>
                    presenciais.</td>
            </tr>
            <tr>
                <td>Falta de an├ílise visual dos dados da escola</td>
                <td><strong>Dashboards Estat├¡sticos</strong> com gr├íficos de crescimento de alunos por ano e
                    distribui├º├úo por turno (ECharts).</td>
            </tr>
            <tr>
                <td>Professores sem ferramentas pedag├│gicas digitais</td>
                <td>Portal docente com lan├ºamento de notas, registo de sum├írios digitais, marca├º├úo de faltas e
                    resposta
                    a reclama├º├Áes de alunos.</td>
            </tr>
            <tr>
                <td>Comunica├º├úo escolar descentralizada e ineficaz</td>
                <td>Sistema de Comunicados com <strong>Read Tracking</strong> (registo de leitura por utilizador) e
                    expira├º├úo autom├ítica de avisos.</td>
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
                <td><strong>ÔÜÖ´©Å Administra├º├úo</strong></td>
                <td>Diretor / Gestor</td>
                <td>Configura├º├úo global do sistema, auditoria de logs, gest├úo de utilizadores, an├ílise de dashboards
                    estat├¡sticos.</td>
            </tr>
            <tr>
                <td><strong>­ƒÅó Secretaria/Tesouraria</strong></td>
                <td>Administrativos</td>
                <td>Valida├º├úo de matr├¡culas e documentos, aprova├º├úo/rejei├º├úo de pagamentos, emiss├úo de recibos
                    digitais,
                    gest├úo de comunicados.</td>
            </tr>
            <tr>
                <td><strong>­ƒæ¿ÔÇì­ƒÅ½ Professor</strong></td>
                <td>Docentes</td>
                <td>Lan├ºamento de pautas e notas, registo de sum├írios e faltas, resposta a reclama├º├Áes de alunos,
                    consulta de hor├írios.</td>
            </tr>
            <tr>
                <td><strong>­ƒÄô Estudante</strong></td>
                <td>Alunos</td>
                <td>Submiss├úo de matr├¡cula, consulta de notas, hor├írios e hist├│rico global, pagamento de propinas,
                    leitura de comunicados.</td>
            </tr>
        </tbody>
    </table>

    <h2>4. Novas Funcionalidades da Vers├úo 1.0</h2>

    <h3>4.1 Dashboards de Intelig├¬ncia Operacional</h3>
    <p>O painel Administrativo foi equipado com visualiza├º├Áes gr├íficas em tempo real utilizando a biblioteca
        <strong>ECharts</strong>. As m├®tricas dispon├¡veis incluem:
    </p>
    <ul>
        <li><strong>Densidade Estudantil por Ano Letivo</strong>: evolu├º├úo do n├║mero de matriculados ao longo dos
            anos.
        </li>
        <li><strong>Distribui├º├úo por Turno</strong>: an├ílise da ocupa├º├úo de salas e docentes por turno (Manh├ú,
            Tarde,
            Noite).</li>
        <li><strong>Alerta de Propinas</strong>: monitoriza├º├úo em tempo real de propinas em atraso.</li>
    </ul>

    <h3>4.2 Motor de Progress├úo Acad├®mica Autom├ítica</h3>
    <p>Implementado no modelo <strong>Matricula.php</strong> e validado pelo motor <strong>Academico.php</strong>, o
        sistema aplica as seguintes regras pedag├│gicas sem interven├º├úo manual:</p>
    <table>
        <thead>
            <tr>
                <th>Cen├írio</th>
                <th>Condi├º├úo</th>
                <th>Resultado Autom├ítico</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Aprova├º├úo Direta</td>
                <td>M├®dia final ÔëÑ 12 em todas as disciplinas</td>
                <td><span class="badge badge-green">Aprovado Ô£ô</span></td>
            </tr>
            <tr>
                <td>Acesso a Exame de Recurso</td>
                <td>M├®dia entre 8 e 11.9 (8.0 m├¡nimo AC)</td>
                <td><span class="badge badge-yellow">Recurso ÔÜá</span></td>
            </tr>
            <tr>
                <td>Repeti├º├úo de Disciplina</td>
                <td>Nota AC < 8 ou M├®dia Final < 8</td>
                <td><span class="badge badge-red">Reprovado Ô£ù</span></td>
            </tr>
        </tbody>
    </table>

    <h3>4.3 Gest├úo de Tesouraria e Pagamento Manual</h3>
    <p>Al├®m da submiss├úo e valida├º├úo digital de comprovativos, a secretaria pode agora registar pagamentos em 
    pessoa: dep├│sitos banc├írios diretos s├úo lan├ºados manualmente pelo administrativo, activando imediatamente o status 
    acad├®mico do aluno e gerando um <strong>Recibo T├®rmico (POS 80mm)</strong> com <strong>QR Code din├ómico</strong> 
    de autentica├º├úo digital instant├ónea.</p>

    <h3>4.4 Hist├│rico Acad├®mico Global</h3>
    <p>Cada aluno tem acesso ao seu <strong>Hist├│rico Global</strong>, um registo imut├ível e vital├¡cio de todas as
        disciplinas conclu├¡das, com m├®dias, semestres e anos letivos. Este registo serve como base para a emiss├úo de
        certid├Áes e ├® gerido pelo modelo <strong>Academico.php > getGlobalHistory()</strong>.</p>

    <h3>4.5 Sistema de M├®rito Acad├®mico</h3>
    <p>A plataforma emite automaticamente <strong>Certificados de M├®rito</strong> para os melhores alunos por
        semestre e
        por n├¡vel. Os rankings s├úo calculados pela m├®dia aritm├®tica de todas as disciplinas com exame lan├ºado, e os
        certificados ficam vis├¡veis no portal do aluno.</p>

    <h3>4.6 Fluxo de Inscri├º├úo Simplificado para Estudantes Internos</h3>
    <p>O portal p├║blico de candidatura foi atualizado com l├│gica inteligente que distingue automaticamente entre
        <strong>novos candidatos</strong> e <strong>estudantes internos</strong> (alunos j├í registados na plataforma).
        Quando um aluno interno acede ao formul├írio de candidatura, o sistema:</p>
    <ul>
        <li><strong>Oculta campos redundantes</strong>: Escola de Proveni├¬ncia, Ano de Conclus├úo, M├®dia Final, Motiva├º├úo e Certificado de Habilita├º├Áes s├úo automaticamente escondidos, pois estes dados j├í existem no sistema.</li>
        <li><strong>Pr├®-preenche os dados pessoais</strong>: Nome, B.I., Email, Telefone, Morada e dados do encarregado s├úo preenchidos automaticamente a partir do perfil existente.</li>
        <li><strong>Reutiliza a conta existente</strong>: O backend identifica o utilizador j├í autenticado e associa a nova candidatura ├á conta existente, sem criar duplicados nem gerar novas credenciais.</li>
        <li><strong>P├ígina de confirma├º├úo adaptada</strong>: Ap├│s a submiss├úo, a p├ígina de sucesso n├úo exibe credenciais (que o aluno j├í possui), apresentando apenas a confirma├º├úo da submiss├úo e os pr├│ximos passos.</li>
    </ul>
    <div class="success-box">
        <strong>Ô£à Benef├¡cio Institucional:</strong> Este fluxo reduz o tempo de inscri├º├úo para estudantes em renova├º├úo de ano ou inscri├º├úo num novo curso, eliminando burocracia repetitiva e o risco de dados duplicados ou inconsistentes na base de dados.
    </div>

    <h3>4.7 Transpar├¬ncia Acad├®mica: Ciclo de Confirma├º├úo de Notas</h3>
    <p>A plataforma introduziu um fluxo de "Acordo de Notas" onde o aluno deve validar ativamente o resultado final. Isto reduz drasticamente os erros de lan├ºamento e as reclama├º├Áes presenciais, movendo o debate pedag├│gico para um ambiente digital audit├ível (Pendente -> Respondido -> Resolvido).</p>

    <h3>4.8 Deploy em Produ├º├úo e Responsividade Universal</h3>
    <p>A plataforma est├í agora configurada via <strong>.htaccess e core/config.php</strong> para suporte nativo em servidores Cloud e cPanel standard. Na vers├úo 1.2, implement├ímos um sistema de <strong>Responsividade Mobile-First</strong> unificado (<code>responsive_global.css</code>), garantindo que todos os portais ÔÇö Admin, Professor e Aluno ÔÇö ofere├ºam a mesma experi├¬ncia premium e profissional em smartphones, tablets e desktops de alta resolu├º├úo (4K).</p>

    <h3>4.9 Sistema de Alertas de Convocat├│ria (Novo v1.3)</h3>
    <p>Para garantir que reuni├Áes de media├º├úo de notas n├úo sejam perdidas, implement├ímos um sistema de <strong>Sticky Alerts</strong> (Cabe├ºalhos Fixos) de alta visibilidade. Tanto o professor quanto o aluno recebem um alerta vermelho no topo do ecr├ú assim que a coordena├º├úo agenda uma data de reuni├úo, for├ºando o conhecimento imediato da convocat├│ria.</p>

    <h3>4.9 Consist├¬ncia Visual e Branding</h3>
    <p>Padroniza├º├úo total da identidade visual nos quatro portais, com um tema escuro unificado para menus laterais e um sistema de grelha compacta para maximizar a visibilidade de dados financeiros e acad├®micos num ├║nico ecr├ú.</p>

    <h2>5. Arquitetura de Seguran├ºa</h2>
        de
        seguran├ºa de dados:</p>
    <ul>
        <li><strong>CSRF</strong>: Token criptogr├ífico ├║nico por sess├úo em todos os formul├írios e chamadas AJAX.
        </li>
        <li><strong>XSS</strong>: Sanitiza├º├úo sistem├ítica de todos os inputs e outputs din├ómicos.</li>
        <li><strong>SQLi</strong>: PDO Prepared Statements em 100% das consultas ├á base de dados.</li>
        <li><strong>IDOR</strong>: Verifica├º├úo de propriedade antes de servir qualquer ficheiro ou URL sens├¡vel.
        </li>
        <li><strong>Auditoria</strong>: Todas as a├º├Áes cr├¡ticas s├úo registadas com ID do utilizador, IP e timestamp.
        </li>
    </ul>

    <div class="alert-box">
        <strong>­ƒöÆ Nota de Seguran├ºa Institucional:</strong> As credenciais de Administrador n├úo devem ser
        partilhadas.
        Todas as a├º├Áes efetuadas sob a conta administrativa ficam registadas num log inviol├ível, servindo como prova
        legal em caso de auditoria.
    </div>

    <h2>6. Ficha T├®cnica</h2>
    <table>
        <tbody>
            <tr>
                <td width="30%"><strong>Plataforma</strong></td>
                <td>PHP 8.2 Nativo ÔÇö Padr├úo MVC sem frameworks</td>
            </tr>
            <tr>
                <td><strong>Dom├¡nio de Produ├º├úo</strong></td>
                <td><a href="https://escola-ghs.wuaze.com" style="color:var(--accent); text-decoration:none;">https://escola-ghs.wuaze.com</a></td>
            </tr>
            <tr>
                <td><strong>Servidor</strong></td>
                <td>Apache 2.4+ com mod_rewrite (Cloud/InfinityFree e XAMPP)</td>
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
                <td><strong>Seguran├ºa</strong></td>
                <td>CSRF Tokens, XSS Sanitization, IDOR Guards, finfo Upload Validation</td>
            </tr>
            <tr>
                <td><strong>Desenvolvedor</strong></td>
                <td>Diosives Crobute / Waro Campotcho</td>
            </tr>
            <tr>
                <td><strong>Vers├úo Atual</strong></td>
                <td>1.2 ÔÇö Abril 2026</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <span>&copy; 2026 Green Hard &amp; Softh ÔÇö Escola Superior de Inform├ítica. Documento de Uso Interno.</span>
        <span>Resumo Executivo v1.2</span>
    </div>

</body>

</html>
